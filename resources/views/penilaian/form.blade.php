@extends('lowongan.layout')

@section('title', 'Penilaian Tes: ' . $penugasan->kandidat->name)

@section('content')
    <div class="heading">
        <div>
            <a href="{{ route('penilaian.show', $penugasan) }}" class="meta" style="display: inline-block; margin-bottom: 6px;">← Kembali ke Lembar Hasil</a>
            <div class="subtitle">Penilaian Manual HR</div>
            <h1>Formulir Penilaian: {{ $penugasan->kandidat->name }}</h1>
        </div>
    </div>

    <div class="disclaimer-banner info">
        <span>ℹ</span>
        <span><strong>Penilaian saat ini dilakukan oleh HR.</strong> Masukkan skor untuk setiap kriteria rubrik berdasarkan kualitas jawaban kandidat dibandingkan dengan jawaban acuan.</span>
    </div>

    @php
        $isEditingCompleted = ($penugasan->status_penilaian === 'selesai_dinilai');
        $soalList = $penugasan->paket_snapshot['soal'] ?? [];
        $jawabanList = $penugasan->percobaan ? $penugasan->percobaan->jawaban->keyBy('soal_id') : collect();
        $existingDetails = $penugasan->penilaian ? $penugasan->penilaian->detail->keyBy(fn($d) => $d->jawaban_kandidat_id . '_' . $d->soal_rubrik_id) : collect();
    @endphp

    <form method="post" action="{{ route('penilaian.update', $penugasan) }}" id="grading-form">
        @csrf
        @method('PUT')

        <div class="grid" style="display: grid; grid-template-columns: 2fr 1fr; gap: 24px;">
            <div>
                @foreach ($soalList as $s)
                    @php
                        $jawabanItem = $jawabanList->get($s['id']);
                        $jawabanId = $jawabanItem ? $jawabanItem->id : 0;
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
                            <span style="font-size: 13px; color: var(--text-muted);">Maks: <strong>{{ $s['skor_maksimum'] }} poin</strong></span>
                        </div>

                        @if (!empty($s['teks_bacaan']))
                            <div class="reading-context" style="margin-bottom: 14px; padding: 12px 16px; font-size: 13.5px;">
                                <h4>Teks Bacaan / Konteks:</h4>
                                {!! nl2br(e($s['teks_bacaan'])) !!}
                            </div>
                        @endif

                        <div style="font-size: 14.5px; font-weight: 600; color: #e2e8f0; margin-bottom: 12px;">
                            Pertanyaan: {{ $s['pertanyaan'] }}
                        </div>

                        <!-- Jawaban Kandidat -->
                        <div style="margin-bottom: 16px;">
                            <div style="font-size: 12px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; margin-bottom: 6px;">
                                Jawaban Kandidat:
                            </div>
                            @if ($jawabanItem && trim($jawabanItem->jawaban ?? '') !== '')
                                <div class="kandidat-answer-box">{{ $jawabanItem->jawaban }}</div>
                            @else
                                <div class="kandidat-answer-box empty">(Kandidat tidak mengisi jawaban untuk soal ini)</div>
                            @endif
                        </div>

                        <!-- Jawaban Acuan -->
                        <div class="jawaban-acuan-card" style="margin-bottom: 16px;">
                            <span class="label-tag">🔒 Jawaban Acuan (Referensi HR)</span>
                            @foreach ($s['jawaban_acuan'] ?? [] as $ja)
                                <div style="font-size: 13.5px; color: #cbd5e1; margin-top: 4px;">
                                    • {{ $ja['jawaban'] }} @if(!empty($ja['keterangan'])) <em style="color: var(--text-muted);">({{ $ja['keterangan'] }})</em> @endif
                                </div>
                            @endforeach
                        </div>

                        <!-- Input Skor Rubrik -->
                        <div>
                            <div style="font-size: 12px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; margin-bottom: 8px;">
                                Input Skor Kriteria:
                            </div>
                            <table class="rubrik-table">
                                <thead>
                                    <tr>
                                        <th>Kriteria Rubrik</th>
                                        <th>Panduan / Deskripsi</th>
                                        <th style="width: 70px; text-align: right;">Batas</th>
                                        <th style="width: 110px;">Input Skor *</th>
                                        <th style="width: 180px;">Catatan Kriteria</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($s['rubrik'] ?? [] as $r)
                                        @php
                                            $key = $jawabanId . '_' . $r['id'];
                                            $detail = $existingDetails->get($key);
                                            $oldSkor = old("scores.{$jawabanId}.{$r['id']}.skor", $detail ? $detail->skor : '');
                                            $oldCatatan = old("scores.{$jawabanId}.{$r['id']}.catatan", $detail ? $detail->catatan : '');
                                        @endphp
                                        <tr>
                                            <td><strong>{{ $r['kriteria'] }}</strong></td>
                                            <td style="color: var(--text-secondary); font-size: 12px;">{{ $r['deskripsi'] ?: '-' }}</td>
                                            <td style="text-align: right; color: var(--text-muted); font-weight: 600;">{{ $r['poin_maksimum'] }}</td>
                                            <td>
                                                <input type="number" step="0.5" min="0" max="{{ $r['poin_maksimum'] }}"
                                                       class="score-input"
                                                       data-max="{{ $r['poin_maksimum'] }}"
                                                       name="scores[{{ $jawabanId }}][{{ $r['id'] }}][skor]"
                                                       value="{{ $oldSkor }}"
                                                       placeholder="0 - {{ $r['poin_maksimum'] }}"
                                                       oninput="calculateLiveScore()"
                                                       style="padding: 6px 10px; font-size: 13.5px; text-align: right;">
                                            </td>
                                            <td>
                                                <input type="text"
                                                       name="scores[{{ $jawabanId }}][{{ $r['id'] }}][catatan]"
                                                       value="{{ $oldCatatan }}"
                                                       placeholder="Alasan poin..."
                                                       style="padding: 6px 10px; font-size: 12.5px;">
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endforeach
            </div>

            <div>
                <!-- Panel Live Total & Submit -->
                <div class="panel" style="position: sticky; top: 90px;">
                    <h3 style="font-size: 16px; margin-bottom: 14px; color: var(--text);">Kalkulasi Nilai Akhir</h3>

                    <div style="text-align: center; padding: 18px 0; background: var(--bg-surface-alt); border-radius: var(--radius-sm); margin-bottom: 16px;">
                        <div style="font-size: 12px; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.5px;">Nilai Akhir (Skala 100)</div>
                        <div style="font-size: 44px; font-weight: 900; color: var(--accent); line-height: 1.1; margin: 6px 0;">
                            <span id="display-nilai-akhir">0</span>
                        </div>
                        <div style="font-size: 13px; color: var(--text-secondary);">
                            Total Skor Diperoleh: <strong id="display-total-skor">0</strong> / <span id="display-max-skor">{{ $penugasan->paket_snapshot['total_skor_maksimum'] ?? 100 }}</span> poin
                        </div>
                    </div>

                    <div style="margin-bottom: 16px;">
                        <label for="catatan_umum">Catatan Umum Penilai (Opsional)</label>
                        <textarea id="catatan_umum" name="catatan_umum" rows="3" placeholder="Catatan keseluruhan performa kandidat...">{{ old('catatan_umum', $penugasan->penilaian?->catatan_umum) }}</textarea>
                    </div>

                    @if ($isEditingCompleted)
                        <div style="margin-bottom: 18px; padding: 12px; background: rgba(234, 179, 8, 0.08); border: 1px solid rgba(234, 179, 8, 0.3); border-radius: var(--radius-sm);">
                            <label for="alasan_perubahan" style="color: #facc15; font-size: 12.5px; font-weight: 700;">
                                Alasan Perubahan Nilai *
                            </label>
                            <textarea id="alasan_perubahan" name="alasan_perubahan" rows="2" required placeholder="Wajib mengisi alasan revisi penilaian ini...">{{ old('alasan_perubahan') }}</textarea>
                            <div class="form-hint" style="color: #fef08a;">Perubahan akan dicatat dalam riwayat revisi (audit trail).</div>
                        </div>
                    @endif

                    <div style="display: flex; flex-direction: column; gap: 10px;">
                        <button type="submit" name="action" value="complete" class="button button-primary" style="width: 100%;" onclick="return confirm('Selesaikan penilaian ini? Pastikan semua kriteria telah terisi.');">
                            ✓ Selesaikan Penilaian
                        </button>
                        <button type="submit" name="action" value="draft" class="button button-secondary" style="width: 100%;">
                            💾 Simpan Sebagai Draf
                        </button>
                        <a href="{{ route('penilaian.show', $penugasan) }}" class="button button-secondary" style="width: 100%; text-align: center; background: transparent; border-color: transparent;">
                            Batal
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <script>
        function calculateLiveScore() {
            const inputs = document.querySelectorAll('.score-input');
            let totalObtained = 0;
            let totalMax = 0;

            inputs.forEach(input => {
                const max = parseFloat(input.dataset.max) || 0;
                totalMax += max;

                const val = parseFloat(input.value);
                if (!isNaN(val)) {
                    totalObtained += val;
                }
            });

            const nilaiAkhir = totalMax > 0 ? ((totalObtained / totalMax) * 100).toFixed(2) : '0';

            document.getElementById('display-total-skor').textContent = totalObtained.toFixed(1).replace(/\.0$/, '');
            document.getElementById('display-max-skor').textContent = totalMax.toFixed(0);
            document.getElementById('display-nilai-akhir').textContent = nilaiAkhir;
        }

        window.addEventListener('DOMContentLoaded', calculateLiveScore);
    </script>
@endsection
