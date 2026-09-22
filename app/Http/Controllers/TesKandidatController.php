<?php

namespace App\Http\Controllers;

use App\Models\PenugasanTes;
use App\Models\PercobaanTes;
use App\Services\TesKandidatService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TesKandidatController extends Controller
{
    public function __construct(
        protected TesKandidatService $tesService
    ) {}

    /**
     * Daftar tes milik kandidat (Tes Saya).
     */
    public function index(Request $request): View
    {
        $kandidat = $request->user();

        $daftarTes = PenugasanTes::with(['lamaran.lowongan', 'paketTes', 'percobaan', 'penilaian'])
            ->where('user_id', $kandidat->id)
            ->latest('id')
            ->paginate(10);

        return view('kandidat.index', compact('daftarTes'));
    }

    /**
     * Detail tes dan petunjuk pengerjaan sebelum memulai.
     */
    public function show(Request $request, PenugasanTes $penugasan): View
    {
        if ($penugasan->user_id !== $request->user()->id) {
            abort(403, 'Akses ditolak.');
        }

        $penugasan->load(['lamaran.lowongan', 'percobaan', 'penilaian']);

        return view('kandidat.show', compact('penugasan'));
    }

    /**
     * Memulai atau melanjutkan pengerjaan tes.
     */
    public function start(Request $request, PenugasanTes $penugasan): RedirectResponse
    {
        if ($penugasan->user_id !== $request->user()->id) {
            abort(403, 'Akses ditolak.');
        }

        $percobaan = $this->tesService->startTes(
            $penugasan,
            $request->user(),
            $request->ip(),
            $request->userAgent()
        );

        return redirect()->route('kandidat.kerjakan', $penugasan);
    }

    /**
     * Halaman pengerjaan tes.
     * PENTING: Jangan sertakan jawaban acuan dan rubrik ke view kandidat!
     */
    public function kerjakan(Request $request, PenugasanTes $penugasan): View|RedirectResponse
    {
        if ($penugasan->user_id !== $request->user()->id) {
            abort(403, 'Akses ditolak.');
        }

        $percobaan = $penugasan->percobaan;

        if (!$percobaan) {
            return redirect()->route('kandidat.show', $penugasan)->with('error', 'Silakan klik Mulai Tes terlebih dahulu.');
        }

        if (in_array($percobaan->status, ['diserahkan', 'waktu_habis'])) {
            return redirect()->route('kandidat.show', $penugasan)->with('notice', 'Tes ini sudah diselesaikan.');
        }

        // Check if server time has exceeded end time
        if ($percobaan->isTimeUp()) {
            $this->tesService->finalizeAttempt($percobaan, 'waktu_habis');
            return redirect()->route('kandidat.show', $penugasan)->with('error', 'Waktu pengerjaan tes telah berakhir.');
        }

        $percobaan->load('jawaban');

        // Extract ONLY questions, reading text, order without rubrik or answer keys
        $soalList = $penugasan->candidate_questions;

        // Map answers
        $jawabanMap = $percobaan->jawaban->pluck('jawaban', 'soal_id')->toArray();

        $sisaDetik = $percobaan->sisaDetik();
        $waktuBerakhirIso = $percobaan->waktu_berakhir?->toIso8601String();

        return view('kandidat.kerjakan', compact(
            'penugasan',
            'percobaan',
            'soalList',
            'jawabanMap',
            'sisaDetik',
            'waktuBerakhirIso'
        ));
    }

    /**
     * AJAX autosave endpoint.
     */
    public function autosave(Request $request, PenugasanTes $penugasan): JsonResponse
    {
        if ($penugasan->user_id !== $request->user()->id) {
            return response()->json(['success' => false, 'message' => 'Akses ditolak.'], 403);
        }

        $percobaan = $penugasan->percobaan;
        if (!$percobaan) {
            return response()->json(['success' => false, 'message' => 'Percobaan belum dimulai.'], 400);
        }

        $request->validate([
            'soal_id' => 'required|integer',
            'jawaban' => 'nullable|string',
        ]);

        try {
            $jawabanRecord = $this->tesService->autosaveAnswer(
                $percobaan,
                (int) $request->input('soal_id'),
                $request->input('jawaban')
            );

            return response()->json([
                'success' => true,
                'message' => 'Tersimpan otomatis',
                'saved_at' => $jawabanRecord->terakhir_disimpan_pada?->format('H:i:s'),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 422);
        }
    }

    /**
     * Kirim & finalisasi jawaban tes oleh kandidat.
     */
    public function submit(Request $request, PenugasanTes $penugasan): RedirectResponse
    {
        if ($penugasan->user_id !== $request->user()->id) {
            abort(403, 'Akses ditolak.');
        }

        $percobaan = $penugasan->percobaan;
        if (!$percobaan) {
            return redirect()->route('kandidat.show', $penugasan);
        }

        $jawabanData = $request->input('jawaban', []);

        $this->tesService->submitTes($percobaan, $jawabanData);

        return redirect()->route('kandidat.show', $penugasan)->with('success', 'Jawaban Anda telah berhasil dikirim. Terima kasih.');
    }
}
