@extends('lowongan.layout')

@section('title', $paket->nama . ' · Paket Tes')

@section('content')
    <div class="heading">
        <div>
            <a href="{{ route('paket.index') }}" class="meta" style="display: inline-block; margin-bottom: 6px;">← Kembali ke Paket Tes</a>
            <div style="display: flex; gap: 8px; align-items: center; margin-bottom: 6px;">
                <span class="badge badge-{{ $paket->status }}">{{ ucfirst($paket->status) }}</span>
                <span style="font-size: 12px; color: var(--text-muted);">Durasi: <strong>{{ $paket->durasi_menit }} Menit</strong></span>
            </div>
            <h1>{{ $paket->nama }}</h1>
        </div>
        <div class="actions">
            <a class="button button-primary" href="{{ route('penugasan.create', ['paket_tes_id' => $paket->id]) }}">Tugaskan ke Kandidat</a>
            <a class="button button-secondary" href="{{ route('paket.edit', $paket) }}">Edit Paket</a>
            <form action="{{ route('paket.destroy', $paket) }}" method="post" onsubmit="return confirm('Hapus/arsipkan paket tes ini?');" style="display:inline;">
                @csrf
                @method('DELETE')
                <button type="submit" class="button button-danger">Hapus</button>
            </form>
        </div>
    </div>

    <div class="grid" style="display: grid; grid-template-columns: 2fr 1fr; gap: 24px; margin-top: 20px;">
        <div>
            @if ($paket->deskripsi)
                <div class="panel" style="margin-bottom: 24px;">
                    <h3 style="font-size: 15px; margin-bottom: 8px; color: var(--text);">Deskripsi Paket</h3>
                    <p style="font-size: 14.5px; color: #cbd5e1; line-height: 1.6;">{{ $paket->deskripsi }}</p>
                </div>
            @endif

            @if ($paket->petunjuk)
                <div class="panel" style="margin-bottom: 24px; border-left: 4px solid var(--accent);">
                    <h3 style="font-size: 15px; margin-bottom: 8px; color: var(--accent);">Petunjuk Pengerjaan (Ditampilkan ke Kandidat)</h3>
                    <p style="font-size: 14px; color: #e2e8f0; line-height: 1.7; white-space: pre-line;">{{ $paket->petunjuk }}</p>
                </div>
            @endif

            <!-- Daftar Soal Terurut -->
            <div class="panel">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
                    <div>
                        <h3 style="font-size: 16px; color: var(--text);">Daftar Soal & Urutan Pengerjaan</h3>
                        <div style="font-size: 13px; color: var(--text-muted);">Total: {{ $paket->soal->count() }} soal (Total skor maksimum: {{ $paket->total_skor_maksimum }} poin)</div>
                    </div>
                </div>

                <div style="display: flex; flex-direction: column; gap: 14px;">
                    @forelse ($paket->soal as $s)
                        <div style="padding: 16px; background: var(--bg-surface-alt); border: 1px solid var(--border); border-radius: var(--radius-sm);">
                            <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 8px;">
                                <div style="display: flex; align-items: center; gap: 10px;">
                                    <span style="width: 28px; height: 28px; border-radius: 50%; background: var(--accent-surface); color: var(--accent); display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 13px;">
                                        {{ $s->pivot->urutan }}
                                    </span>
                                    <div>
                                        <span style="font-family: monospace; font-size: 11px; color: var(--accent);">{{ $s->kode_soal }}</span>
                                        <h4 style="font-size: 15px; margin-top: 2px;"><a href="{{ route('soal.show', $s) }}">{{ $s->judul }}</a></h4>
                                    </div>
                                </div>
                                <span style="font-weight: 700; color: var(--accent); font-size: 13.5px;">{{ $s->skor_maksimum }} poin</span>
                            </div>

                            <p style="font-size: 13.5px; color: var(--text-secondary); margin-bottom: 10px;">
                                {{ Str::limit($s->pertanyaan, 180) }}
                            </p>

                            <div style="font-size: 12px; color: var(--text-muted); display: flex; gap: 14px;">
                                <span>Kategori: {{ ucwords(str_replace('_', ' ', $s->kategori)) }}</span>
                                <span>Kriteria Rubrik: {{ $s->rubrik->count() }}</span>
                                <span>Versi: v{{ $s->versi }}</span>
                            </div>
                        </div>
                    @empty
                        <div style="text-align: center; color: var(--text-muted); padding: 24px;">
                            Belum ada soal yang ditambahkan pada paket ini.
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <div>
            <!-- Info Panel -->
            <div class="panel" style="margin-bottom: 24px;">
                <h3 style="font-size: 15px; margin-bottom: 14px; color: var(--text);">Ringkasan Paket</h3>
                <div style="display: flex; flex-direction: column; gap: 12px; font-size: 13.5px;">
                    <div>
                        <div style="color: var(--text-muted); font-size: 12px;">Durasi Tes</div>
                        <div style="font-weight: 700; color: var(--accent); font-size: 18px;">{{ $paket->durasi_menit }} Menit</div>
                    </div>
                    <div>
                        <div style="color: var(--text-muted); font-size: 12px;">Total Skor Maksimum</div>
                        <div style="font-weight: 700; color: var(--text); font-size: 18px;">{{ $paket->total_skor_maksimum }} Poin</div>
                    </div>
                    <div>
                        <div style="color: var(--text-muted); font-size: 12px;">Status Kelayakan Paket</div>
                        @if ($paket->canBeActivated())
                            <span style="color: var(--accent); font-weight: 600;">✓ Siap ditugaskan</span>
                        @else
                            <span style="color: #facc15; font-weight: 600;">⚠ Memerlukan soal aktif & valid</span>
                        @endif
                    </div>
                    <div>
                        <div style="color: var(--text-muted); font-size: 12px;">Dibuat Oleh</div>
                        <div style="color: var(--text);">{{ $paket->user->name ?? 'HR Murialo' }}</div>
                    </div>
                </div>
            </div>

            <!-- Lowongan Terkait Panel -->
            <div class="panel">
                <h3 style="font-size: 15px; margin-bottom: 14px; color: var(--text);">Lowongan yang Terhubung</h3>
                <p style="font-size: 12.5px; color: var(--text-muted); margin-bottom: 12px;">
                    Paket ini dapat digunakan untuk berbagai lowongan rekrutmen:
                </p>
                <div style="display: flex; flex-direction: column; gap: 8px;">
                    @forelse ($paket->lowongan as $low)
                        <div style="padding: 8px 12px; background: var(--bg-surface-alt); border-radius: var(--radius-sm); font-size: 13px;">
                            <a href="{{ route('lowongan.show', $low) }}"><strong>{{ $low->judul }}</strong></a>
                            <div style="font-size: 11.5px; color: var(--text-muted);">{{ $low->perusahaan }} · {{ $low->lokasi }}</div>
                        </div>
                    @empty
                        <div style="font-size: 12.5px; color: var(--text-muted);">Belum dihubungkan ke lowongan manapun.</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
@endsection
