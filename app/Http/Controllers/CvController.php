<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCvRequest;
use App\Http\Requests\UpdateCvRequest;
use App\Models\HasilParsing;
use App\Models\Pelamar;
use App\Services\AiEngineService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class CvController extends Controller
{
    protected AiEngineService $aiEngine;

    public function __construct(AiEngineService $aiEngine)
    {
        $this->aiEngine = $aiEngine;
    }

    /**
     * Daftar semua CV yang masuk (halaman HRD).
     */
    public function index(): View
    {
        $pelamars = Pelamar::with('hasilParsing')->latest()->paginate(10);
        $aiOnline = $this->aiEngine->isHealthy();

        return view('cv.index', compact('pelamars', 'aiOnline'));
    }

    /**
     * Tampilkan form upload CV (halaman publik untuk pelamar).
     */
    public function create(): View
    {
        return view('cv.create');
    }

    /**
     * Simpan data pelamar, file CV ke storage, dan kirim ke AI Engine.
     */
    public function store(StoreCvRequest $request)
    {
        $validated = $request->validated();
        $uploadedFile = $request->file('file_cv');

        // 1. Upload file CV ke storage/app/public/cv/
        $filePath = $uploadedFile->store('cv', 'public');

        // 2. Simpan ke database pelamars
        $pelamar = Pelamar::create([
            'nama'           => $validated['nama'],
            'email'          => $validated['email'],
            'nomor_hp'       => $validated['nomor_hp'],
            'posisi_dilamar' => $validated['posisi_dilamar'],
            'file_cv'        => $filePath,
        ]);

        // 3. Proses otomatis via AI Engine FastAPI (Resume Parser)
        $aiResult = $this->aiEngine->parseCv($pelamar, $uploadedFile);

        // Jika request menginginkan respon JSON (API / AJAX)
        if ($request->wantsJson()) {
            return response()->json([
                'status'  => 'success',
                'message' => 'CV berhasil diunggah' . ($aiResult ? ' dan diproses oleh AI Engine' : ''),
                'data'    => [
                    'pelamar'       => $pelamar,
                    'hasil_parsing' => $aiResult,
                ],
            ], 201);
        }

        $flashMessage = $aiResult
            ? 'CV Anda berhasil dikirim dan dianalisis oleh AI Engine! Keahlian Anda telah terdata.'
            : 'CV Anda berhasil dikirim! Kami akan menghubungi Anda segera.';

        return redirect()
            ->route('cv.create')
            ->with('success', $flashMessage);
    }

    /**
     * Tampilkan form edit data pelamar (halaman HRD).
     */
    public function edit(Pelamar $pelamar): View
    {
        $pelamar->load('hasilParsing');

        return view('cv.edit', compact('pelamar'));
    }

    /**
     * Update data pelamar. File CV opsional (jika diubah, parse ulang via AI).
     */
    public function update(UpdateCvRequest $request, Pelamar $pelamar): RedirectResponse
    {
        $validated = $request->validated();

        $data = [
            'nama'           => $validated['nama'],
            'email'          => $validated['email'],
            'nomor_hp'       => $validated['nomor_hp'],
            'posisi_dilamar' => $validated['posisi_dilamar'],
        ];

        // Ganti file CV jika ada file baru yang diunggah
        if ($request->hasFile('file_cv')) {
            // Hapus file lama dari storage
            Storage::disk('public')->delete($pelamar->file_cv);

            // Upload file baru
            $newFile = $request->file('file_cv');
            $data['file_cv'] = $newFile->store('cv', 'public');

            $pelamar->update($data);

            // Parse ulang file CV baru di AI Engine
            $this->aiEngine->parseCv($pelamar, $newFile);
        } else {
            $pelamar->update($data);
        }

        return redirect()
            ->route('cv.index')
            ->with('success', "Data pelamar <strong>{$pelamar->nama}</strong> berhasil diperbarui.");
    }

    /**
     * Hapus data pelamar beserta file CV dan riwayat hasil parsing AI.
     */
    public function destroy(Pelamar $pelamar): RedirectResponse
    {
        $nama = $pelamar->nama;

        // Hapus file dari storage
        Storage::disk('public')->delete($pelamar->file_cv);

        // Hapus hasil parsing terkait jika ada
        HasilParsing::where('pelamar_id', $pelamar->id)->delete();

        // Hapus record dari database
        $pelamar->delete();

        return redirect()
            ->route('cv.index')
            ->with('success', "Data pelamar <strong>{$nama}</strong> berhasil dihapus.");
    }

    /**
     * Download file CV pelamar.
     */
    public function download(Pelamar $pelamar)
    {
        $filePath = Storage::disk('public')->path($pelamar->file_cv);

        if (! Storage::disk('public')->exists($pelamar->file_cv)) {
            return redirect()
                ->route('cv.index')
                ->with('error', 'File CV tidak ditemukan di server.');
        }

        $extension = pathinfo($pelamar->file_cv, PATHINFO_EXTENSION) ?: 'pdf';
        $namaFile = 'CV_' . str_replace(' ', '_', $pelamar->nama) . '.' . $extension;

        return response()->download($filePath, $namaFile);
    }

    /**
     * Jalankan ulang parsing AI Engine untuk pelamar tertentu (fitur HRD).
     */
    public function reparse(Pelamar $pelamar): RedirectResponse
    {
        $result = $this->aiEngine->parseCv($pelamar);

        if ($result) {
            return redirect()
                ->route('cv.index')
                ->with('success', "AI Engine berhasil mem-parse ulang CV <strong>{$pelamar->nama}</strong>.");
        }

        return redirect()
            ->route('cv.index')
            ->with('error', "Gagal memproses ulang CV <strong>{$pelamar->nama}</strong>. Pastikan AI Engine aktif di port 8001.");
    }

    /**
     * Ambil data detail hasil parsing AI dalam format JSON (untuk modal detail HRD).
     */
    public function aiDetail(Pelamar $pelamar): JsonResponse
    {
        $pelamar->load('hasilParsing');

        return response()->json([
            'pelamar'       => $pelamar,
            'hasil_parsing' => $pelamar->hasilParsing,
            'skills'        => $pelamar->hasilParsing?->skill_list ?? [],
        ]);
    }
}
