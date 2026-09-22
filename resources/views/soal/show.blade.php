@extends('lowongan.layout')

@section('title', $soal->judul . ' · Bank Soal')

@section('content')
    <div class="heading">
        <div>
            <a href="{{ route('soal.index') }}" class="meta" style="display: inline-block; margin-bottom: 6px;">← Kembali ke Bank Soal</a>
            <div style="display: flex; gap: 8px; align-items: center; margin-bottom: 6px;">
                <span class="badge badge-{{ $soal->status }}">{{ ucfirst($soal->status) }}</span>
                <span style="font-family: monospace; font-size: 12px; color: var(--accent); background: var(--accent-surface); padding: 2px 8px; border-radius: var(--radius-pill);">
                    {{ $soal->kode_soal }}
                </span>
                @if ($soal->is_dummy)
                    <span style="font-size: 11px; font-weight: 600; color: #facc15; background: rgba(250, 204, 21, 0.1); padding: 2px 6px; border-radius: var(--radius-pill);">
                        Data Dummy Demo
                    </span>
                @endif
                <span style="font-size: 12px; color: var(--text-muted);">Versi aktif: <strong>v{{ $soal->versi }}</strong></span>
            </div>
            <h1>{{ $soal->judul }}</h1>
        </div>
        <div class="actions">
            <a class="button button-primary" href="{{ route('soal.edit', $soal) }}">Edit Soal</a>
            @if ($soal->status !== 'arsip')
                <form action="{{ route('soal.archive', $soal) }}" method="post" onsubmit="return confirm('Arsipkan soal ini?');" style="display:inline;">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="button button-secondary">Arsipkan</button>
                </form>
            @endif
            <form action="{{ route('soal.destroy', $soal) }}" method="post" onsubmit="return confirm('Hapus/arsipkan soal ini? Riwayat tes akan tetap aman.');" style="display:inline;">
                @csrf
                @method('DELETE')
                <button type="submit" class="button button-danger">Hapus</button>
            </form>
        </div>
    </div>

    @if ($soal->is_dummy)
        <div class="disclaimer-banner">
            <span>ℹ</span>
            <span>Soal ini ditandai sebagai <strong>Data dummy untuk demonstrasi; belum divalidasi sebagai instrumen seleksi.</strong></span>
        </div>
    @endif

    <div class="grid" style="display: grid; grid-template-columns: 2fr 1fr; gap: 24px; margin-top: 20px;">
        <div>
            @if ($soal->teks_bacaan)
                <div class="reading-context">
                    <h4>Teks Bacaan / Konteks</h4>
                    {!! nl2br(e($soal->teks_bacaan)) !!}
                </div>
            @endif

            <div class="panel" style="margin-bottom: 24px;">
                <h3 style="font-size: 16px; margin-bottom: 12px; color: var(--text);">Pertanyaan</h3>
                <p style="font-size: 15px; color: #f1f5f9; line-height: 1.7;">{{ $soal->pertanyaan }}</p>
            </div>

            <!-- Jawaban Acuan (HR Only) -->
            <div class="panel" style="margin-bottom: 24px;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px;">
                    <h3 style="font-size: 16px; color: var(--text);">Jawaban Acuan (Kunci Referensi HR)</h3>
                    <span style="font-size: 11px; text-transform: uppercase; letter-spacing: 0.5px; color: #f87171; background: rgba(248, 113, 113, 0.1); padding: 2px 8px; border-radius: var(--radius-pill);">
                        🔒 Rahasia HR (Tidak Dikirim ke Kandidat)
                    </span>
                </div>
                <p style="font-size: 13px; color: var(--text-muted); margin-bottom: 12px;">
                    Contoh respon acuan yang dijadikan pedoman bagi penilai saat memeriksa jawaban singkat kandidat:
                </p>

                @forelse ($soal->jawabanAcuan as $ja)
                    <div class="jawaban-acuan-card">
                        <span class="label-tag">Contoh Jawaban #{{ $loop->iteration }}</span>
                        <p style="font-size: 14.5px; color: #e2e8f0; margin-bottom: 4px;">{{ $ja->jawaban }}</p>
                        @if ($ja->keterangan)
                            <p style="font-size: 12.5px; color: var(--text-muted);"><em>Catatan: {{ $ja->keterangan }}</em></p>
                        @endif
                    </div>
                @empty
                    <p style="font-size: 13px; color: var(--text-muted);">Belum ada contoh jawaban acuan.</p>
                @endforelse
            </div>

            <!-- Rubrik Penilaian -->
            <div class="panel">
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <div>
                        <h3 style="font-size: 16px; color: var(--text);">Rubrik Penilaian</h3>
                        <div style="font-size: 13px; color: var(--text-muted); margin-top: 2px;">
                            Total Poin: <strong>{{ $soal->totalPoinRubrik() }} / {{ $soal->skor_maksimum }}</strong>
                            @if ($soal->isRubrikValid())
                                <span style="color: var(--accent); margin-left: 6px;">✓ Sesuai skor maksimum</span>
                            @else
                                <span style="color: var(--danger); margin-left: 6px;">⚠ Tidak sesuai skor maksimum</span>
                            @endif
                        </div>
                    </div>
                </div>

                <table class="rubrik-table">
                    <thead>
                        <tr>
                            <th style="width: 30px;">#</th>
                            <th>Kriteria Penilaian</th>
                            <th>Deskripsi / Panduan Poin</th>
                            <th style="width: 90px; text-align: right;">Poin Maks</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($soal->rubrik as $r)
                            <tr>
                                <td>{{ $r->urutan }}</td>
                                <td><strong style="color: var(--text);">{{ $r->kriteria }}</strong></td>
                                <td style="color: var(--text-secondary);">{{ $r->deskripsi ?: '-' }}</td>
                                <td style="text-align: right; font-weight: 700; color: var(--accent);">{{ $r->poin_maksimum }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" style="text-align: center; color: var(--text-muted);">Belum ada kriteria rubrik.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div>
            <!-- Meta Panel -->
            <div class="panel" style="margin-bottom: 24px;">
                <h3 style="font-size: 15px; margin-bottom: 14px; color: var(--text);">Informasi Soal</h3>
                <div style="display: flex; flex-direction: column; gap: 12px; font-size: 13.5px;">
                    <div>
                        <div style="color: var(--text-muted); font-size: 12px;">Kategori Kemampuan</div>
                        <div style="font-weight: 600; color: var(--text);">{{ ucwords(str_replace('_', ' ', $soal->kategori)) }}</div>
                    </div>
                    <div>
                        <div style="color: var(--text-muted); font-size: 12px;">Skor Maksimum</div>
                        <div style="font-weight: 700; color: var(--accent); font-size: 18px;">{{ $soal->skor_maksimum }} poin</div>
                    </div>
                    <div>
                        <div style="color: var(--text-muted); font-size: 12px;">Penyusun / Author</div>
                        <div style="color: var(--text);">{{ $soal->user->name ?? 'HR Murialo' }}</div>
                    </div>
                    <div>
                        <div style="color: var(--text-muted); font-size: 12px;">Sumber / Catatan</div>
                        <div style="color: var(--text-secondary);">{{ $soal->sumber ?: 'Internal HR Murialo' }}</div>
                    </div>
                    <div>
                        <div style="color: var(--text-muted); font-size: 12px;">Dibuat Pada</div>
                        <div style="color: var(--text-secondary);">{{ $soal->created_at->format('d M Y, H:i') }}</div>
                    </div>
                </div>
            </div>

            <!-- Version History / Snapshot Panel -->
            <div class="panel">
                <h3 style="font-size: 15px; margin-bottom: 14px; color: var(--text);">Riwayat Versi (Snapshot)</h3>
                <p style="font-size: 12px; color: var(--text-muted); margin-bottom: 12px;">
                    Setiap perubahan soal membekukan snapshot otomatis untuk menjaga integritas tes yang telah ditugaskan.
                </p>
                <div style="display: flex; flex-direction: column; gap: 8px;">
                    @forelse ($soal->snapshots as $snap)
                        <div style="display: flex; justify-content: space-between; align-items: center; padding: 8px 12px; background: var(--bg-surface-alt); border-radius: var(--radius-sm); font-size: 12.5px;">
                            <span>Versi {{ $snap->versi }}</span>
                            <span style="color: var(--text-muted);">{{ $snap->created_at->format('d/m/Y H:i') }}</span>
                        </div>
                    @empty
                        <div style="font-size: 12.5px; color: var(--text-muted);">Versi awal (v1).</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
@endsection
