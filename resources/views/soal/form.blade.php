@extends('lowongan.layout')

@section('title', $soal->exists ? 'Edit Soal ' . $soal->kode_soal : 'Tambah Soal Baru')

@section('content')
    <div class="heading">
        <div>
            <a href="{{ $soal->exists ? route('soal.show', $soal) : route('soal.index') }}" class="meta" style="display: inline-block; margin-bottom: 6px;">← Kembali</a>
            <div class="subtitle">{{ $soal->exists ? 'Perbarui Soal (Versi akan dinaikkan otomatis)' : 'Bank Soal HR' }}</div>
            <h1>{{ $soal->exists ? 'Edit Soal: ' . $soal->kode_soal : 'Buat Soal Baru' }}</h1>
        </div>
    </div>

    <div class="disclaimer-banner">
        <span>ℹ</span>
        <span>Instrumen tes jawaban singkat ditujukan untuk <strong>Pemahaman Informasi Tertulis, Instruksi, dan Penalaran</strong>. Rubrik penilaian harus berjumlah sama persis dengan skor maksimum.</span>
    </div>

    <form method="post" action="{{ $soal->exists ? route('soal.update', $soal) : route('soal.store') }}">
        @csrf
        @if ($soal->exists)
            @method('PUT')
        @endif

        <div class="panel" style="margin-bottom: 24px;">
            <h3 style="font-size: 16px; margin-bottom: 16px; color: var(--text);">1. Informasi Dasar Soal</h3>

            <div class="grid" style="display: grid; grid-template-columns: 1fr 2fr; gap: 16px;">
                <div>
                    <label for="kode_soal">Kode Soal (Unik) *</label>
                    <input type="text" id="kode_soal" name="kode_soal" value="{{ old('kode_soal', $soal->kode_soal ?? 'SOAL-' . strtoupper(Str::random(6))) }}" required placeholder="Contoh: SOAL-INF-001">
                </div>
                <div>
                    <label for="judul">Judul Soal *</label>
                    <input type="text" id="judul" name="judul" value="{{ old('judul', $soal->judul) }}" required placeholder="Contoh: Prosedur Keselamatan Kerja di Gudang">
                </div>
            </div>

            <div class="grid" style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 16px; margin-top: 14px;">
                <div>
                    <label for="kategori">Kategori Kemampuan *</label>
                    <select id="kategori" name="kategori" required>
                        <option value="pemahaman_informasi" @selected(old('kategori', $soal->kategori) === 'pemahaman_informasi')>Pemahaman Informasi Tertulis</option>
                        <option value="instruksi" @selected(old('kategori', $soal->kategori) === 'instruksi')>Pemahaman Instruksi</option>
                        <option value="penalaran" @selected(old('kategori', $soal->kategori) === 'penalaran')>Penalaran Berdasarkan Informasi</option>
                    </select>
                </div>
                <div>
                    <label for="skor_maksimum">Skor Maksimum (Total Poin) *</label>
                    <input type="number" id="skor_maksimum" name="skor_maksimum" value="{{ old('skor_maksimum', $soal->skor_maksimum ?? 10) }}" min="1" max="100" required>
                    <div class="form-hint">Harus sama dengan jumlah poin rubrik</div>
                </div>
                <div>
                    <label for="status">Status Soal *</label>
                    <select id="status" name="status" required>
                        <option value="draft" @selected(old('status', $soal->status) === 'draft')>Draft</option>
                        <option value="aktif" @selected(old('status', $soal->status) === 'aktif')>Aktif</option>
                        <option value="arsip" @selected(old('status', $soal->status) === 'arsip')>Arsip</option>
                    </select>
                </div>
            </div>

            <div class="grid" style="display: grid; grid-template-columns: 2fr 1fr; gap: 16px; margin-top: 14px;">
                <div>
                    <label for="sumber">Sumber / Catatan Penyusunan</label>
                    <input type="text" id="sumber" name="sumber" value="{{ old('sumber', $soal->sumber) }}" placeholder="Contoh: Modul SOP Internal v2.1">
                </div>
                <div style="display: flex; align-items: center; padding-top: 24px;">
                    <label style="display: flex; align-items: center; gap: 8px; cursor: pointer;">
                        <input type="checkbox" name="is_dummy" value="1" @checked(old('is_dummy', $soal->is_dummy ?? true))>
                        <span>Tandai sebagai Data Dummy Demo</span>
                    </label>
                </div>
            </div>
        </div>

        <div class="panel" style="margin-bottom: 24px;">
            <h3 style="font-size: 16px; margin-bottom: 16px; color: var(--text);">2. Konten Teks & Pertanyaan</h3>

            <div style="margin-bottom: 16px;">
                <label for="teks_bacaan">Teks Bacaan / Informasi Konteks (Opsional bila soal menyertakan teks bacaan)</label>
                <textarea id="teks_bacaan" name="teks_bacaan" rows="6" placeholder="Tuliskan teks bacaan atau panduan operasional di sini...">{{ old('teks_bacaan', $soal->teks_bacaan) }}</textarea>
            </div>

            <div>
                <label for="pertanyaan">Pertanyaan Jawaban Singkat *</label>
                <textarea id="pertanyaan" name="pertanyaan" rows="3" required placeholder="Tuliskan pertanyaan spesifik yang membutuhkan jawaban singkat berdasarkan teks di atas...">{{ old('pertanyaan', $soal->pertanyaan) }}</textarea>
            </div>
        </div>

        <!-- Jawaban Acuan (HR Only) -->
        <div class="panel" style="margin-bottom: 24px;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 14px;">
                <div>
                    <h3 style="font-size: 16px; color: var(--text);">3. Contoh Jawaban Acuan (Kunci Jawaban HR)</h3>
                    <div class="form-hint">Digunakan HR sebagai pembanding saat menilai jawaban kandidat. Rahasia dan tidak dikirim ke kandidat.</div>
                </div>
                <button type="button" class="button button-secondary" onclick="addJawabanAcuanRow()">+ Tambah Contoh Acuan</button>
            </div>

            <div id="jawaban-acuan-container">
                @php
                    $oldAcuan = old('jawaban_acuan', $soal->exists ? $soal->jawabanAcuan->toArray() : [['jawaban' => '', 'keterangan' => '']]);
                @endphp
                @foreach ($oldAcuan as $index => $item)
                    <div class="jawaban-acuan-row" style="display: grid; grid-template-columns: 2fr 1fr 40px; gap: 12px; margin-bottom: 10px; align-items: flex-start;">
                        <div>
                            <input type="text" name="jawaban_acuan[{{ $index }}][jawaban]" value="{{ $item['jawaban'] ?? '' }}" placeholder="Teks contoh jawaban acuan yang tepat..." required>
                        </div>
                        <div>
                            <input type="text" name="jawaban_acuan[{{ $index }}][keterangan]" value="{{ $item['keterangan'] ?? '' }}" placeholder="Catatan/poin kunci (opsional)">
                        </div>
                        <div>
                            <button type="button" class="button button-danger" style="padding: 10px;" onclick="this.closest('.jawaban-acuan-row').remove()">✕</button>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Rubrik Penilaian -->
        <div class="panel" style="margin-bottom: 24px;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 14px;">
                <div>
                    <h3 style="font-size: 16px; color: var(--text);">4. Rubrik Penilaian</h3>
                    <div class="form-hint" id="rubrik-summary-text">
                        Total Poin Rubrik: <strong id="rubrik-total-display">0</strong> / Target: <strong id="rubrik-target-display">10</strong>
                    </div>
                </div>
                <button type="button" class="button button-secondary" onclick="addRubrikRow()">+ Tambah Kriteria</button>
            </div>

            <div id="rubrik-container">
                @php
                    $oldRubrik = old('rubrik', $soal->exists ? $soal->rubrik->toArray() : [
                        ['kriteria' => 'Ketepatan Informasi', 'poin_maksimum' => 6, 'deskripsi' => 'Menyebutkan poin inti sesuai teks'],
                        ['kriteria' => 'Kelengkapan Fakta', 'poin_maksimum' => 4, 'deskripsi' => 'Menyertakan alasan atau rincian relevan'],
                    ]);
                @endphp
                @foreach ($oldRubrik as $index => $r)
                    <div class="rubrik-row" style="display: grid; grid-template-columns: 2fr 100px 2fr 40px; gap: 12px; margin-bottom: 10px; align-items: flex-start;">
                        <div>
                            <input type="text" name="rubrik[{{ $index }}][kriteria]" value="{{ $r['kriteria'] ?? '' }}" placeholder="Nama kriteria..." required>
                        </div>
                        <div>
                            <input type="number" class="rubrik-poin-input" name="rubrik[{{ $index }}][poin_maksimum]" value="{{ $r['poin_maksimum'] ?? 0 }}" min="1" max="100" placeholder="Poin" required oninput="calculateRubrikTotal()">
                        </div>
                        <div>
                            <input type="text" name="rubrik[{{ $index }}][deskripsi]" value="{{ $r['deskripsi'] ?? '' }}" placeholder="Deskripsi atau panduan skor...">
                        </div>
                        <div>
                            <button type="button" class="button button-danger" style="padding: 10px;" onclick="this.closest('.rubrik-row').remove(); calculateRubrikTotal();">✕</button>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <div style="display: flex; gap: 12px; justify-content: flex-end;">
            <a href="{{ $soal->exists ? route('soal.show', $soal) : route('soal.index') }}" class="button button-secondary">Batal</a>
            <button type="submit" class="button button-primary">Simpan Soal</button>
        </div>
    </form>

    <script>
        let acuanCounter = {{ count($oldAcuan) }};
        let rubrikCounter = {{ count($oldRubrik) }};

        function addJawabanAcuanRow() {
            const container = document.getElementById('jawaban-acuan-container');
            const row = document.createElement('div');
            row.className = 'jawaban-acuan-row';
            row.style = 'display: grid; grid-template-columns: 2fr 1fr 40px; gap: 12px; margin-bottom: 10px; align-items: flex-start;';
            row.innerHTML = `
                <div><input type="text" name="jawaban_acuan[${acuanCounter}][jawaban]" placeholder="Teks contoh jawaban acuan yang tepat..." required></div>
                <div><input type="text" name="jawaban_acuan[${acuanCounter}][keterangan]" placeholder="Catatan/poin kunci (opsional)"></div>
                <div><button type="button" class="button button-danger" style="padding: 10px;" onclick="this.closest('.jawaban-acuan-row').remove()">✕</button></div>
            `;
            container.appendChild(row);
            acuanCounter++;
        }

        function addRubrikRow() {
            const container = document.getElementById('rubrik-container');
            const row = document.createElement('div');
            row.className = 'rubrik-row';
            row.style = 'display: grid; grid-template-columns: 2fr 100px 2fr 40px; gap: 12px; margin-bottom: 10px; align-items: flex-start;';
            row.innerHTML = `
                <div><input type="text" name="rubrik[${rubrikCounter}][kriteria]" placeholder="Nama kriteria..." required></div>
                <div><input type="number" class="rubrik-poin-input" name="rubrik[${rubrikCounter}][poin_maksimum]" value="5" min="1" max="100" placeholder="Poin" required oninput="calculateRubrikTotal()"></div>
                <div><input type="text" name="rubrik[${rubrikCounter}][deskripsi]" placeholder="Deskripsi atau panduan skor..."></div>
                <div><button type="button" class="button button-danger" style="padding: 10px;" onclick="this.closest('.rubrik-row').remove(); calculateRubrikTotal();">✕</button></div>
            `;
            container.appendChild(row);
            rubrikCounter++;
            calculateRubrikTotal();
        }

        function calculateRubrikTotal() {
            const inputs = document.querySelectorAll('.rubrik-poin-input');
            let sum = 0;
            inputs.forEach(input => {
                const val = parseInt(input.value, 10);
                if (!isNaN(val)) sum += val;
            });

            const maxInput = document.getElementById('skor_maksimum');
            const target = parseInt(maxInput.value, 10) || 0;

            document.getElementById('rubrik-total-display').textContent = sum;
            document.getElementById('rubrik-target-display').textContent = target;

            const summary = document.getElementById('rubrik-summary-text');
            if (sum === target && target > 0) {
                summary.style.color = 'var(--accent)';
            } else {
                summary.style.color = 'var(--danger)';
            }
        }

        document.getElementById('skor_maksimum').addEventListener('input', calculateRubrikTotal);
        window.addEventListener('DOMContentLoaded', calculateRubrikTotal);
    </script>
@endsection
