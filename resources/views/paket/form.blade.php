@extends('lowongan.layout')

@section('title', $paket->exists ? 'Edit Paket Tes: ' . $paket->nama : 'Buat Paket Tes Baru')

@section('content')
    <div class="heading">
        <div>
            <a href="{{ $paket->exists ? route('paket.show', $paket) : route('paket.index') }}" class="meta" style="display: inline-block; margin-bottom: 6px;">← Kembali</a>
            <div class="subtitle">{{ $paket->exists ? 'Edit Paket Tes' : 'Paket Tes Baru' }}</div>
            <h1>{{ $paket->exists ? 'Edit Paket: ' . $paket->nama : 'Buat Paket Tes Baru' }}</h1>
        </div>
    </div>

    <form method="post" action="{{ $paket->exists ? route('paket.update', $paket) : route('paket.store') }}">
        @csrf
        @if ($paket->exists)
            @method('PUT')
        @endif

        <div class="grid" style="display: grid; grid-template-columns: 2fr 1fr; gap: 24px;">
            <div>
                <div class="panel" style="margin-bottom: 24px;">
                    <h3 style="font-size: 16px; margin-bottom: 16px; color: var(--text);">1. Informasi Paket Tes</h3>

                    <div style="margin-bottom: 16px;">
                        <label for="nama">Nama Paket Tes *</label>
                        <input type="text" id="nama" name="nama" value="{{ old('nama', $paket->nama) }}" required placeholder="Contoh: Tes Penalaran & Pemahaman Kerja Operasional">
                    </div>

                    <div class="grid" style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 16px;">
                        <div>
                            <label for="durasi_menit">Durasi Pengerjaan (Menit) *</label>
                            <input type="number" id="durasi_menit" name="durasi_menit" value="{{ old('durasi_menit', $paket->durasi_menit ?? 30) }}" min="5" max="300" required>
                            <div class="form-hint">Dihitung otomatis sejak kandidat mulai menekan 'Mulai Tes'</div>
                        </div>
                        <div>
                            <label for="status">Status Paket *</label>
                            <select id="status" name="status" required>
                                <option value="draft" @selected(old('status', $paket->status) === 'draft')>Draft</option>
                                <option value="aktif" @selected(old('status', $paket->status) === 'aktif')>Aktif (Siap Ditugaskan)</option>
                                <option value="arsip" @selected(old('status', $paket->status) === 'arsip')>Arsip</option>
                            </select>
                        </div>
                    </div>

                    <div style="margin-bottom: 16px;">
                        <label for="deskripsi">Deskripsi Singkat</label>
                        <textarea id="deskripsi" name="deskripsi" rows="2" placeholder="Tuliskan tujuan evaluasi paket tes ini...">{{ old('deskripsi', $paket->deskripsi) }}</textarea>
                    </div>

                    <div>
                        <label for="petunjuk">Petunjuk Pengerjaan untuk Kandidat</label>
                        <textarea id="petunjuk" name="petunjuk" rows="4" placeholder="Petunjuk ini akan dibaca kandidat sebelum memulai tes (contoh: 'Bacalah setiap teks dengan teliti, jawab singkat dan padat...').">{{ old('petunjuk', $paket->petunjuk) }}</textarea>
                    </div>
                </div>

                <!-- Soal Selection -->
                <div class="panel">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 14px;">
                        <div>
                            <h3 style="font-size: 16px; color: var(--text);">2. Pilih Soal dari Bank Soal *</h3>
                            <div class="form-hint">Pilih butir soal yang akan dimasukkan ke dalam paket tes ini.</div>
                        </div>
                        <div style="font-size: 13px; color: var(--accent); font-weight: 600;">
                            Terpilih: <span id="count-selected">0</span> soal
                        </div>
                    </div>

                    @if ($availableSoal->isEmpty())
                        <div style="padding: 20px; text-align: center; color: var(--text-muted);">
                            Belum ada soal di Bank Soal. <a href="{{ route('soal.create') }}">Buat soal terlebih dahulu</a>.
                        </div>
                    @else
                        <div style="display: flex; flex-direction: column; gap: 10px; max-height: 480px; overflow-y: auto; padding-right: 6px;">
                            @foreach ($availableSoal as $s)
                                @php
                                    $isChecked = in_array($s->id, old('soal_ids', $selectedSoalIds));
                                @endphp
                                <label style="display: flex; gap: 12px; padding: 12px; background: var(--bg-surface-alt); border: 1px solid var(--border); border-radius: var(--radius-sm); cursor: pointer; align-items: flex-start;">
                                    <input type="checkbox" name="soal_ids[]" value="{{ $s->id }}" class="soal-checkbox" data-skor="{{ $s->skor_maksimum }}" @checked($isChecked) style="margin-top: 4px;" onchange="updatePaketSummary()">
                                    <div style="flex: 1;">
                                        <div style="display: flex; justify-content: space-between; align-items: center;">
                                            <div style="display: flex; gap: 8px; align-items: center;">
                                                <span style="font-family: monospace; font-size: 11px; color: var(--accent); background: var(--accent-surface); padding: 1px 6px; border-radius: 4px;">{{ $s->kode_soal }}</span>
                                                <span class="badge badge-{{ $s->status }}" style="font-size: 10px; padding: 2px 6px;">{{ ucfirst($s->status) }}</span>
                                                <strong style="font-size: 14px; color: var(--text);">{{ $s->judul }}</strong>
                                            </div>
                                            <span style="font-weight: 700; color: var(--accent); font-size: 13px;">{{ $s->skor_maksimum }} poin</span>
                                        </div>
                                        <p style="font-size: 12.5px; color: var(--text-secondary); margin-top: 4px; line-height: 1.4;">{{ Str::limit($s->pertanyaan, 130) }}</p>
                                    </div>
                                </label>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

            <div>
                <!-- Panel Relasi Lowongan -->
                <div class="panel" style="margin-bottom: 24px;">
                    <h3 style="font-size: 15px; margin-bottom: 12px; color: var(--text);">3. Hubungkan ke Lowongan</h3>
                    <p style="font-size: 12.5px; color: var(--text-muted); margin-bottom: 14px;">
                        Pilih lowongan pekerjaan yang menggunakan paket tes ini:
                    </p>

                    <div style="display: flex; flex-direction: column; gap: 8px; max-height: 300px; overflow-y: auto;">
                        @forelse ($availableLowongan as $low)
                            @php
                                $isLowChecked = in_array($low->id, old('lowongan_ids', $selectedLowonganIds));
                            @endphp
                            <label style="display: flex; gap: 10px; padding: 8px 10px; background: var(--bg-surface-alt); border-radius: var(--radius-sm); cursor: pointer; align-items: center; font-size: 13px;">
                                <input type="checkbox" name="lowongan_ids[]" value="{{ $low->id }}" @checked($isLowChecked)>
                                <div>
                                    <div style="font-weight: 600; color: var(--text);">{{ $low->judul }}</div>
                                    <div style="font-size: 11px; color: var(--text-muted);">{{ $low->perusahaan }}</div>
                                </div>
                            </label>
                        @empty
                            <p style="font-size: 12.5px; color: var(--text-muted);">Belum ada lowongan tersedia.</p>
                        @endforelse
                    </div>
                </div>

                <div class="panel">
                    <h3 style="font-size: 15px; margin-bottom: 14px; color: var(--text);">Ringkasan Skor</h3>
                    <div style="font-size: 13.5px; margin-bottom: 16px;">
                        <div style="color: var(--text-muted); font-size: 12px;">Total Skor Maksimum Dihitung:</div>
                        <div style="font-size: 26px; font-weight: 800; color: var(--accent); margin-top: 4px;">
                            <span id="total-skor-display">0</span> <span style="font-size: 14px; font-weight: 500; color: var(--text-secondary);">poin</span>
                        </div>
                    </div>

                    <div style="display: flex; flex-direction: column; gap: 10px;">
                        <button type="submit" class="button button-primary" style="width: 100%;">Simpan Paket Tes</button>
                        <a href="{{ $paket->exists ? route('paket.show', $paket) : route('paket.index') }}" class="button button-secondary" style="width: 100%; text-align: center;">Batal</a>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <script>
        function updatePaketSummary() {
            const checkboxes = document.querySelectorAll('.soal-checkbox:checked');
            let totalSkor = 0;
            checkboxes.forEach(cb => {
                totalSkor += parseInt(cb.dataset.skor || 0, 10);
            });

            document.getElementById('count-selected').textContent = checkboxes.length;
            document.getElementById('total-skor-display').textContent = totalSkor;
        }

        window.addEventListener('DOMContentLoaded', updatePaketSummary);
    </script>
@endsection
