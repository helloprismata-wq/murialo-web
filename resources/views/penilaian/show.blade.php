@extends('lowongan.layout')

@section('title', 'Hasil Penilaian · ' . $penugasan->kandidat->name)

@section('content')
    <div class="heading">
        <div>
            <a href="{{ route('penilaian.index') }}" class="meta" style="display: inline-block; margin-bottom: 6px;">← Kembali ke Daftar Hasil Tes</a>
            <div style="display: flex; gap: 8px; align-items: center; margin-bottom: 6px;">
                <span class="badge badge-{{ $penugasan->status_penilaian === 'selesai_dinilai' ? 'aktif' : ($penugasan->status_penilaian === 'sedang_dinilai' ? 'draft' : 'ditutup') }}">
                    Status: {{ ucwords(str_replace('_', ' ', $penugasan->status_penilaian)) }}
                </span>
                @if ($penugasan->is_published)
                    <span style="font-size: 11px; font-weight: 600; color: #34d399; background: rgba(52, 211, 153, 0.1); padding: 2px 8px; border-radius: var(--radius-pill);">
                        ✓ Dipublikasikan ke Kandidat
                    </span>
                @else
                    <span style="font-size: 11px; color: var(--text-muted); background: var(--bg-surface-alt); padding: 2px 8px; border-radius: var(--radius-pill);">
                        🔒 Draf Internal (Belum Dilihat Kandidat)
                    </span>
                @endif
            </div>
            <h1>Hasil Tes: {{ $penugasan->kandidat->name }}</h1>
        </div>
        <div class="actions">
            <a class="button button-primary" href="{{ route('penilaian.edit', $penugasan) }}">
                {{ $penugasan->status_penilaian === 'selesai_dinilai' ? 'Ubah Penilaian' : 'Input / Lanjutkan Penilaian' }}
            </a>

            <form action="{{ route('penilaian.retry_ai', $penugasan) }}" method="post" style="display:inline;">
                @csrf
                <button type="submit" class="button button-secondary" style="border-color: #6366f1; color: #a5b4fc;" onclick="return confirm('Jalankan ulang evaluasi Smart Grading S-BERT dari AI Engine?');">
                    ⚡ {{ isset($aiResult) ? 'Jalankan Ulang AI' : 'Evaluasi dengan AI' }}
                </button>
            </form>

            @if ($penugasan->status_penilaian === 'selesai_dinilai')
                <form action="{{ route('penilaian.publish', $penugasan) }}" method="post" style="display:inline;" onsubmit="return confirm('{{ $penugasan->is_published ? 'Tarik kembali publikasi nilai dari kandidat?' : 'Publikasikan nilai ke kandidat? Kandidat akan dapat melihat nilai akhir.' }}');">
                    @csrf
                    <input type="hidden" name="publish" value="{{ $penugasan->is_published ? '0' : '1' }}">
                    <button type="submit" class="button {{ $penugasan->is_published ? 'button-secondary' : 'button-primary' }}" style="{{ !$penugasan->is_published ? 'background: #059669;' : '' }}">
                        {{ $penugasan->is_published ? 'Tarik Publikasi' : 'Publikasikan Hasil ke Kandidat' }}
                    </button>
                </form>
            @endif
        </div>
    </div>

    @if (isset($aiResult) && $aiResult->status === 'completed')
        <div class="disclaimer-banner success" style="background: rgba(99, 102, 241, 0.1); border-left: 4px solid #6366f1; padding: 12px 18px; margin-bottom: 20px; border-radius: var(--radius-sm); display: flex; justify-content: space-between; align-items: center;">
            <div style="display: flex; gap: 12px; align-items: center;">
                <span style="font-size: 20px;">🤖</span>
                <div>
                    <strong style="color: #e0e7ff;">Smart Grading AI (S-BERT & Cosine Similarity) Aktif</strong>
                    <div style="font-size: 12.5px; color: #cbd5e1; margin-top: 2px;">
                        Prediksi Skor AI: <strong style="color: #a5b4fc;">{{ $aiResult->score }}/100</strong> · Model: <code>{{ $aiResult->model_version }}</code> · Waktu inferensi: {{ $aiResult->execution_time_ms }} ms
                    </div>
                </div>
            </div>
            <span style="font-size: 11px; background: rgba(99, 102, 241, 0.2); color: #c7d2fe; padding: 3px 10px; border-radius: var(--radius-pill); font-weight: 600;">
                Tervalidasi Otomatis
            </span>
        </div>
    @elseif (isset($aiResult) && $aiResult->status === 'failed')
        <div class="disclaimer-banner error" style="background: rgba(239, 68, 68, 0.1); border-left: 4px solid #ef4444; padding: 12px 18px; margin-bottom: 20px; border-radius: var(--radius-sm); display: flex; justify-content: space-between; align-items: center;">
            <div style="display: flex; gap: 12px; align-items: center;">
                <span style="font-size: 20px;">⚠️</span>
                <div>
                    <strong style="color: #fca5a5;">Smart Grading AI Mengalami Kendala</strong>
                    <div style="font-size: 12.5px; color: #cbd5e1; margin-top: 2px;">
                        {{ $aiResult->error_message }} — Penilaian manual oleh HR tetap dapat dilakukan.
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="disclaimer-banner info">
            <span>ℹ</span>
            <span><strong>Smart Grading & Penilaian HR:</strong> Sistem menggunakan model S-BERT dan Cosine Similarity untuk menghasilkan draf evaluasi cerdas, didukung verifikasi penuh oleh tim HR.</span>
        </div>
    @endif

    <div class="grid" style="display: grid; grid-template-columns: 2fr 1fr; gap: 24px;">
        <div>
            @php
                $soalList = $penugasan->paket_snapshot['soal'] ?? [];
                $jawabanList = $penugasan->percobaan ? $penugasan->percobaan->jawaban->keyBy('soal_id') : collect();
                $penilaianDetails = $penugasan->penilaian ? $penugasan->penilaian->detail->groupBy('jawaban_kandidat_id') : collect();
            @endphp

            @foreach ($soalList as $s)
                @php
                    $jawabanItem = $jawabanList->get($s['id']);
                    $details = $jawabanItem ? ($penilaianDetails->get($jawabanItem->id) ?? collect()) : collect();
                    $skorSoalDiperoleh = $details->sum('skor');
                @endphp

                <div class="panel" style="margin-bottom: 24px;">
                    <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 12px;">
                        <div style="display: flex; align-items: center; gap: 10px;">
                            <span style="width: 28px; height: 28px; border-radius: 50%; background: var(--accent-surface); color: var(--accent); display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 13px;">
                                {{ $s['urutan'] }}
                            </span>
                            <div>
                                <span style="font-family: monospace; font-size: 11px; color: var(--accent);">{{ $s['kode_soal'] }} (v{{ $s['versi'] }})</span>
                                <h3 style="font-size: 16px; margin-top: 2px; color: var(--text);">{{ $s['judul'] }}</h3>
                            </div>
                        </div>
                        <div style="text-align: right;">
                            <span style="font-size: 16px; font-weight: 800; color: var(--accent);">{{ $skorSoalDiperoleh }}</span>
                            <span style="font-size: 13px; color: var(--text-muted);">/ {{ $s['skor_maksimum'] }} poin</span>
                        </div>
                    </div>

                    @if (!empty($s['teks_bacaan']))
                        <div class="reading-context" style="margin-bottom: 14px; padding: 12px 16px; font-size: 13.5px;">
                            <h4>Teks Bacaan / Konteks:</h4>
                            {!! nl2br(e($s['teks_bacaan'])) !!}
                        </div>
                    @endif

                    <div style="font-size: 14.5px; font-weight: 600; color: #e2e8f0; margin-bottom: 10px;">
                        Pertanyaan: {{ $s['pertanyaan'] }}
                    </div>

                    <!-- Jawaban Kandidat -->
                    <div style="margin: 16px 0;">
                        <div style="font-size: 12px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; margin-bottom: 6px;">
                            Jawaban Kandidat:
                        </div>
                        @if ($jawabanItem && trim($jawabanItem->jawaban ?? '') !== '')
                            <div class="kandidat-answer-box">{{ $jawabanItem->jawaban }}</div>
                        @else
                            <div class="kandidat-answer-box empty">(Kandidat tidak mengisi jawaban)</div>
                        @endif
                    </div>

                    <!-- Jawaban Acuan (HR Only) -->
                    <div class="jawaban-acuan-card" style="margin-bottom: 16px;">
                        <span class="label-tag">🔒 Jawaban Acuan (Referensi HR)</span>
                        @foreach ($s['jawaban_acuan'] ?? [] as $ja)
                            <div style="font-size: 13.5px; color: #cbd5e1; margin-top: 4px;">
                                • {{ $ja['jawaban'] }} @if(!empty($ja['keterangan'])) <em style="color: var(--text-muted);">({{ $ja['keterangan'] }})</em> @endif
                            </div>
                        @endforeach
                    </div>

                    <!-- Rubrik & Penilaian -->
                    <div>
                        <div style="font-size: 12px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; margin-bottom: 8px;">
                            Evaluasi Rubrik:
                        </div>
                        <table class="rubrik-table">
                            <thead>
                                <tr>
                                    <th>Kriteria</th>
                                    <th>Deskripsi</th>
                                    <th style="width: 80px; text-align: right;">Maks</th>
                                    <th style="width: 80px; text-align: right;">Skor Diberikan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($s['rubrik'] ?? [] as $r)
                                    @php
                                        $d = $details->firstWhere('soal_rubrik_id', $r['id']);
                                    @endphp
                                    <tr>
                                        <td><strong>{{ $r['kriteria'] }}</strong></td>
                                        <td style="color: var(--text-secondary); font-size: 12.5px;">{{ $r['deskripsi'] ?: '-' }}</td>
                                        <td style="text-align: right; color: var(--text-muted);">{{ $r['poin_maksimum'] }}</td>
                                        <td style="text-align: right; font-weight: 700; color: var(--accent);">
                                            {{ $d ? $d->skor : '0' }}
                                        </td>
                                    </tr>
                                    @if ($d && $d->catatan)
                                        <tr>
                                            <td colspan="4" style="background: rgba(255,255,255,0.02); font-size: 12px; color: var(--text-muted); padding-left: 24px;">
                                                Catatan penilai: <em>{{ $d->catatan }}</em>
                                            </td>
                                        </tr>
                                    @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endforeach
        </div>

        <div>
            <!-- Skor Akhir Panel -->
            <div class="panel" style="margin-bottom: 24px;">
                <h3 style="font-size: 15px; margin-bottom: 14px; color: var(--text);">Ringkasan Skor & Nilai</h3>

                @if ($penugasan->penilaian)
                    <div style="text-align: center; padding: 18px 0; background: var(--bg-surface-alt); border-radius: var(--radius-sm); margin-bottom: 16px;">
                        <div style="font-size: 12px; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.5px;">Nilai Akhir (Skala 100)</div>
                        <div style="font-size: 42px; font-weight: 900; color: var(--accent); line-height: 1.1; margin: 6px 0;">
                            {{ $penugasan->penilaian->nilai_akhir }}
                        </div>
                        <div style="font-size: 13px; color: var(--text-secondary);">
                            Total Skor: <strong>{{ $penugasan->penilaian->total_skor_diperoleh }}</strong> / {{ $penugasan->penilaian->total_skor_maksimum }} poin
                        </div>
                    </div>

                    @if ($penugasan->penilaian->catatan_umum)
                        <div style="margin-bottom: 16px;">
                            <div style="font-size: 12px; color: var(--text-muted); margin-bottom: 4px;">Catatan Umum Penilai:</div>
                            <div style="font-size: 13.5px; color: #cbd5e1; background: var(--bg-surface-alt); padding: 10px 14px; border-radius: var(--radius-sm);">
                                {{ $penugasan->penilaian->catatan_umum }}
                            </div>
                        </div>
                    @endif

                    <div style="display: flex; flex-direction: column; gap: 10px; font-size: 12.5px; color: var(--text-muted); border-top: 1px solid var(--border); padding-top: 14px;">
                        <div>Penilai: <strong style="color: var(--text);">{{ $penugasan->penilaian->penilai->name ?? 'HR' }}</strong></div>
                        <div>Waktu Penilaian: <span style="color: var(--text);">{{ $penugasan->penilaian->waktu_penilaian?->format('d M Y, H:i') ?? '-' }}</span></div>
                    </div>
                @else
                    <div style="text-align: center; padding: 24px 0; color: var(--text-muted);">
                        Tes ini belum dinilai oleh HR.
                        <div style="margin-top: 14px;">
                            <a href="{{ route('penilaian.edit', $penugasan) }}" class="button button-primary" style="width: 100%;">Mulai Penilaian</a>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Detail Pengerjaan Panel -->
            <div class="panel" style="margin-bottom: 24px;">
                <h3 style="font-size: 15px; margin-bottom: 12px; color: var(--text);">Informasi Pengerjaan</h3>
                <div style="display: flex; flex-direction: column; gap: 10px; font-size: 13px;">
                    <div>Kandidat: <strong style="color: var(--text);">{{ $penugasan->kandidat->name }}</strong></div>
                    <div>Email: <span style="color: var(--text-secondary);">{{ $penugasan->kandidat->email }}</span></div>
                    <div>Lowongan: <strong style="color: var(--accent);">{{ $penugasan->lamaran->lowongan->judul ?? '-' }}</strong></div>
                    <div>Paket: <span style="color: var(--text);">{{ $penugasan->paket_snapshot['nama'] ?? '-' }}</span></div>
                    <div>Mulai: <span style="color: var(--text-secondary);">{{ $penugasan->percobaan?->waktu_mulai?->format('d/m/Y H:i:s') ?? '-' }}</span></div>
                    <div>Selesai: <span style="color: var(--text-secondary);">{{ $penugasan->percobaan?->waktu_selesai?->format('d/m/Y H:i:s') ?? '-' }}</span></div>
                    <div>Status Selesai: <span style="color: var(--text);">{{ ucwords(str_replace('_', ' ', $penugasan->percobaan?->status ?? '-')) }}</span></div>
                </div>
            </div>

            <!-- Audit Trail Riwayat Perubahan Penilaian -->
            @if ($penugasan->penilaian && $penugasan->penilaian->riwayat->isNotEmpty())
                <div class="panel">
                    <h3 style="font-size: 15px; margin-bottom: 12px; color: var(--text);">Riwayat Revisi Nilai</h3>
                    <div style="display: flex; flex-direction: column; gap: 10px;">
                        @foreach ($penugasan->penilaian->riwayat as $rw)
                            <div style="padding: 10px 12px; background: var(--bg-surface-alt); border-radius: var(--radius-sm); font-size: 12.5px;">
                                <div style="display: flex; justify-content: space-between; margin-bottom: 4px;">
                                    <strong style="color: var(--text);">{{ $rw->user->name ?? 'HR' }}</strong>
                                    <span style="color: var(--text-muted); font-size: 11px;">{{ $rw->created_at->format('d/m/Y H:i') }}</span>
                                </div>
                                <div style="color: var(--accent); margin-bottom: 4px;">
                                    Skor: {{ $rw->skor_sebelumnya }} → <strong>{{ $rw->skor_baru }}</strong>
                                </div>
                                <div style="color: var(--text-secondary); font-style: italic;">
                                    "{{ $rw->alasan_perubahan }}"
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection
