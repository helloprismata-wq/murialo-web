@extends('lowongan.layout')

@section('title', 'Paket Tes')

@section('content')
    <div class="heading">
        <div>
            <div class="subtitle">Smart Grading / Paket Tes</div>
            <h1>Paket Tes Jawaban Singkat</h1>
        </div>
        <a class="button button-primary" href="{{ route('paket.create') }}">+ Buat Paket Tes</a>
    </div>

    <form method="get" class="filters">
        <div>
            <input type="search" name="q" value="{{ request('q') }}" placeholder="Cari nama paket atau deskripsi..." aria-label="Cari paket">
        </div>
        <div>
            <select name="status" aria-label="Filter status">
                <option value="">Semua Status</option>
                <option value="aktif" @selected(request('status') === 'aktif')>Aktif</option>
                <option value="draft" @selected(request('status') === 'draft')>Draft</option>
                <option value="arsip" @selected(request('status') === 'arsip')>Arsip</option>
            </select>
        </div>
        <button type="submit" class="button button-secondary">Filter</button>
        @if (request()->hasAny(['q', 'status']))
            <a href="{{ route('paket.index') }}" class="button button-secondary">Reset</a>
        @endif
    </form>

    @if ($paketList->isEmpty())
        <div class="empty">
            <div class="icon">📦</div>
            <h3>Belum ada paket tes</h3>
            <p>Gabungkan beberapa soal dari Bank Soal ke dalam satu paket tes dengan durasi dan petunjuk pengerjaan.</p>
            <div style="margin-top: 18px;">
                <a class="button button-primary" href="{{ route('paket.create') }}">+ Buat Paket Pertama</a>
            </div>
        </div>
    @else
        <div class="cards">
            @foreach ($paketList as $item)
                <div class="card job">
                    <div class="heading">
                        <div>
                            <div style="display: flex; gap: 8px; align-items: center; margin-bottom: 6px;">
                                <span class="badge badge-{{ $item->status }}">{{ ucfirst($item->status) }}</span>
                                <span style="font-size: 12px; color: var(--text-muted);">Durasi: <strong>{{ $item->durasi_menit }} Menit</strong></span>
                            </div>
                            <h2 style="font-size: 18px;"><a href="{{ route('paket.show', $item) }}">{{ $item->nama }}</a></h2>
                        </div>
                        <div class="meta" style="font-size: 13px;">
                            <span>Total Skor: <strong>{{ $item->total_skor_maksimum }} poin</strong></span>
                        </div>
                    </div>

                    @if ($item->deskripsi)
                        <div style="font-size: 13.5px; color: var(--text-secondary); margin: 8px 0;">
                            {{ Str::limit($item->deskripsi, 140) }}
                        </div>
                    @endif

                    <div class="meta" style="margin-top: 14px; font-size: 12.5px;">
                        <span>Jumlah Soal: <strong>{{ $item->soal_count }} butir</strong></span>
                        <span>Lowongan Terkait: {{ $item->lowongan()->count() }}</span>
                        <span>Penugasan: {{ $item->penugasanTes()->count() }} kandidat</span>
                    </div>

                    <div class="actions" style="margin-top: 16px;">
                        <a class="button button-secondary" href="{{ route('paket.show', $item) }}">Lihat Detail Paket</a>
                        <a class="button button-secondary" href="{{ route('paket.edit', $item) }}">Edit Paket</a>
                        <form action="{{ route('paket.destroy', $item) }}" method="post" style="display:inline;" onsubmit="return confirm('Hapus atau arsipkan paket tes ini?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="button button-danger" style="background: transparent; border-color: var(--border);">Hapus</button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="pagination">
            {{ $paketList->links('pagination::simple-default') }}
        </div>
    @endif
@endsection
