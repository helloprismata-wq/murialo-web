@extends('lowongan.layout')

@section('title', 'Detail Tes · ' . ($penugasan->paket_snapshot['nama'] ?? 'Tes'))

@section('content')
    <div class="heading">
        <div>
            <a href="{{ route('kandidat.index') }}" class="meta" style="display: inline-block; margin-bottom: 6px;">← Kembali ke Tes Saya</a>
            <div style="display: flex; gap: 8px; align-items: center; margin-bottom: 6px;">
                <span class="badge badge-{{ $penugasan->status_pengerjaan === 'selesai' ? 'aktif' : ($penugasan->status_pengerjaan === 'sedang_mengerjakan' ? 'draft' : 'ditutup') }}">
                    Status: {{ ucwords(str_replace('_', ' ', $penugasan->status_pengerjaan)) }}
                </span>
                @if ($penugasan->is_published)
                    <span style="font-size: 11px; font-weight: 600; color: #34d399; background: rgba(52, 211, 153, 0.1); padding: 2px 8px; border-radius: var(--radius-pill);">
                        ✓ Hasil Telah Dipublikasikan
                    </span>
                @endif
            </div>
            <h1>{{ $penugasan->paket_snapshot['nama'] ?? 'Paket Tes Rekrutmen' }}</h1>
        </div>
        <div class="actions">
            @if ($penugasan->status_pengerjaan === 'belum_dimulai')
                <form action="{{ route('kandidat.start', $penugasan) }}" method="post" onsubmit="return confirm('Apakah Anda yakin ingin memulai tes sekarang? Waktu pengerjaan akan langsung berjalan dan tidak dapat diulang.');">
                    @csrf
                    <button type="submit" class="button button-primary" style="font-size: 15px; padding: 12px 24px;">
                        Mulai Kerjakan Tes Sekarang →
                    </button>
                </form>
            @elseif ($penugasan->status_pengerjaan === 'sedang_mengerjakan')
                <a href="{{ route('kandidat.kerjakan', $penugasan) }}" class="button button-primary" style="font-size: 15px; padding: 12px 24px;">
                    Lanjutkan Pengerjaan Tes →
                </a>
            @endif
        </div>
    </div>

    @if ($penugasan->is_published && $penugasan->penilaian)
        <!-- Kartu Hasil Nilai yang Dipublikasikan -->
        <div class="panel" style="margin-bottom: 24px; border: 1px solid var(--accent); background: rgba(52, 211, 153, 0.04);">
            <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 16px;">
                <div>
                    <span style="font-size: 12px; color: var(--accent); text-transform: uppercase; font-weight: 700; letter-spacing: 0.5px;">Pengumuman Hasil Tes</span>
                    <h2 style="font-size: 22px; color: var(--text); margin-top: 4px;">Nilai Anda Telah Dipublikasikan</h2>
                    <p style="font-size: 13.5px; color: var(--text-secondary); margin-top: 4px;">
                        Evaluasi telah selesai dilakukan oleh tim HR Murialo.
                    </p>
                </div>
                <div style="text-align: right; background: var(--bg-surface-alt); padding: 16px 24px; border-radius: var(--radius-sm); border: 1px solid var(--border);">
                    <div style="font-size: 11px; color: var(--text-muted); text-transform: uppercase;">Nilai Akhir (Skala 100)</div>
                    <div style="font-size: 38px; font-weight: 900; color: var(--accent); line-height: 1;">
                        {{ $penugasan->penilaian->nilai_akhir }}
                    </div>
                </div>
            </div>
            @if ($penugasan->penilaian->catatan_umum)
                <div style="margin-top: 16px; padding-top: 14px; border-top: 1px solid var(--border); font-size: 13.5px; color: #cbd5e1;">
                    <strong>Catatan Tim HR:</strong> {{ $penugasan->penilaian->catatan_umum }}
                </div>
            @endif
        </div>
    @elseif ($penugasan->status_pengerjaan === 'selesai')
        <div class="disclaimer-banner info">
            <span>ℹ</span>
            <span>Tes Anda telah diterima dan saat ini <strong>sedang dalam proses penilaian oleh HR</strong>. Nilai akan tampil setelah dipublikasikan.</span>
        </div>
    @endif

    <div class="grid" style="display: grid; grid-template-columns: 2fr 1fr; gap: 24px;">
        <div>
            <!-- Petunjuk Pengerjaan -->
            <div class="panel" style="margin-bottom: 24px;">
                <h3 style="font-size: 16px; margin-bottom: 12px; color: var(--text);">Petunjuk Pengerjaan Tes</h3>
                <div style="font-size: 14.5px; color: #cbd5e1; line-height: 1.7; white-space: pre-line;">
                    {{ $penugasan->paket_snapshot['petunjuk'] ?: 'Bacalah setiap soal dengan teliti dan berikan jawaban singkat yang padat dan jelas sesuai informasi yang tersedia.' }}
                </div>
            </div>

            <!-- Aturan Penting -->
            <div class="panel">
                <h3 style="font-size: 16px; margin-bottom: 14px; color: var(--text);">Ketentuan Penting</h3>
                <ul style="padding-left: 20px; font-size: 13.5px; color: var(--text-secondary); display: flex; flex-direction: column; gap: 10px; line-height: 1.6;">
                    <li><strong>Satu Kali Kesempatan:</strong> Tes ini hanya dapat dikerjakan satu kali untuk penugasan lamaran terkait.</li>
                    <li><strong>Waktu Server:</strong> Durasi pengerjaan dihitung berdasarkan jam server. Refresh halaman tidak akan menambah waktu.</li>
                    <li><strong>Penyimpanan Otomatis:</strong> Jawaban yang Anda ketik akan tersimpan secara otomatis setiap beberapa detik.</li>
                    <li><strong>Waktu Habis:</strong> Jika waktu habis, seluruh jawaban yang tersimpan akan difinalisasi secara otomatis.</li>
                    <li><strong>Terkunci Setelah Dikirim:</strong> Setelah menekan tombol kirim tes, jawaban tidak dapat diedit kembali.</li>
                </ul>
            </div>
        </div>

        <div>
            <!-- Ringkasan Informasi -->
            <div class="panel" style="margin-bottom: 24px;">
                <h3 style="font-size: 15px; margin-bottom: 14px; color: var(--text);">Ringkasan Tes</h3>
                <div style="display: flex; flex-direction: column; gap: 12px; font-size: 13.5px;">
                    <div>
                        <div style="color: var(--text-muted); font-size: 12px;">Posisi Lowongan</div>
                        <div style="font-weight: 600; color: var(--text);">{{ $penugasan->lamaran->lowongan->judul ?? '-' }}</div>
                        <div style="font-size: 12px; color: var(--text-secondary);">{{ $penugasan->lamaran->lowongan->perusahaan ?? '' }}</div>
                    </div>
                    <div>
                        <div style="color: var(--text-muted); font-size: 12px;">Durasi Pengerjaan</div>
                        <div style="font-weight: 700; color: var(--accent); font-size: 18px;">{{ $penugasan->paket_snapshot['durasi_menit'] ?? 0 }} Menit</div>
                    </div>
                    <div>
                        <div style="color: var(--text-muted); font-size: 12px;">Jumlah Butir Soal</div>
                        <div style="font-weight: 600; color: var(--text);">{{ count($penugasan->paket_snapshot['soal'] ?? []) }} butir soal</div>
                    </div>
                    <div>
                        <div style="color: var(--text-muted); font-size: 12px;">Batas Akhir Penugasan</div>
                        <div style="color: #f87171; font-weight: 600;">{{ $penugasan->batas_waktu->format('d M Y, H:i') }}</div>
                    </div>
                </div>
            </div>

            @if ($penugasan->percobaan)
                <div class="panel">
                    <h3 style="font-size: 15px; margin-bottom: 12px; color: var(--text);">Riwayat Pengerjaan</h3>
                    <div style="display: flex; flex-direction: column; gap: 8px; font-size: 13px; color: var(--text-secondary);">
                        <div>Mulai: {{ $penugasan->percobaan->waktu_mulai?->format('d M Y, H:i') ?? '-' }}</div>
                        @if ($penugasan->percobaan->waktu_selesai)
                            <div>Selesai: {{ $penugasan->percobaan->waktu_selesai->format('d M Y, H:i') }}</div>
                        @endif
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection
