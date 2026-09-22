@extends('lowongan.layout')

@section('title', 'Detail Penugasan · ' . ($penugasan->kandidat->name ?? 'Kandidat'))

@section('content')
    <div class="heading">
        <div>
            <a href="{{ route('penugasan.index') }}" class="meta" style="display: inline-block; margin-bottom: 6px;">← Kembali ke Penugasan</a>
            <div style="display: flex; gap: 8px; align-items: center; margin-bottom: 6px;">
                <span class="badge badge-{{ $penugasan->status_pengerjaan === 'selesai' ? 'aktif' : ($penugasan->status_pengerjaan === 'sedang_mengerjakan' ? 'draft' : 'ditutup') }}">
                    Pengerjaan: {{ ucwords(str_replace('_', ' ', $penugasan->status_pengerjaan)) }}
                </span>
                <span class="badge badge-{{ $penugasan->status_penilaian === 'selesai_dinilai' ? 'aktif' : ($penugasan->status_penilaian === 'sedang_dinilai' ? 'draft' : 'ditutup') }}">
                    Penilaian: {{ ucwords(str_replace('_', ' ', $penugasan->status_penilaian)) }}
                </span>
                @if ($penugasan->is_published)
                    <span style="font-size: 11px; font-weight: 600; color: #34d399; background: rgba(52, 211, 153, 0.1); padding: 2px 8px; border-radius: var(--radius-pill);">
                        ✓ Hasil Dipublikasikan
                    </span>
                @endif
            </div>
            <h1>Penugasan: {{ $penugasan->kandidat->name }}</h1>
        </div>
        <div class="actions">
            @if ($penugasan->status_pengerjaan === 'selesai')
                <a class="button button-primary" href="{{ route('penilaian.show', $penugasan) }}">
                    {{ $penugasan->status_penilaian === 'selesai_dinilai' ? 'Lihat Hasil Nilai' : 'Periksa & Nilai Jawaban' }}
                </a>
            @endif
        </div>
    </div>

    <div class="grid" style="display: grid; grid-template-columns: 2fr 1fr; gap: 24px; margin-top: 20px;">
        <div>
            <!-- Paket Snapshot Card -->
            <div class="panel" style="margin-bottom: 24px;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px;">
                    <h3 style="font-size: 16px; color: var(--text);">Paket Tes yang Ditugaskan</h3>
                    <span style="font-size: 11px; color: var(--accent); background: var(--accent-surface); padding: 2px 8px; border-radius: var(--radius-pill);">
                        Snapshot Terkunci
                    </span>
                </div>
                <div style="font-size: 16px; font-weight: 700; color: #f1f5f9; margin-bottom: 6px;">
                    {{ $penugasan->paket_snapshot['nama'] ?? '-' }}
                </div>
                <p style="font-size: 13.5px; color: var(--text-secondary); margin-bottom: 14px;">
                    {{ $penugasan->paket_snapshot['deskripsi'] ?? 'Tidak ada deskripsi.' }}
                </p>

                <div class="meta" style="font-size: 13px;">
                    <span>Durasi: <strong>{{ $penugasan->paket_snapshot['durasi_menit'] ?? 0 }} Menit</strong></span>
                    <span>Total Soal: <strong>{{ count($penugasan->paket_snapshot['soal'] ?? []) }} butir</strong></span>
                    <span>Total Skor Maks: <strong>{{ $penugasan->paket_snapshot['total_skor_maksimum'] ?? 0 }} poin</strong></span>
                </div>
            </div>

            <!-- Percobaan Pengerjaan Detail -->
            <div class="panel">
                <h3 style="font-size: 16px; margin-bottom: 14px; color: var(--text);">Riwayat Percobaan Pengerjaan</h3>
                @if ($penugasan->percobaan)
                    <div style="display: flex; flex-direction: column; gap: 12px; font-size: 13.5px;">
                        <div>
                            <span style="color: var(--text-muted);">Status Percobaan:</span>
                            <strong style="color: var(--text);">{{ ucwords(str_replace('_', ' ', $penugasan->percobaan->status)) }}</strong>
                        </div>
                        <div>
                            <span style="color: var(--text-muted);">Waktu Mulai:</span>
                            <strong style="color: var(--text);">{{ $penugasan->percobaan->waktu_mulai?->format('d M Y, H:i:s') ?? '-' }}</strong>
                        </div>
                        <div>
                            <span style="color: var(--text-muted);">Batas Waktu Berakhir:</span>
                            <strong style="color: var(--text);">{{ $penugasan->percobaan->waktu_berakhir?->format('d M Y, H:i:s') ?? '-' }}</strong>
                        </div>
                        <div>
                            <span style="color: var(--text-muted);">Waktu Selesai:</span>
                            <strong style="color: var(--text);">{{ $penugasan->percobaan->waktu_selesai?->format('d M Y, H:i:s') ?? '-' }}</strong>
                        </div>
                        <div>
                            <span style="color: var(--text-muted);">Jumlah Jawaban Terisi:</span>
                            <strong style="color: var(--accent);">{{ $penugasan->percobaan->jawaban()->whereNotNull('jawaban')->count() }} / {{ count($penugasan->paket_snapshot['soal'] ?? []) }}</strong>
                        </div>
                    </div>
                @else
                    <div style="color: var(--text-muted); font-size: 13.5px;">
                        Kandidat belum memulai pengerjaan tes ini.
                    </div>
                @endif
            </div>
        </div>

        <div>
            <!-- Info Kandidat & Lamaran -->
            <div class="panel" style="margin-bottom: 24px;">
                <h3 style="font-size: 15px; margin-bottom: 14px; color: var(--text);">Informasi Pelamar</h3>
                <div style="display: flex; flex-direction: column; gap: 12px; font-size: 13.5px;">
                    <div>
                        <div style="color: var(--text-muted); font-size: 12px;">Nama Kandidat</div>
                        <div style="font-weight: 600; color: var(--text);">{{ $penugasan->kandidat->name }}</div>
                    </div>
                    <div>
                        <div style="color: var(--text-muted); font-size: 12px;">Email</div>
                        <div style="color: var(--text);">{{ $penugasan->kandidat->email }}</div>
                    </div>
                    <div>
                        <div style="color: var(--text-muted); font-size: 12px;">Posisi Lowongan</div>
                        <div style="font-weight: 600; color: var(--accent);">{{ $penugasan->lamaran->lowongan->judul ?? '-' }}</div>
                        <div style="font-size: 12px; color: var(--text-muted);">{{ $penugasan->lamaran->lowongan->perusahaan ?? '' }}</div>
                    </div>
                </div>
            </div>

            <!-- Periode Tes -->
            <div class="panel">
                <h3 style="font-size: 15px; margin-bottom: 14px; color: var(--text);">Periode Ketersediaan</h3>
                <div style="display: flex; flex-direction: column; gap: 12px; font-size: 13.5px;">
                    <div>
                        <div style="color: var(--text-muted); font-size: 12px;">Mulai Tersedia</div>
                        <div style="color: var(--text);">{{ $penugasan->waktu_tersedia->format('d M Y, H:i') }}</div>
                    </div>
                    <div>
                        <div style="color: var(--text-muted); font-size: 12px;">Batas Akhir (Deadline)</div>
                        <div style="color: #f87171; font-weight: 600;">{{ $penugasan->batas_waktu->format('d M Y, H:i') }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
