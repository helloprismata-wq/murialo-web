@extends('lowongan.layout')
@section('title', 'Kelola lowongan')
@section('content')
<div class="heading">
    <div>
        <p class="eyebrow">RUANG REKRUTMEN</p>
        <h1>Lowongan saya</h1>
        <p class="muted">Atur dan pantau peluang kerja yang Anda buat.</p>
    </div>
    <a class="button" href="{{ route('lowongan.create') }}">+ Tambah lowongan</a>
</div>

@php
    $allLowongan = \App\Models\Lowongan::where('user_id', auth()->id());
    $totalAktif = (clone $allLowongan)->where('status', 'aktif')->count();
    $totalDraft = (clone $allLowongan)->where('status', 'draft')->count();
    $totalDitutup = (clone $allLowongan)->where('status', 'ditutup')->count();
    $totalSemua = $totalAktif + $totalDraft + $totalDitutup;
@endphp
<div class="stats-row">
    <div class="stat-card">
        <div class="stat-value">{{ $totalSemua }}</div>
        <div class="stat-label">Total</div>
    </div>
    <div class="stat-card">
        <div class="stat-value">{{ $totalAktif }}</div>
        <div class="stat-label">Aktif</div>
    </div>
    <div class="stat-card">
        <div class="stat-value">{{ $totalDraft }}</div>
        <div class="stat-label">Draft</div>
    </div>
    <div class="stat-card">
        <div class="stat-value">{{ $totalDitutup }}</div>
        <div class="stat-label">Ditutup</div>
    </div>
</div>

<form class="filters panel" method="get" action="{{ route('lowongan.index') }}">
    <div>
        <label for="q">🔍 Cari lowongan</label>
        <input id="q" name="q" maxlength="255" placeholder="Posisi, perusahaan, atau lokasi..." value="{{ request('q') }}">
    </div>
    <div>
        <label for="status">Status</label>
        <select id="status" name="status">
            <option value="">Semua status</option>
            @foreach (\App\Models\Lowongan::STATUS as $value => $label)
                <option value="{{ $value }}" @selected(request('status') === $value)>{{ $label }}</option>
            @endforeach
        </select>
    </div>
    <button class="button" type="submit">Terapkan</button>
    <a href="{{ route('lowongan.index') }}">Reset</a>
</form>

<p class="muted" style="margin-bottom: 16px;">{{ $lowongan->total() }} lowongan ditemukan</p>

<div class="cards">
@forelse ($lowongan as $item)
    <article class="panel job">
        <div class="heading">
            <span class="badge {{ $item->status }}">
                @if ($item->status === 'aktif') ● @elseif ($item->status === 'draft') ◌ @else ✕ @endif
                {{ \App\Models\Lowongan::STATUS[$item->status] }}
            </span>
            <span class="muted">{{ \App\Models\Lowongan::TIPE[$item->tipe_pekerjaan] }}</span>
        </div>
        <h2><a href="{{ route('lowongan.show', $item) }}">{{ $item->judul }}</a></h2>
        <p class="meta">
            <span>🏢 {{ $item->perusahaan }}</span>
            <span>📍 {{ $item->lokasi }}</span>
        </p>
        @if ($item->gaji_min !== null || $item->gaji_max !== null)
            <p class="salary">
                💰
                @if ($item->gaji_min !== null && $item->gaji_max !== null)
                    Rp {{ number_format($item->gaji_min, 0, ',', '.') }} – {{ number_format($item->gaji_max, 0, ',', '.') }}
                @elseif ($item->gaji_min !== null)
                    Mulai Rp {{ number_format($item->gaji_min, 0, ',', '.') }}
                @else
                    Hingga Rp {{ number_format($item->gaji_max, 0, ',', '.') }}
                @endif
            </p>
        @endif
        <p class="muted">📅 Batas: {{ $item->batas_lamaran?->format('d/m/Y') ?? 'Tidak ditentukan' }}</p>
        <div class="actions">
            <a href="{{ route('lowongan.show', $item) }}">Lihat detail →</a>
            <a href="{{ route('lowongan.edit', $item) }}">Edit</a>
        </div>
    </article>
@empty
    <div class="panel empty">
        <h2>Belum ada lowongan yang sesuai</h2>
        <p>Tambahkan lowongan pertama atau ubah pencarian Anda.</p>
        <a class="button" href="{{ route('lowongan.create') }}">Tambah lowongan</a>
    </div>
@endforelse
</div>

@if ($lowongan->hasPages())
<nav class="pagination" aria-label="Halaman lowongan">
    @if ($lowongan->previousPageUrl())<a href="{{ $lowongan->previousPageUrl() }}">← Sebelumnya</a>@endif
    <span>Halaman {{ $lowongan->currentPage() }} dari {{ $lowongan->lastPage() }}</span>
    @if ($lowongan->nextPageUrl())<a href="{{ $lowongan->nextPageUrl() }}">Berikutnya →</a>@endif
</nav>
@endif
@endsection
