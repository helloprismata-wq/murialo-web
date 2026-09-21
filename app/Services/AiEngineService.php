<?php

namespace App\Services;

use App\Models\Pelamar;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AiEngineService
{
    protected string $baseUrl;

    public function __construct()
    {
        $this->baseUrl = rtrim(config('services.ai_engine.url', 'http://127.0.0.1:8001'), '/');
    }

    /**
     * Cek apakah server FastAPI sedang online
     */
    public function isHealthy(): bool
    {
        try {
            $response = Http::timeout(2)->get("{$this->baseUrl}/health");
            return $response->successful() && $response->json('status') === 'ok';
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Kirim file CV ke AI Engine untuk diparse
     */
    public function parseCv(Pelamar $pelamar, ?UploadedFile $file = null): ?array
    {
        try {
            // Tentukan sumber file (file upload baru atau file dari storage)
            if ($file) {
                $fileContent = file_get_contents($file->getRealPath());
                $fileName    = $file->getClientOriginalName();
            } else {
                $path = storage_path('app/public/' . $pelamar->file_cv);
                if (!file_exists($path)) return null;
                $fileContent = file_get_contents($path);
                $fileName    = basename($path);
            }

            // Kirim ke endpoint FastAPI via multipart/form-data
            $response = Http::timeout(60)
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
            Log::error('Koneksi ke AI Engine gagal: ' . $e->getMessage());
            return null;
        }
    }
}
