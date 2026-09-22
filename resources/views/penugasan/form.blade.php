@extends('lowongan.layout')

@section('title', 'Tugaskan Paket Tes ke Pelamar')

@section('content')
    <div class="heading">
        <div>
            <a href="{{ route('penugasan.index') }}" class="meta" style="display: inline-block; margin-bottom: 6px;">← Kembali</a>
            <div class="subtitle">Penugasan Tes</div>
            <h1>Berikan Paket Tes kepada Pelamar</h1>
        </div>
    </div>

    <form method="post" action="{{ route('penugasan.store') }}">
        @csrf

        <div class="grid" style="display: grid; grid-template-columns: 2fr 1fr; gap: 24px;">
            <div>
                <div class="panel" style="margin-bottom: 24px;">
                    <h3 style="font-size: 16px; margin-bottom: 16px; color: var(--text);">1. Pilih Pelamar & Lowongan *</h3>

                    @if ($lamaranList->isEmpty())
                        <div style="padding: 16px; background: rgba(248, 113, 113, 0.1); border: 1px solid rgba(248, 113, 113, 0.3); border-radius: var(--radius-sm); color: #f87171; font-size: 13.5px;">
                            Belum ada berkas lamaran dari kandidat yang masuk ke sistem.
                        </div>
                    @else
                        <div style="margin-bottom: 16px;">
                            <label for="lamaran_id">Lamaran Kandidat *</label>
                            <select id="lamaran_id" name="lamaran_id" required>
                                <option value="">-- Pilih Berkas Lamaran Kandidat --</option>
                                @foreach ($lamaranList as $lam)
                                    <option value="{{ $lam->id }}" @selected(old('lamaran_id', $selectedLamaranId) == $lam->id)>
                                        {{ $lam->kandidat->name }} ({{ $lam->kandidat->email }}) — Posisi: {{ $lam->lowongan->judul }} ({{ $lam->lowongan->perusahaan }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    @endif
                </div>

                <div class="panel">
                    <h3 style="font-size: 16px; margin-bottom: 16px; color: var(--text);">2. Pilih Paket Tes Aktif *</h3>

                    @if ($paketList->isEmpty())
                        <div style="padding: 16px; background: rgba(250, 204, 21, 0.1); border: 1px solid rgba(250, 204, 21, 0.3); border-radius: var(--radius-sm); color: #facc15; font-size: 13.5px;">
                            Belum ada paket tes yang berstatus <strong>Aktif</strong>. Silakan buat atau aktifkan paket tes di menu <a href="{{ route('paket.index') }}" style="text-decoration: underline;">Paket Tes</a>.
                        </div>
                    @else
                        <div style="display: flex; flex-direction: column; gap: 12px;">
                            @foreach ($paketList as $pkt)
                                <label style="display: flex; gap: 12px; padding: 14px; background: var(--bg-surface-alt); border: 1px solid var(--border); border-radius: var(--radius-sm); cursor: pointer; align-items: flex-start;">
                                    <input type="radio" name="paket_tes_id" value="{{ $pkt->id }}" @checked(old('paket_tes_id') == $pkt->id || request('paket_tes_id') == $pkt->id || $loop->first) required style="margin-top: 4px;">
                                    <div style="flex: 1;">
                                        <div style="display: flex; justify-content: space-between; align-items: center;">
                                            <strong style="font-size: 15px; color: var(--text);">{{ $pkt->nama }}</strong>
                                            <span style="font-weight: 700; color: var(--accent); font-size: 13px;">{{ $pkt->durasi_menit }} Menit</span>
                                        </div>
                                        <p style="font-size: 13px; color: var(--text-secondary); margin: 6px 0;">{{ $pkt->deskripsi ?: 'Tidak ada deskripsi.' }}</p>
                                        <div class="meta" style="font-size: 12px;">
                                            <span>Jumlah Soal: {{ $pkt->soal_count }}</span>
                                            <span>Total Skor: {{ $pkt->total_skor_maksimum }} poin</span>
                                        </div>
                                    </div>
                                </label>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

            <div>
                <div class="panel">
                    <h3 style="font-size: 15px; margin-bottom: 14px; color: var(--text);">3. Waktu & Batas Pengerjaan</h3>

                    <div style="margin-bottom: 16px;">
                        <label for="waktu_tersedia">Mulai Tersedia *</label>
                        <input type="datetime-local" id="waktu_tersedia" name="waktu_tersedia" value="{{ old('waktu_tersedia', $waktuTersedia) }}" required>
                        <div class="form-hint">Waktu paling awal kandidat dapat menekan tombol Mulai Tes</div>
                    </div>

                    <div style="margin-bottom: 20px;">
                        <label for="batas_waktu">Batas Akhir (Deadline) *</label>
                        <input type="datetime-local" id="batas_waktu" name="batas_waktu" value="{{ old('batas_waktu', $batasWaktu) }}" required>
                        <div class="form-hint">Kandidat wajib menyelesaikan tes sebelum waktu ini</div>
                    </div>

                    <div style="display: flex; flex-direction: column; gap: 10px;">
                        <button type="submit" class="button button-primary" style="width: 100%;" @disabled($lamaranList->isEmpty() || $paketList->isEmpty())>
                            Kirim Penugasan ke Kandidat
                        </button>
                        <a href="{{ route('penugasan.index') }}" class="button button-secondary" style="width: 100%; text-align: center;">Batal</a>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection
