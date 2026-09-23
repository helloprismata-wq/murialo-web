<?php

namespace App\Http\Controllers;

use App\Jobs\ProcessSkillMatching;
use App\Models\AiProcessingResult;
use App\Models\Lamaran;
use App\Models\Lowongan;
use App\Services\AiEngineService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SkillMatchingController extends Controller
{
    public function __construct(
        protected AiEngineService $aiEngine
    ) {}

    /**
     * Tampilan dashboard Skill Matching untuk suatu lowongan
     */
    public function index(Request $request, Lowongan $lowongan): View
    {
        $lamaranList = Lamaran::with(['kandidat'])
            ->where('lowongan_id', $lowongan->id)
            ->latest('id')
            ->get();

        // Ambil hasil AI matching untuk setiap lamaran
        $lamaranIds = $lamaranList->pluck('id');
        $aiResults = AiProcessingResult::where('referensi_type', Lamaran::class)
            ->whereIn('referensi_id', $lamaranIds)
            ->where('tipe', 'skill_matching')
            ->get()
            ->keyBy('referensi_id');

        $isAiOnline = $this->aiEngine->isHealthy();

        return view('lowongan.matching', compact('lowongan', 'lamaranList', 'aiResults', 'isAiOnline'));
    }

    /**
     * Jalankan skill matching untuk suatu lamaran atau input sandbox
     */
    public function match(Request $request, Lowongan $lowongan): RedirectResponse
    {
        $lamaranId = $request->input('lamaran_id');
        $cvText    = $request->input('cv_text');

        if ($lamaranId) {
            try {
                ProcessSkillMatching::dispatchSync((int) $lamaranId, $cvText);
                return redirect()->route('lowongan.matching', $lowongan)
                    ->with('success', 'Skill Matching AI berhasil diproses.');
            } catch (\Exception $e) {
                return redirect()->route('lowongan.matching', $lowongan)
                    ->with('error', 'Gagal memproses Skill Matching: ' . $e->getMessage());
            }
        }

        // Jika testing sandbox langsung
        if (!empty($cvText)) {
            $requiredSkills = [];
            if (!empty($lowongan->skills)) {
                $requiredSkills = array_map('trim', explode(',', $lowongan->skills));
            }

            $payload = [
                'request_id'      => (string) \Illuminate\Support\Str::uuid(),
                'candidate_id'    => 'sandbox-test',
                'job_id'          => (string) $lowongan->id,
                'resume_text'     => $cvText,
                'job_description' => trim("{$lowongan->judul}\n\n{$lowongan->deskripsi}\n\n{$lowongan->persyaratan}"),
                'required_skills' => array_values(array_filter($requiredSkills)),
            ];

            $result = $this->aiEngine->skillMatching($payload);

            if ($result) {
                return redirect()->route('lowongan.matching', $lowongan)
                    ->with('sandbox_result', $result)
                    ->with('sandbox_cv', $cvText)
                    ->with('success', 'Pengujian Skill Matching S-BERT berhasil dijalankan.');
            } else {
                return redirect()->route('lowongan.matching', $lowongan)
                    ->with('error', 'AI Engine tidak merespons atau sedang offline.');
            }
        }

        return redirect()->route('lowongan.matching', $lowongan)
            ->with('error', 'Pilih lamaran atau masukkan teks CV untuk diuji.');
    }

    /**
     * API JSON endpoint untuk pengujian langsung tanpa form reload
     */
    public function apiMatch(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'resume_text'      => 'required|string',
            'job_description'  => 'required|string',
            'required_skills'  => 'nullable|array',
            'candidate_skills' => 'nullable|array',
        ]);

        $payload = [
            'request_id'       => (string) \Illuminate\Support\Str::uuid(),
            'candidate_id'     => 'api-test',
            'job_id'           => 'api-test',
            'resume_text'      => $validated['resume_text'],
            'job_description'  => $validated['job_description'],
            'required_skills'  => $validated['required_skills'] ?? [],
            'candidate_skills' => $validated['candidate_skills'] ?? [],
        ];

        $result = $this->aiEngine->skillMatching($payload);

        if ($result) {
            return response()->json(['status' => 'success', 'data' => $result]);
        }

        return response()->json(['status' => 'error', 'message' => 'AI Engine request failed'], 503);
    }
}
