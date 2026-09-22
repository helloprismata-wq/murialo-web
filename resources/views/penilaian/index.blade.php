@extends('lowongan.layout')

@section('title', 'Hasil & Penilaian Tes')

@section('content')
    <div class="heading">
        <div>
            <div class="subtitle">Smart Grading / Penilaian Manual</div>
            <h1>Hasil Tes & Penilaian Manual</h1>
        </div>
    </div>

    <div class="disclaimer-banner info">
        <span>ℹ</span>
        <span><strong>Penilaian saat ini dilakukan oleh HR.</strong> Jawaban kandidat tersimpan secara terstruktur dan siap untuk evaluasi bertahap.</span>
    </div>

    <form method="get" class="filters">
        <div>
            <input type="search" name="q" value="{{ request('q') }}" placeholder="Cari kandidat atau lowongan..." aria-label="Cari hasil tes">
        </div>
        <div>
            <select name="status_penilaian" aria-label="Filter status penilaian">
                <option value="">Semua Status Penilaian</option>
                <option value="belum_dinilai" @selected(request('status_penilaian') === 'belum_dinilai')>Belum Dinilai</option>
                <option value="sedang_dinilai" @selected(request('status_penilaian') === 'sedang_dinilai')>Sedang Dinilai</option>
                <option value="selesai_dinilai" @selected(request('status_penilaian') === 'selesai_dinilai')>Selesai Dinilai</option>
            </select>
        </div>
        <div>
            <select name="is_published" aria-label="Filter publikasi">
                <option value="">Semua Status Publikasi</option>
                <option value="1" @selected(request('is_published') === '1')>Sudah Dipublikasikan</option>
                <option value="0" @selected(request('is_published') === '0')>Belum Dipublikasikan</option>
            </select>
        </div>
        <button type="submit" class="button button-secondary">Filter</button>
        @if (request()->hasAny(['q', 'status_penilaian', 'is_published']))
            <a href="{{ route('penilaian.index') }}" class="button button-secondary">Reset</a>
        @endif
    </form>

    @if ($hasilList->isEmpty())
        <div class="empty">
            <div class="icon">📊</div>
            <h3>Belum ada tes yang siap dinilai</h3>
            <p>Halaman ini menampilkan tes yang telah diselesaikan oleh kandidat untuk dilakukan pemeriksaan dan penilaian manual oleh HR.</p>
        </div>
    @else
        <div class="cards">
            @foreach ($hasilList as $item)
                <div class="card job">
                    <div class="heading">
                        <div>
                            <div style="display: flex; gap: 8px; align-items: center; margin-bottom: 6px;">
                                <span class="badge badge-{{ $item->status_penilaian === 'selesai_dinilai' ? 'aktif' : ($item->status_penilaian === 'sedang_dinilai' ? 'draft' : 'ditutup') }}">
                                    {{ ucwords(str_replace('_', ' ', $item->status_penilaian)) }}
                                </span>
                                @if ($item->is_published)
                                    <span style="font-size: 11px; font-weight: 600; color: #34d399; background: rgba(52, 211, 153, 0.1); padding: 2px 8px; border-radius: var(--radius-pill);">
                                        ✓ Publikasi Aktif
                                    </span>
                                @else
                                    <span style="font-size: 11px; color: var(--text-muted); background: var(--bg-surface-alt); padding: 2px 8px; border-radius: var(--radius-pill);">
                                        Draf Internal HR
                                    </span>
                                @endif
                            </div>
                            <h2 style="font-size: 18px;">
                                <a href="{{ route('penilaian.show', $item) }}">
                                    {{ $item->kandidat->name }} — {{ $item->paket_snapshot['nama'] ?? 'Tes' }}
                                </a>
                            </h2>
                        </div>
                        <div class="meta" style="font-size: 13px; text-align: right;">
                            @if ($item->penilaian)
                                <div>Nilai Akhir: <strong style="font-size: 20px; color: var(--accent);">{{ $item->penilaian->nilai_akhir }}</strong> / 100</div>
                                <div style="font-size: 11.5px; color: var(--text-muted);">Skor: {{ $item->penilaian->total_skor_diperoleh }} / {{ $item->penilaian->total_skor_maksimum }}</div>
                            @else
                                <span style="color: var(--text-muted);">Belum ada nilai</span>
                            @endif
                        </div>
                    </div>

                    <div class="meta" style="margin: 12px 0; font-size: 12.5px;">
                        <span>Posisi: <strong>{{ $item->lamaran->lowongan->judul ?? '-' }}</strong></span>
                        <span>Selesai: {{ $item->percobaan?->waktu_selesai?->format('d M Y, H:i') ?? '-' }}</span>
                        @if ($item->penilaian?->penilai)
                            <span>Penilai: {{ $item->penilaian->penilai->name }}</span>
                        @endif
                    </div>

                    <div class="actions" style="margin-top: 16px;">
                        <a class="button button-secondary" href="{{ route('penilaian.show', $item) }}">Lihat Lembar Jawaban & Nilai</a>
                        <a class="button button-primary" href="{{ route('penilaian.edit', $item) }}">
                            {{ $item->status_penilaian === 'selesai_dinilai' ? 'Ubah Penilaian' : 'Nilai Sekarang' }}
                        </a>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="pagination">
            {{ $hasilList->links('pagination::simple-default') }}
        </div>
    @endif
@endsection
