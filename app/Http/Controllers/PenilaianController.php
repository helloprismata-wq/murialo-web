<?php

namespace App\Http\Controllers;

use App\Models\PenugasanTes;
use App\Services\PenilaianService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PenilaianController extends Controller
{
    public function __construct(
        protected PenilaianService $penilaianService
    ) {}

    public function index(Request $request): View
    {
        $q = $request->query('q');
        $statusPenilaian = $request->query('status_penilaian');
        $isPublished = $request->query('is_published');

        $hasilList = PenugasanTes::with(['lamaran.lowongan', 'kandidat', 'paketTes', 'percobaan', 'penilaian.penilai'])
            ->whereIn('status_pengerjaan', ['selesai'])
            ->when($q, function ($query, $search) {
                $query->whereHas('kandidat', function ($sub) use ($search) {
                    $sub->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                })->orWhereHas('lamaran.lowongan', function ($sub) use ($search) {
                    $sub->where('judul', 'like', "%{$search}%");
                });
            })
            ->when($statusPenilaian, fn ($query, $st) => $query->where('status_penilaian', $st))
            ->when($isPublished !== null && $isPublished !== '', fn ($query) => $query->where('is_published', (bool) $isPublished))
            ->latest('id')
            ->paginate(10)
            ->withQueryString();

        return view('penilaian.index', compact('hasilList'));
    }

    public function show(PenugasanTes $penugasan): View
    {
        $penugasan->load([
            'lamaran.lowongan',
            'kandidat',
            'paketTes',
            'percobaan.jawaban',
            'penilaian.detail',
            'penilaian.penilai',
            'penilaian.riwayat.user',
        ]);

        return view('penilaian.show', compact('penugasan'));
    }

    public function edit(PenugasanTes $penugasan): View
    {
        $penugasan->load([
            'lamaran.lowongan',
            'kandidat',
            'paketTes',
            'percobaan.jawaban',
            'penilaian.detail',
            'penilaian.penilai',
            'penilaian.riwayat.user',
        ]);

        return view('penilaian.form', compact('penugasan'));
    }

    public function update(Request $request, PenugasanTes $penugasan): RedirectResponse
    {
        $scores = $request->input('scores', []);
        $catatanUmum = $request->input('catatan_umum');
        $isComplete = $request->input('action') === 'complete';
        $alasanPerubahan = $request->input('alasan_perubahan');

        $this->penilaianService->savePenilaian(
            $penugasan,
            $request->user(),
            $scores,
            $catatanUmum,
            $isComplete,
            $alasanPerubahan
        );

        $msg = $isComplete
            ? 'Penilaian berhasil diselesaikan dan status diperbarui menjadi Selesai Dinilai.'
            : 'Penilaian berhasil disimpan sebagai draf.';

        return redirect()->route('penilaian.show', $penugasan)->with('success', $msg);
    }

    public function togglePublish(Request $request, PenugasanTes $penugasan): RedirectResponse
    {
        $publish = (bool) $request->input('publish', false);

        $this->penilaianService->setPublishStatus($penugasan, $publish);

        $msg = $publish
            ? 'Hasil tes berhasil dipublikasikan. Kandidat sekarang dapat melihat nilai akhir.'
            : 'Publikasi hasil tes telah ditarik kembali.';

        return redirect()->route('penilaian.show', $penugasan)->with('success', $msg);
    }
}
