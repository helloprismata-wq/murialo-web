<?php

namespace App\Http\Controllers;

use App\Http\Requests\PaketTesRequest;
use App\Models\Lowongan;
use App\Models\PaketTes;
use App\Models\Soal;
use App\Services\PaketTesService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PaketTesController extends Controller
{
    public function __construct(
        protected PaketTesService $paketService
    ) {}

    public function index(Request $request): View
    {
        $q = $request->query('q');
        $status = $request->query('status');

        $paketList = PaketTes::query()
            ->when($q, function ($query, $search) {
                $query->where('nama', 'like', "%{$search}%")
                    ->orWhere('deskripsi', 'like', "%{$search}%");
            })
            ->when($status, fn ($query, $st) => $query->where('status', $st))
            ->withCount('soal')
            ->latest('id')
            ->paginate(10)
            ->withQueryString();

        return view('paket.index', compact('paketList'));
    }

    public function create(): View
    {
        $availableSoal = Soal::orderBy('kategori')->orderBy('kode_soal')->get();
        $availableLowongan = Lowongan::latest('id')->get();

        return view('paket.form', [
            'paket' => new PaketTes(['status' => 'draft', 'durasi_menit' => 30]),
            'availableSoal' => $availableSoal,
            'availableLowongan' => $availableLowongan,
            'selectedSoalIds' => [],
            'selectedLowonganIds' => [],
        ]);
    }

    public function store(PaketTesRequest $request): RedirectResponse
    {
        $paket = $this->paketService->createPaket($request->user(), $request->validated());

        return redirect()->route('paket.show', $paket)->with('success', 'Paket tes berhasil dibuat.');
    }

    public function show(PaketTes $paket): View
    {
        $paket->load(['user', 'soal.rubrik', 'lowongan', 'penugasanTes']);
        return view('paket.show', compact('paket'));
    }

    public function edit(PaketTes $paket): View
    {
        $availableSoal = Soal::orderBy('kategori')->orderBy('kode_soal')->get();
        $availableLowongan = Lowongan::latest('id')->get();
        $selectedSoalIds = $paket->soal()->pluck('soal.id')->toArray();
        $selectedLowonganIds = $paket->lowongan()->pluck('lowongan.id')->toArray();

        return view('paket.form', [
            'paket' => $paket,
            'availableSoal' => $availableSoal,
            'availableLowongan' => $availableLowongan,
            'selectedSoalIds' => $selectedSoalIds,
            'selectedLowonganIds' => $selectedLowonganIds,
        ]);
    }

    public function update(PaketTesRequest $request, PaketTes $paket): RedirectResponse
    {
        $this->paketService->updatePaket($paket, $request->validated());

        return redirect()->route('paket.show', $paket)->with('success', 'Paket tes berhasil diperbarui.');
    }

    public function destroy(PaketTes $paket): RedirectResponse
    {
        if ($paket->penugasanTes()->count() > 0) {
            $paket->update(['status' => 'arsip']);
            $paket->delete();
            return redirect()->route('paket.index')->with('success', 'Paket tes telah diarsipkan karena sudah memiliki riwayat penugasan.');
        }

        $paket->delete();
        return redirect()->route('paket.index')->with('success', 'Paket tes berhasil dihapus.');
    }
}
