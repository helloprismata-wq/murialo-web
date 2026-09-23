<?php

namespace App\Services;

use App\Models\Pelamar;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class AiEngineService
{
    protected string $baseUrl;
    protected ?string $apiKey;
    protected int $timeout;

    public function __construct()
    {
        $this->baseUrl = rtrim(config('services.ai_engine.url', 'http://127.0.0.1:8001'), '/');
        $this->apiKey  = config('services.ai_engine.key') ?: null;
        $this->timeout = (int) config('services.ai_engine.timeout', 30);
    }

    /**
     * Membuat HTTP client dengan header standar
     */
    protected function client()
    {
        $headers = [
            'Accept'       => 'application/json',
            'X-Request-ID' => (string) Str::uuid(),
        ];

        if ($this->apiKey) {
            $headers['X-API-Key'] = $this->apiKey;
        }

        return Http::timeout($this->timeout)->withHeaders($headers);
    }

    /**
     * Cek apakah server FastAPI sedang online
     */
    public function isHealthy(): bool
    {
        try {
            $response = Http::timeout(3)->get("{$this->baseUrl}/health");
            return $response->successful() && $response->json('status') === 'ok';
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Ambil status kesehatan mendalam (termasuk status model yang dimuat)
     */
    public function getDetailedHealth(): ?array
    {
        try {
            $response = $this->client()->get("{$this->baseUrl}/api/v1/health/detailed");
            if ($response->successful()) {
                return $response->json();
            }
            return null;
        } catch (\Exception $e) {
            Log::warning('Gagal mendapatkan detailed health AI Engine: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Kirim permintaan Smart Grading tunggal ke AI Engine
     *
     * @param array $payload [candidate_answer, reference_answers, max_score, ...]
     * @return array|null
     */
    public function smartGrading(array $payload): ?array
    {
        try {
            $response = $this->client()->post("{$this->baseUrl}/api/v1/smart-grading", $payload);

            if ($response->successful()) {
                return $response->json('data');
            }

            Log::error('Smart Grading AI Engine error: ' . $response->status() . ' - ' . $response->body());
            return null;
        } catch (\Exception $e) {
            Log::error('Koneksi Smart Grading ke AI Engine gagal: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Kirim permintaan Smart Grading dalam format batch
     *
     * @param array $items Array of grading payload items
     * @return array|null
     */
    public function smartGradingBatch(array $items): ?array
    {
        try {
            $response = $this->client()->post("{$this->baseUrl}/api/v1/smart-grading/batch", [
                'items' => $items,
            ]);

            if ($response->successful()) {
                return $response->json();
            }

            Log::error('Smart Grading Batch AI Engine error: ' . $response->status() . ' - ' . $response->body());
            return null;
        } catch (\Exception $e) {
            Log::error('Koneksi Smart Grading Batch ke AI Engine gagal: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Kirim permintaan Skill Matching ke AI Engine
     *
     * @param array $payload [resume_text, job_description, candidate_skills, required_skills, ...]
     * @return array|null
     */
    public function skillMatching(array $payload): ?array
    {
        try {
            $response = $this->client()->post("{$this->baseUrl}/api/v1/skill-matching", $payload);

            if ($response->successful()) {
                return $response->json('data');
            }

            Log::error('Skill Matching AI Engine error: ' . $response->status() . ' - ' . $response->body());
            return null;
        } catch (\Exception $e) {
            Log::error('Koneksi Skill Matching ke AI Engine gagal: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Kirim file CV ke AI Engine untuk diparse (Modul Danul)
     */
    public function parseCv(Pelamar $pelamar, ?UploadedFile $file = null): ?array
    {
        try {
            if ($file) {
                $fileContent = file_get_contents($file->getRealPath());
                $fileName    = $file->getClientOriginalName();
            } else {
                $path = storage_path('app/public/' . $pelamar->file_cv);
                if (!file_exists($path)) return null;
                $fileContent = file_get_contents($path);
                $fileName    = basename($path);
            }

            $response = $this->client()
                ->timeout(60)
                ->attach('file', $fileContent, $fileName)
                ->post("{$this->baseUrl}/resume-parser/parse", [
                    'pelamar_id' => $pelamar->id,
                    'cv_id'      => $pelamar->id,
                ]);

            if ($response->successful()) {
                return $response->json('data');
            }

            Log::error('Gagal memproses CV via AI Engine: ' . $response->body());
            return null;
        } catch (\Exception $e) {
            Log::error('Koneksi CV parser ke AI Engine gagal: ' . $e->getMessage());
            return null;
        }
    }
}
