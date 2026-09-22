@extends('lowongan.layout')

@section('title', 'Penugasan Tes')

@section('content')
    <div class="heading">
        <div>
            <div class="subtitle">Smart Grading / Penugasan Tes</div>
            <h1>Penugasan Tes ke Kandidat</h1>
        </div>
        <a class="button button-primary" href="{{ route('penugasan.create') }}">+ Berikan Tes ke Kandidat</a>
    </div>

    <form method="get" class="filters">
        <div>
            <input type="search" name="q" value="{{ request('q') }}" placeholder="Cari nama kandidat, email, lowongan..." aria-label="Cari penugasan">
        </div>
        <div>
            <select name="status_pengerjaan" aria-label="Filter status pengerjaan">
                <option value="">Semua Status Pengerjaan</option>
                <option value="belum_dimulai" @selected(request('status_pengerjaan') === 'belum_dimulai')>Belum Dimulai</option>
                <option value="sedang_mengerjakan" @selected(request('status_pengerjaan') === 'sedang_mengerjakan')>Sedang Mengerjakan</option>
                <option value="selesai" @selected(request('status_pengerjaan') === 'selesai')>Selesai</option>
                <option value="kedaluwarsa" @selected(request('status_pengerjaan') === 'kedaluwarsa')>Kedaluwarsa</option>
            </select>
        </div>
        <div>
            <select name="status_penilaian" aria-label="Filter status penilaian">
                <option value="">Semua Status Penilaian</option>
                <option value="belum_dinilai" @selected(request('status_penilaian') === 'belum_dinilai')>Belum Dinilai</option>
                <option value="sedang_dinilai" @selected(request('status_penilaian') === 'sedang_dinilai')>Sedang Dinilai</option>
                <option value="selesai_dinilai" @selected(request('status_penilaian') === 'selesai_dinilai')>Selesai Dinilai</option>
            </select>
        </div>
        <button type="submit" class="button button-secondary">Filter</button>
        @if (request()->hasAny(['q', 'status_pengerjaan', 'status_penilaian']))
            <a href="{{ route('penugasan.index') }}" class="button button-secondary">Reset</a>
        @endif
    </form>

    @if ($penugasanList->isEmpty())
        <div class="empty">
            <div class="icon">📋</div>
            <h3>Belum ada penugasan tes</h3>
            <p>Tugaskan paket tes aktif kepada pelamar yang telah mengajukan lamaran pekerjaan.</p>
            <div style="margin-top: 18px;">
                <a class="button button-primary" href="{{ route('penugasan.create') }}">+ Buat Penugasan Baru</a>
            </div>
        </div>
    @else
        <div class="cards">
            @foreach ($penugasanList as $item)
                <div class="card job">
                    <div class="heading">
                        <div>
                            <div style="display: flex; gap: 8px; align-items: center; margin-bottom: 6px;">
                                <span class="badge badge-{{ $item->status_pengerjaan === 'selesai' ? 'aktif' : ($item->status_pengerjaan === 'sedang_mengerjakan' ? 'draft' : 'ditutup') }}">
                                    Pengerjaan: {{ ucwords(str_replace('_', ' ', $item->status_pengerjaan)) }}
                                </span>
                                <span class="badge badge-{{ $item->status_penilaian === 'selesai_dinilai' ? 'aktif' : ($item->status_penilaian === 'sedang_dinilai' ? 'draft' : 'ditutup') }}">
                                    Penilaian: {{ ucwords(str_replace('_', ' ', $item->status_penilaian)) }}
                                </span>
                                @if ($item->is_published)
                                    <span style="font-size: 11px; font-weight: 600; color: #34d399; background: rgba(52, 211, 153, 0.1); padding: 2px 8px; border-radius: var(--radius-pill);">
                                        ✓ Dipublikasikan
                                    </span>
                                @endif
                            </div>
                            <h2 style="font-size: 18px;">
                                <a href="{{ route('penugasan.show', $item) }}">
                                    {{ $item->kandidat->name }} — {{ $item->paket_snapshot['nama'] ?? 'Paket Tes' }}
                                </a>
                            </h2>
                        </div>
                        <div class="meta" style="font-size: 13px;">
                            <span>Lowongan: <strong>{{ $item->lamaran->lowongan->judul ?? '-' }}</strong></span>
                        </div>
                    </div>

                    <div class="meta" style="margin: 12px 0; font-size: 12.5px;">
                        <span>Kandidat: <strong>{{ $item->kandidat->email }}</strong></span>
                        <span>Mulai: {{ $item->waktu_tersedia->format('d M Y, H:i') }}</span>
                        <span>Batas Akhir: {{ $item->batas_waktu->format('d M Y, H:i') }}</span>
                    </div>

                    <div class="actions" style="margin-top: 16px;">
                        <a class="button button-secondary" href="{{ route('penugasan.show', $item) }}">Lihat Penugasan</a>
                        @if ($item->status_pengerjaan === 'selesai')
                            <a class="button button-primary" href="{{ route('penilaian.show', $item) }}">
                                {{ $item->status_penilaian === 'selesai_dinilai' ? 'Lihat Hasil Nilai' : 'Periksa & Nilai' }}
                            </a>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

        <div class="pagination">
            {{ $penugasanList->links('pagination::simple-default') }}
        </div>
    @endif
@endsection
