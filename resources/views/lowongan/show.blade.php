@extends('lowongan.layout')
@section('title', $lowongan->judul)
@section('content')
<a class="back-link" href="{{ route('lowongan.index') }}">← Semua lowongan</a>

<div class="heading">
    <div>
        <p class="eyebrow">DETAIL LOWONGAN</p>
        <h1>{{ $lowongan->judul }}</h1>
        <p class="muted">🏢 {{ $lowongan->perusahaan }} · 📍 {{ $lowongan->lokasi }}</p>
    </div>
    <div style="display: flex; gap: 8px; align-items: center;">
        <a class="button button-primary" href="{{ route('lowongan.matching', $lowongan) }}" style="background: #6366f1; border-color: #4f46e5;">🎯 Skill Matching AI</a>
        <a class="button" href="{{ route('lowongan.edit', $lowongan) }}">✏ Edit lowongan</a>
    </div>
</div>

<article class="panel">
    <span class="badge {{ $lowongan->status }}">
        @if ($lowongan->status === 'aktif') ● @elseif ($lowongan->status === 'draft') ◌ @else ✕ @endif
        {{ \App\Models\Lowongan::STATUS[$lowongan->status] }}
    </span>

    <dl class="grid facts">
        <div>
            <dt>Tipe pekerjaan</dt>
            <dd>{{ \App\Models\Lowongan::TIPE[$lowongan->tipe_pekerjaan] }}</dd>
        </div>
        <div>
            <dt>Batas lamaran</dt>
            <dd>{{ $lowongan->batas_lamaran?->format('d/m/Y') ?? 'Tidak ditentukan' }}</dd>
        </div>
        <div>
            <dt>Gaji per bulan</dt>
            <dd>
                @if ($lowongan->gaji_min !== null && $lowongan->gaji_max !== null)
                    Rp {{ number_format($lowongan->gaji_min, 0, ',', '.') }} – Rp {{ number_format($lowongan->gaji_max, 0, ',', '.') }}
                @elseif ($lowongan->gaji_min !== null)
                    Mulai Rp {{ number_format($lowongan->gaji_min, 0, ',', '.') }}
                @elseif ($lowongan->gaji_max !== null)
                    Hingga Rp {{ number_format($lowongan->gaji_max, 0, ',', '.') }}
                @else
                    Tidak dicantumkan
                @endif
            </dd>
        </div>
        <div>
            <dt>Diperbarui</dt>
            <dd>{{ $lowongan->updated_at->format('d/m/Y H:i') }}</dd>
        </div>
    </dl>

    <section class="description">
        <h2>Deskripsi pekerjaan</h2>
        <p>{{ $lowongan->deskripsi ?: 'Tidak dicantumkan' }}</p>
    </section>

    <section class="description">
        <h2>Persyaratan</h2>
        <p>{{ $lowongan->persyaratan ?: 'Tidak dicantumkan' }}</p>
    </section>

    <section class="description">
        <h2>Keahlian yang dibutuhkan</h2>
        @if ($lowongan->skills)
            <div class="skill-tags">
                @foreach (explode(',', $lowongan->skills) as $skill)
                    <span class="skill-tag">{{ trim($skill) }}</span>
                @endforeach
            </div>
        @else
            <p>Tidak dicantumkan</p>
        @endif
    </section>
</article>

<details class="panel delete">
    <summary>🗑 Hapus lowongan</summary>
    <p>Lowongan ini akan dihapus permanen. Tindakan ini tidak dapat dibatalkan.</p>
    <form action="{{ route('lowongan.destroy', $lowongan) }}" method="post">
        @csrf
        @method('DELETE')
        <button class="button danger" type="submit">Ya, hapus lowongan ini</button>
    </form>
</details>
@endsection
