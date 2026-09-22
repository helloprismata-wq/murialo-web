@extends('lowongan.layout')

@section('title', 'Bank Soal')

@section('content')
    <div class="heading">
        <div>
            <div class="subtitle">Smart Grading / Bank Soal</div>
            <h1>Bank Soal</h1>
        </div>
        <a class="button button-primary" href="{{ route('soal.create') }}">+ Buat Soal Baru</a>
    </div>

    <div class="disclaimer-banner">
        <span>ℹ</span>
        <span>Data dummy yang bertanda <strong>[DEMO]</strong> digunakan untuk demonstrasi sistem; belum divalidasi sebagai instrumen seleksi psikometrik.</span>
    </div>

    <form method="get" class="filters">
        <div>
            <input type="search" name="q" value="{{ request('q') }}" placeholder="Cari kode soal, judul, atau pertanyaan..." aria-label="Cari soal">
        </div>
        <div>
            <select name="kategori" aria-label="Filter kategori">
                <option value="">Semua Kategori Kemampuan</option>
                <option value="pemahaman_informasi" @selected(request('kategori') === 'pemahaman_informasi')>Pemahaman Informasi Tertulis</option>
                <option value="instruksi" @selected(request('kategori') === 'instruksi')>Pemahaman Instruksi</option>
                <option value="penalaran" @selected(request('kategori') === 'penalaran')>Penalaran Berdasarkan Informasi</option>
            </select>
        </div>
        <div>
            <select name="status" aria-label="Filter status">
                <option value="">Semua Status</option>
                <option value="aktif" @selected(request('status') === 'aktif')>Aktif</option>
                <option value="draft" @selected(request('status') === 'draft')>Draft</option>
                <option value="arsip" @selected(request('status') === 'arsip')>Arsip</option>
            </select>
        </div>
        <button type="submit" class="button button-secondary">Filter</button>
        @if (request()->hasAny(['q', 'status', 'kategori']))
            <a href="{{ route('soal.index') }}" class="button button-secondary">Reset</a>
        @endif
    </form>

    @if ($soalList->isEmpty())
        <div class="empty">
            <div class="icon">📝</div>
            <h3>Belum ada soal ditemukan</h3>
            <p>Mulai tambahkan soal jawaban singkat untuk evaluasi pemahaman bacaan, instruksi, atau penalaran.</p>
            <div style="margin-top: 18px;">
                <a class="button button-primary" href="{{ route('soal.create') }}">+ Tambah Soal Pertama</a>
            </div>
        </div>
    @else
        <div class="cards">
            @foreach ($soalList as $item)
                <div class="card job">
                    <div class="heading">
                        <div>
                            <div style="display: flex; gap: 8px; align-items: center; margin-bottom: 6px;">
                                <span class="badge badge-{{ $item->status }}">{{ ucfirst($item->status) }}</span>
                                <span style="font-family: monospace; font-size: 12px; color: var(--accent); background: var(--accent-surface); padding: 2px 8px; border-radius: var(--radius-pill);">
                                    {{ $item->kode_soal }}
                                </span>
                                @if ($item->is_dummy)
                                    <span style="font-size: 11px; font-weight: 600; color: #facc15; background: rgba(250, 204, 21, 0.1); padding: 2px 6px; border-radius: var(--radius-pill);">
                                        DEMO
                                    </span>
                                @endif
                                <span style="font-size: 11px; color: var(--text-muted);">v{{ $item->versi }}</span>
                            </div>
                            <h2 style="font-size: 18px;"><a href="{{ route('soal.show', $item) }}">{{ $item->judul }}</a></h2>
                        </div>
                        <div class="meta" style="font-size: 13px;">
                            <span>Maks: <strong>{{ $item->skor_maksimum }} poin</strong></span>
                        </div>
                    </div>

                    <div style="font-size: 13.5px; color: var(--text-secondary); margin: 10px 0; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                        {{ $item->pertanyaan }}
                    </div>

                    <div class="meta" style="margin-top: 14px; font-size: 12.5px;">
                        <span>Kategori: <strong>{{ ucwords(str_replace('_', ' ', $item->kategori)) }}</strong></span>
                        <span>Rubrik: {{ $item->rubrik()->count() }} kriteria</span>
                        <span>Acuan: {{ $item->jawabanAcuan()->count() }} contoh</span>
                    </div>

                    <div class="actions" style="margin-top: 16px;">
                        <a class="button button-secondary" href="{{ route('soal.show', $item) }}">Detail & Rubrik</a>
                        <a class="button button-secondary" href="{{ route('soal.edit', $item) }}">Edit Soal</a>
                        @if ($item->status !== 'arsip')
                            <form action="{{ route('soal.archive', $item) }}" method="post" style="display:inline;" onsubmit="return confirm('Arsipkan soal ini?');">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="button button-danger" style="background: transparent; border-color: var(--border);">Arsipkan</button>
                            </form>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

        <div class="pagination">
            {{ $soalList->links('pagination::simple-default') }}
        </div>
    @endif
@endsection
