<?php

namespace App\Http\Controllers;

use App\Http\Requests\SoalRequest;
use App\Models\Soal;
use App\Services\SoalService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SoalController extends Controller
{
    public function __construct(
        protected SoalService $soalService
    ) {}

    public function index(Request $request): View
    {
        $q = $request->query('q');
        $status = $request->query('status');
        $kategori = $request->query('kategori');

        $soalList = Soal::query()
            ->when($q, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('kode_soal', 'like', "%{$search}%")
                        ->orWhere('judul', 'like', "%{$search}%")
                        ->orWhere('pertanyaan', 'like', "%{$search}%");
                });
            })
            ->when($status, fn ($query, $st) => $query->where('status', $st))
            ->when($kategori, fn ($query, $kat) => $query->where('kategori', $kat))
            ->latest('id')
            ->paginate(10)
            ->withQueryString();

        return view('soal.index', compact('soalList'));
    }

    public function create(): View
    {
        return view('soal.form', [
            'soal' => new Soal(['status' => 'draft', 'skor_maksimum' => 10, 'is_dummy' => true]),
        ]);
    }

    public function store(SoalRequest $request): RedirectResponse
    {
        $soal = $this->soalService->createSoal($request->user(), $request->validated());

        return redirect()->route('soal.show', $soal)->with('success', 'Soal berhasil ditambahkan ke Bank Soal.');
    }

    public function show(Soal $soal): View
    {
        $soal->load(['user', 'jawabanAcuan', 'rubrik', 'snapshots']);
        return view('soal.show', compact('soal'));
    }

    public function edit(Soal $soal): View
    {
        $soal->load(['jawabanAcuan', 'rubrik']);
        return view('soal.form', compact('soal'));
    }

    public function update(SoalRequest $request, Soal $soal): RedirectResponse
    {
        $this->soalService->updateSoal($soal, $request->validated());

        return redirect()->route('soal.show', $soal)->with('success', 'Soal berhasil diperbarui (versi ditingkatkan).');
    }

    public function destroy(Soal $soal): RedirectResponse
    {
        $this->soalService->deleteSoal($soal);

        return redirect()->route('soal.index')->with('success', 'Soal berhasil dihapus/diarsipkan.');
    }

    public function archive(Soal $soal): RedirectResponse
    {
        $this->soalService->archiveSoal($soal);

        return redirect()->route('soal.show', $soal)->with('success', 'Soal berhasil diarsipkan.');
    }
}
