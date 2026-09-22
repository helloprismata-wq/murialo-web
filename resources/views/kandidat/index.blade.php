@extends('lowongan.layout')

@section('title', 'Tes Saya · Kandidat')

@section('content')
    <div class="heading">
        <div>
            <div class="subtitle">Portal Kandidat</div>
            <h1>Tes Jawaban Singkat Saya</h1>
        </div>
    </div>

    @if ($daftarTes->isEmpty())
        <div class="empty">
            <div class="icon">📑</div>
            <h3>Belum ada tes yang ditugaskan</h3>
            <p>Saat ini belum ada penugasan tes untuk lamaran kerja Anda. Harap cek kembali secara berkala.</p>
        </div>
    @else
        <div class="cards">
            @foreach ($daftarTes as $item)
                <div class="card job">
                    <div class="heading">
                        <div>
                            <div style="display: flex; gap: 8px; align-items: center; margin-bottom: 6px;">
                                <span class="badge badge-{{ $item->status_pengerjaan === 'selesai' ? 'aktif' : ($item->status_pengerjaan === 'sedang_mengerjakan' ? 'draft' : 'ditutup') }}">
                                    {{ ucwords(str_replace('_', ' ', $item->status_pengerjaan)) }}
                                </span>
                                <span style="font-size: 12px; color: var(--text-muted);">
                                    Durasi: <strong>{{ $item->paket_snapshot['durasi_menit'] ?? '-' }} Menit</strong>
                                </span>
                            </div>
                            <h2 style="font-size: 18px;">
                                <a href="{{ route('kandidat.show', $item) }}">
                                    {{ $item->paket_snapshot['nama'] ?? 'Paket Tes Rekrutmen' }}
                                </a>
                            </h2>
                        </div>
                        <div class="meta" style="font-size: 13px; text-align: right;">
                            @if ($item->is_published && $item->penilaian)
                                <div style="font-size: 11px; color: var(--text-muted);">Hasil Nilai Akhir:</div>
                                <div style="font-size: 22px; font-weight: 900; color: var(--accent);">{{ $item->penilaian->nilai_akhir }}</div>
                            @elseif ($item->status_pengerjaan === 'selesai')
                                <span style="color: var(--text-muted); font-size: 12.5px;">Dalam proses penilaian HR</span>
                            @else
                                <span style="color: var(--text-muted); font-size: 12.5px;">{{ count($item->paket_snapshot['soal'] ?? []) }} Soal</span>
                            @endif
                        </div>
                    </div>

                    <div class="meta" style="margin: 12px 0; font-size: 12.5px;">
                        <span>Posisi: <strong>{{ $item->lamaran->lowongan->judul ?? '-' }}</strong></span>
                        <span>Perusahaan: {{ $item->lamaran->lowongan->perusahaan ?? '-' }}</span>
                        <span>Batas Pengerjaan: <strong style="color: #f87171;">{{ $item->batas_waktu->format('d M Y, H:i') }}</strong></span>
                    </div>

                    <div class="actions" style="margin-top: 16px;">
                        @if ($item->status_pengerjaan === 'sedang_mengerjakan')
                            <a class="button button-primary" href="{{ route('kandidat.kerjakan', $item) }}">Lanjutkan Pengerjaan Tes</a>
                        @elseif ($item->status_pengerjaan === 'belum_dimulai')
                            <a class="button button-primary" href="{{ route('kandidat.show', $item) }}">Lihat Petunjuk & Mulai Tes</a>
                        @else
                            <a class="button button-secondary" href="{{ route('kandidat.show', $item) }}">Lihat Rincian & Status</a>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

        <div class="pagination">
            {{ $daftarTes->links('pagination::simple-default') }}
        </div>
    @endif
@endsection
