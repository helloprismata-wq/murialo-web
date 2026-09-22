<?php

namespace App\Services;

use App\Models\JawabanKandidat;
use App\Models\Penilaian;
use App\Models\PenilaianDetail;
use App\Models\PenilaianRiwayat;
use App\Models\PenugasanTes;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class PenilaianService
{
    /**
     * Save grading scores (draft or completed).
     *
     * @param PenugasanTes $penugasan
     * @param User $penilai
     * @param array $scores Array of [jawaban_id => [rubrik_id => ['skor' => x, 'catatan' => y]]]
     * @param string|null $catatanUmum
     * @param bool $isComplete Whether to mark as 'selesai_dinilai'
     * @param string|null $alasanPerubahan Required if modifying an already completed assessment
     */
    public function savePenilaian(
        PenugasanTes $penugasan,
        User $penilai,
        array $scores,
        ?string $catatanUmum = null,
        bool $isComplete = false,
        ?string $alasanPerubahan = null
    ): Penilaian {
        if ($penugasan->status_pengerjaan !== 'selesai') {
            throw ValidationException::withMessages([
                'penilaian' => 'Penilaian hanya dapat dilakukan pada tes yang sudah berstatus selesai.',
            ]);
        }

        return DB::transaction(function () use ($penugasan, $penilai, $scores, $catatanUmum, $isComplete, $alasanPerubahan) {
            $isEditingCompleted = ($penugasan->status_penilaian === 'selesai_dinilai');

            if ($isEditingCompleted && empty(trim($alasanPerubahan ?? ''))) {
                throw ValidationException::withMessages([
                    'alasan_perubahan' => 'Wajib mengisi alasan perubahan untuk memperbarui penilaian yang sudah selesai dinilai.',
                ]);
            }

            // Find or create Penilaian record
            $penilaian = Penilaian::firstOrCreate(
                ['penugasan_tes_id' => $penugasan->id],
                [
                    'penilai_id' => $penilai->id,
                    'total_skor_diperoleh' => 0,
                    'total_skor_maksimum' => 0,
                    'nilai_akhir' => 0,
                    'catatan_umum' => $catatanUmum,
                ]
            );

            $oldSkor = (float) $penilaian->total_skor_diperoleh;

            // Extract snapshot data for rubric max scores
            $soalSnapshotList = collect($penugasan->paket_snapshot['soal'] ?? []);

            $totalObtained = 0;
            $totalMax = 0;
            $allCriteriaGraded = true;

            // Iterate over all answers of the attempt
            $jawabanList = $penugasan->percobaan ? $penugasan->percobaan->jawaban : collect();

            foreach ($jawabanList as $jawaban) {
                // Find matching soal in snapshot
                $soalSnap = $soalSnapshotList->firstWhere('id', $jawaban->soal_id);
                $rubrikList = $soalSnap['rubrik'] ?? [];

                foreach ($rubrikList as $rubrikItem) {
                    $rubrikId = $rubrikItem['id'];
                    $kriteria = $rubrikItem['kriteria'];
                    $maxPoin = (float) $rubrikItem['poin_maksimum'];

                    $input = $scores[$jawaban->id][$rubrikId] ?? null;
                    $scoreVal = isset($input['skor']) && $input['skor'] !== '' ? (float) $input['skor'] : null;
                    $catatan = $input['catatan'] ?? null;

                    if ($scoreVal === null) {
                        $allCriteriaGraded = false;
                        $scoreVal = 0;
                    } else {
                        // Validate score boundary
                        if ($scoreVal < 0) {
                            throw ValidationException::withMessages([
                                "scores.{$jawaban->id}.{$rubrikId}.skor" => "Skor kriteria '{$kriteria}' tidak boleh bernilai negatif.",
                            ]);
                        }
                        if ($scoreVal > $maxPoin) {
                            throw ValidationException::withMessages([
                                "scores.{$jawaban->id}.{$rubrikId}.skor" => "Skor kriteria '{$kriteria}' ({$scoreVal}) melebihi batas maksimum ({$maxPoin}).",
                            ]);
                        }
                    }

                    $totalObtained += $scoreVal;
                    $totalMax += $maxPoin;

                    PenilaianDetail::updateOrCreate(
                        [
                            'penilaian_id' => $penilaian->id,
                            'jawaban_kandidat_id' => $jawaban->id,
                            'soal_rubrik_id' => $rubrikId,
                        ],
                        [
                            'kriteria' => $kriteria,
                            'skor_maksimum' => $maxPoin,
                            'skor' => $scoreVal,
                            'catatan' => $catatan,
                        ]
                    );
                }
            }

            if ($isComplete && !$allCriteriaGraded) {
                throw ValidationException::withMessages([
                    'is_complete' => 'Semua kriteria rubrik pada setiap soal harus dinilai sebelum penilaian diselesaikan.',
                ]);
            }

            $nilaiAkhir = $totalMax > 0 ? round(($totalObtained / $totalMax) * 100, 2) : 0;

            $penilaian->update([
                'penilai_id' => $penilai->id,
                'total_skor_diperoleh' => $totalObtained,
                'total_skor_maksimum' => $totalMax,
                'nilai_akhir' => $nilaiAkhir,
                'catatan_umum' => $catatanUmum,
                'waktu_penilaian' => now(),
            ]);

            // If modifying an already completed assessment, record the revision history
            if ($isEditingCompleted) {
                PenilaianRiwayat::create([
                    'penilaian_id' => $penilaian->id,
                    'user_id' => $penilai->id,
                    'alasan_perubahan' => $alasanPerubahan,
                    'skor_sebelumnya' => $oldSkor,
                    'skor_baru' => $totalObtained,
                    'snapshot_perubahan' => [
                        'scores' => $scores,
                        'catatan_umum' => $catatanUmum,
                        'nilai_akhir' => $nilaiAkhir,
                    ],
                ]);
            }

            $statusPenilaian = $isComplete ? 'selesai_dinilai' : 'sedang_dinilai';
            $penugasan->update(['status_penilaian' => $statusPenilaian]);

            return $penilaian;
        });
    }

    /**
     * Publish or unpublish test results.
     */
    public function setPublishStatus(PenugasanTes $penugasan, bool $publish): void
    {
        if ($publish && $penugasan->status_penilaian !== 'selesai_dinilai') {
            throw ValidationException::withMessages([
                'publish' => 'Hasil tes hanya dapat dipublikasikan setelah status penilaian selesai dinilai.',
            ]);
        }

        $penugasan->update([
            'is_published' => $publish,
            'published_at' => $publish ? now() : null,
        ]);
    }
}
