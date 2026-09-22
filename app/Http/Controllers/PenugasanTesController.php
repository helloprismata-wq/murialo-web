<?php

namespace App\Http\Controllers;

use App\Http\Requests\PenugasanTesRequest;
use App\Models\Lamaran;
use App\Models\PaketTes;
use App\Models\PenugasanTes;
use App\Services\PenugasanTesService;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PenugasanTesController extends Controller
{
    public function __construct(
        protected PenugasanTesService $penugasanService
    ) {}

    public function index(Request $request): View
    {
        $q = $request->query('q');
        $statusPengerjaan = $request->query('status_pengerjaan');
        $statusPenilaian = $request->query('status_penilaian');

        $penugasanList = PenugasanTes::with(['lamaran.lowongan', 'kandidat', 'paketTes', 'percobaan'])
            ->when($q, function ($query, $search) {
                $query->whereHas('kandidat', function ($sub) use ($search) {
                    $sub->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                })->orWhereHas('lamaran.lowongan', function ($sub) use ($search) {
                    $sub->where('judul', 'like', "%{$search}%");
                });
            })
            ->when($statusPengerjaan, fn ($query, $st) => $query->where('status_pengerjaan', $st))
            ->when($statusPenilaian, fn ($query, $st) => $query->where('status_penilaian', $st))
            ->latest('id')
            ->paginate(10)
            ->withQueryString();

        return view('penugasan.index', compact('penugasanList'));
    }

    public function create(Request $request): View
    {
        $lamaranList = Lamaran::with(['kandidat', 'lowongan'])->latest('id')->get();
        $paketList = PaketTes::where('status', 'aktif')->withCount('soal')->get();

        $selectedLamaranId = $request->query('lamaran_id');

        return view('penugasan.form', [
            'lamaranList' => $lamaranList,
            'paketList' => $paketList,
            'selectedLamaranId' => $selectedLamaranId,
            'waktuTersedia' => now()->format('Y-m-d\TH:i'),
            'batasWaktu' => now()->addDays(3)->format('Y-m-d\TH:i'),
        ]);
    }

    public function store(PenugasanTesRequest $request): RedirectResponse
    {
        $lamaran = Lamaran::findOrFail($request->input('lamaran_id'));
        $paket = PaketTes::findOrFail($request->input('paket_tes_id'));
        $waktuTersedia = Carbon::parse($request->input('waktu_tersedia'));
        $batasWaktu = Carbon::parse($request->input('batas_waktu'));

        $penugasan = $this->penugasanService->assignPaket($lamaran, $paket, $waktuTersedia, $batasWaktu);

        return redirect()->route('penugasan.index')->with('success', "Paket tes '{$paket->nama}' berhasil ditugaskan kepada kandidat {$lamaran->kandidat->name}.");
    }

    public function show(PenugasanTes $penugasan): View
    {
        $penugasan->load(['lamaran.lowongan', 'kandidat', 'paketTes', 'percobaan.jawaban', 'penilaian.detail']);
        return view('penugasan.show', compact('penugasan'));
    }
}
