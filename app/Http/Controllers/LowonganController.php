<?php

namespace App\Http\Controllers;

use App\Http\Requests\LowonganRequest;
use App\Models\Lowongan;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class LowonganController extends Controller
{
    public function index(Request $request): View
    {
        $filters = $request->validate([
            'q' => ['nullable', 'string', 'max:255'],
            'status' => ['nullable', Rule::in(array_keys(Lowongan::STATUS))],
        ]);
        $lowongan = Lowongan::where('user_id', $request->user()->id)
            ->when($filters['q'] ?? null, function ($query, $search) {
                $query->where(function ($query) use ($search) {
                    $query->where('judul', 'like', '%'.$search.'%')
                        ->orWhere('perusahaan', 'like', '%'.$search.'%')
                        ->orWhere('lokasi', 'like', '%'.$search.'%');
                });
            })
            ->when($filters['status'] ?? null, fn ($query, $status) => $query->where('status', $status))
            ->latest('id')->paginate(10)->withQueryString();

        return view('lowongan.index', compact('lowongan'));
    }

    public function create(): View
    {
        return view('lowongan.form', ['lowongan' => new Lowongan(['status' => 'draft'])]);
    }

    public function store(LowonganRequest $request): RedirectResponse
    {
        $lowongan = new Lowongan($request->validated());
        $lowongan->user()->associate($request->user());
        $lowongan->save();

        return redirect()->route('lowongan.show', $lowongan)->with('success', 'Lowongan berhasil dibuat.');
    }

    public function show(Request $request, Lowongan $lowongan): View
    {
        $this->checkOwner($request, $lowongan);

        return view('lowongan.show', compact('lowongan'));
    }

    public function edit(Request $request, Lowongan $lowongan): View
    {
        $this->checkOwner($request, $lowongan);

        return view('lowongan.form', compact('lowongan'));
    }

    public function update(LowonganRequest $request, Lowongan $lowongan): RedirectResponse
    {
        $lowongan->update($request->validated());

        return redirect()->route('lowongan.show', $lowongan)->with('success', 'Lowongan berhasil diperbarui.');
    }

    public function destroy(Request $request, Lowongan $lowongan): RedirectResponse
    {
        $this->checkOwner($request, $lowongan);
        $lowongan->delete();

        return redirect()->route('lowongan.index')->with('success', 'Lowongan berhasil dihapus.');
    }

    private function checkOwner(Request $request, Lowongan $lowongan): void
    {
        abort_unless($lowongan->user_id === $request->user()->id, 403);
    }
}
