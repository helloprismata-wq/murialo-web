<?php

namespace App\Jobs;

use App\Models\AiProcessingResult;
use App\Models\Penilaian;
use App\Models\PenilaianDetail;
use App\Models\PenugasanTes;
use App\Services\AiEngineService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ProcessSmartGrading implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $penugasanTesId;
    public int $tries = 3;
    public int $timeout = 120;

    /**
     * Create a new job instance.
     */
    public function __construct(int $penugasanTesId)
    {
        $this->penugasanTesId = $penugasanTesId;
    }

    /**
     * Execute the job.
     */
    public function handle(AiEngineService $aiEngine): void
    {
        $penugasan = PenugasanTes::with([
            'percobaan.jawaban.soal.jawabanAcuan',
            'percobaan.jawaban.soal.rubrik',
            'user',
        ])->find($this->penugasanTesId);

        if (!$penugasan || !$penugasan->percobaan) {
            Log::warning("ProcessSmartGrading dibatalkan: PenugasanTes #{$this->penugasanTesId} tidak memiliki percobaan.");
            return;
        }

        $jawabanList = $penugasan->percobaan->jawaban;
        if ($jawabanList->isEmpty()) {
            Log::warning("ProcessSmartGrading dibatalkan: Tidak ada jawaban pada PenugasanTes #{$this->penugasanTesId}.");
            return;
        }

        // Catat atau dapatkan record AiProcessingResult
        $aiResult = AiProcessingResult::updateOrCreate(
            [
                'referensi_type' => PenugasanTes::class,
                'referensi_id'   => $penugasan->id,
                'tipe'           => 'smart_grading',
            ],
            [
                'status'        => 'processing',
                'dispatched_at' => now(),
                'error_message' => null,
            ]
        );

        // Siapkan batch items untuk AI Engine
        $batchItems = [];
        $snapshotSoal = collect($penugasan->paket_snapshot['soal'] ?? [])->keyBy('id');

        foreach ($jawabanList as $jawaban) {
            $soal = $jawaban->soal;
            $soalId = $jawaban->soal_id;

            // Kumpulkan jawaban acuan dari relasi DB atau snapshot
            $refAnswers = [];
            if ($soal && $soal->jawabanAcuan->isNotEmpty()) {
                $refAnswers = $soal->jawabanAcuan->pluck('jawaban')->filter()->values()->toArray();
            }

            if (empty($refAnswers) && isset($snapshotSoal[$soalId])) {
                $snapshotAcuan = $snapshotSoal[$soalId]['jawaban_acuan'] ?? [];
                $refAnswers = collect($snapshotAcuan)->pluck('jawaban')->filter()->values()->toArray();
            }

            // Jika tidak ada jawaban acuan sama sekali, gunakan fallback pesan agar AI tidak gagal validasi
            if (empty($refAnswers)) {
                $refAnswers = ['Jawaban acuan tidak ditentukan.'];
            }

            $maxScore = $soal ? $soal->skor_maksimum : ($snapshotSoal[$soalId]['skor_maksimum'] ?? 10);

            $batchItems[] = [
                'request_id'        => (string) Str::uuid(),
                'candidate_id'      => (string) $penugasan->user_id,
                'attempt_id'        => (string) $penugasan->percobaan->id,
                'answer_id'         => (string) $jawaban->id,
                'question_id'       => (string) $soalId,
                'candidate_answer'  => (string) ($jawaban->jawaban ?? ''),
                'reference_answers' => $refAnswers,
                'max_score'         => (float) $maxScore,
            ];
        }

        $aiResult->update(['payload_input' => ['items_count' => count($batchItems), 'items' => $batchItems]]);

        // Panggil endpoint Batch Smart Grading
        $response = $aiEngine->smartGradingBatch($batchItems);

        if (!$response || !isset($response['data'])) {
            $errorMessage = 'Gagal memanggil AI Engine Smart Grading (respons kosong atau error).';
            $aiResult->update([
                'status'        => 'failed',
                'error_message' => $errorMessage,
                'completed_at'  => now(),
            ]);
            Log::error("Smart Grading gagal untuk PenugasanTes #{$penugasan->id}: {$errorMessage}");
            return;
        }

        $results = $response['data'];
        $resultsByAnswerId = collect($results)->keyBy('answer_id');

        DB::beginTransaction();
        try {
            // Ambil atau buat draft Penilaian
            $penilaian = Penilaian::firstOrNew(['penugasan_tes_id' => $penugasan->id]);

            // Jika belum ada penilai manusia, kita tandai bahwa skor awal ini dari AI
            $isNew = !$penilaian->exists;
            if ($isNew) {
                $penilaian->penilai_id = null;
                $penilaian->catatan_umum = "Dinilai otomatis oleh Smart Grading AI (S-BERT). Menunggu peninjauan/verifikasi HR.";
            }

            $penilaian->save();

            $totalObtained = 0;
            $totalMax = 0;

            foreach ($jawabanList as $jawaban) {
                $aiItem = $resultsByAnswerId->get((string) $jawaban->id);
                $predScore = $aiItem['predicted_score'] ?? 0;
                $maxScore = $aiItem['max_score'] ?? ($jawaban->soal->skor_maksimum ?? 10);
                $cosine = $aiItem['cosine_similarity'] ?? null;
                $similarityText = $cosine !== null ? ' (Similarity: ' . round($cosine * 100, 1) . '%)' : '';

                $totalObtained += $predScore;
                $totalMax += $maxScore;

                // Update or create PenilaianDetail
                PenilaianDetail::updateOrCreate(
                    [
                        'penilaian_id'        => $penilaian->id,
                        'jawaban_kandidat_id' => $jawaban->id,
                    ],
                    [
                        'kriteria'       => 'Smart Grading (S-BERT)',
                        'skor_maksimum'  => $maxScore,
                        'skor'           => $predScore,
                        'catatan'        => 'Prediksi otomatis AI' . $similarityText . '. ' . ($aiItem['reason'] ?? ''),
                    ]
                );
            }

            $penilaian->total_skor_diperoleh = $totalObtained;
            $penilaian->total_skor_maksimum  = $totalMax;
            $penilaian->nilai_akhir          = $totalMax > 0 ? round(($totalObtained / $totalMax) * 100, 2) : 0;
            $penilaian->waktu_penilaian      = now();
            $penilaian->save();

            $penugasan->update(['status_penilaian' => 'sudah_dinilai']);

            $aiResult->update([
                'status'            => 'completed',
                'payload_output'    => $response,
                'score'             => $penilaian->nilai_akhir,
                'model_version'     => $results[0]['model_version'] ?? 'sbert-murialo',
                'execution_time_ms' => collect($results)->sum('processing_time_ms'),
                'completed_at'      => now(),
            ]);

            DB::commit();
            Log::info("Smart Grading selesai untuk PenugasanTes #{$penugasan->id} dengan skor {$penilaian->nilai_akhir}");
        } catch (\Exception $e) {
            DB::rollBack();
            $aiResult->update([
                'status'        => 'failed',
                'error_message' => $e->getMessage(),
                'completed_at'  => now(),
            ]);
            Log::error("Error saat menyimpan hasil Smart Grading: " . $e->getMessage());
            throw $e;
        }
    }
}
