<?php

namespace App\Jobs;

use App\Models\AiProcessingResult;
use App\Models\Lamaran;
use App\Services\AiEngineService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ProcessSkillMatching implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $lamaranId;
    public ?string $cvText;
    public ?array $candidateSkills;
    public int $tries = 3;
    public int $timeout = 60;

    /**
     * Create a new job instance.
     *
     * @param int $lamaranId
     * @param string|null $cvText Input teks CV langsung (sementara sebelum parser Danul siap)
     * @param array|null $candidateSkills List skill kandidat
     */
    public function __construct(int $lamaranId, ?string $cvText = null, ?array $candidateSkills = null)
    {
        $this->lamaranId = $lamaranId;
        $this->cvText = $cvText;
        $this->candidateSkills = $candidateSkills;
    }

    /**
     * Execute the job.
     */
    public function handle(AiEngineService $aiEngine): void
    {
        $lamaran = Lamaran::with(['lowongan', 'kandidat'])->find($this->lamaranId);

        if (!$lamaran || !$lamaran->lowongan) {
            Log::warning("ProcessSkillMatching dibatalkan: Lamaran #{$this->lamaranId} atau lowongan tidak ditemukan.");
            return;
        }

        $lowongan = $lamaran->lowongan;

        // Catat di tabel ai_processing_results
        $aiResult = AiProcessingResult::updateOrCreate(
            [
                'referensi_type' => Lamaran::class,
                'referensi_id'   => $lamaran->id,
                'tipe'           => 'skill_matching',
            ],
            [
                'status'        => 'processing',
                'dispatched_at' => now(),
                'error_message' => null,
            ]
        );

        // Siapkan deskripsi lowongan lengkap
        $jobDesc = trim("{$lowongan->judul}\n\n{$lowongan->deskripsi}\n\n{$lowongan->persyaratan}");

        // Parsing required skills dari lowongan (jika format JSON string, koma, atau array)
        $requiredSkills = [];
        if (!empty($lowongan->skills)) {
            if (is_array($lowongan->skills)) {
                $requiredSkills = $lowongan->skills;
            } elseif (is_string($lowongan->skills)) {
                $decoded = json_decode($lowongan->skills, true);
                if (is_array($decoded)) {
                    $requiredSkills = $decoded;
                } else {
                    $requiredSkills = array_map('trim', explode(',', $lowongan->skills));
                }
            }
        }

        // Teks CV: prioritaskan $this->cvText, fallback ke ringkasan pelamar / dummy test profile
        $resumeText = $this->cvText;
        if (empty($resumeText)) {
            $resumeText = "Kandidat: " . ($lamaran->kandidat->name ?? 'Kandidat') . "\n";
            $resumeText .= "Email: " . ($lamaran->kandidat->email ?? '') . "\n";
            if (!empty($lamaran->catatan)) {
                $resumeText .= "Profil / Catatan: " . $lamaran->catatan . "\n";
            }
        }

        $payload = [
            'request_id'       => (string) Str::uuid(),
            'candidate_id'     => (string) $lamaran->user_id,
            'job_id'           => (string) $lowongan->id,
            'resume_text'      => $resumeText,
            'job_description'  => $jobDesc,
            'candidate_skills' => $this->candidateSkills ?? [],
            'required_skills'  => array_values(array_filter($requiredSkills)),
        ];

        $aiResult->update(['payload_input' => $payload]);

        $result = $aiEngine->skillMatching($payload);

        if (!$result || !isset($result['similarity_score'])) {
            $errorMsg = 'Gagal memanggil AI Engine Skill Matching (respons tidak valid).';
            $aiResult->update([
                'status'        => 'failed',
                'error_message' => $errorMsg,
                'completed_at'  => now(),
            ]);
            Log::error("Skill Matching gagal untuk Lamaran #{$lamaran->id}: {$errorMsg}");
            return;
        }

        $aiResult->update([
            'status'            => 'completed',
            'payload_output'    => $result,
            'score'             => (float) $result['similarity_score'],
            'model_version'     => $result['model_version'] ?? 'sbert-murialo',
            'execution_time_ms' => $result['processing_time_ms'] ?? null,
            'completed_at'      => now(),
        ]);

        Log::info("Skill Matching selesai untuk Lamaran #{$lamaran->id}. Skor: {$result['similarity_score']}%");
    }
}
