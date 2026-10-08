@extends('layouts.public')

@section('title', 'Jelajah Lowongan Terbuka · MURIALO')

@section('content')
<div class="py-12 bg-[#F8FAFC]">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
        
        {{-- Header & Search Banner --}}
        <div class="bg-white rounded-3xl border border-[#E2E8F0] p-6 sm:p-10 shadow-2xs space-y-6">
            <div class="max-w-2xl">
                <span class="text-xs font-bold text-[#2563EB] uppercase tracking-wider">Karier Perusahaan</span>
                <h1 class="text-3xl sm:text-4xl font-extrabold text-[#0F172A] tracking-tight mt-1">
                    Temukan Peluang Karier Terbaik
                </h1>
                <p class="text-sm text-[#64748B] mt-2 leading-relaxed">
                    Bergabung bersama tim PT Muria Logika Nusantara dan kembangkan potensimu dalam ekosistem teknologi yang kolaboratif.
                </p>
            </div>

            {{-- Filter & Search Form --}}
            <form action="{{ route('karir.index') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-12 gap-3 pt-2">
                <div class="sm:col-span-6">
                    <x-input
                        name="q"
                        value="{{ $search ?? '' }}"
                        placeholder="Cari posisi pekerjaan, keahlian, atau kata kunci..."
                        icon='<svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>'
                    />
                </div>

                <div class="sm:col-span-4">
                    <x-select name="dept">
                        <option value="">Semua Departemen</option>
                        @foreach($departemenList as $d)
                            <option value="{{ $d }}" {{ ($dept ?? '') === $d ? 'selected' : '' }}>{{ $d }}</option>
                        @endforeach
                    </x-select>
                </div>

                <div class="sm:col-span-2 flex items-end">
                    <x-button type="submit" variant="primary" size="md" class="w-full">
                        Filter
                    </x-button>
                </div>
            </form>
        </div>

        {{-- Results Counter & Job List --}}
        <div class="space-y-4">
            <div class="flex items-center justify-between text-xs text-[#64748B]">
                <span>Menampilkan <strong class="text-[#0F172A]">{{ count($lowongan) }}</strong> lowongan pekerjaan aktif</span>
                @if($search || $dept)
                    <a href="{{ route('karir.index') }}" class="text-[#2563EB] font-medium hover:underline">Reset Filter</a>
                @endif
            </div>

            @if(count($lowongan) > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($lowongan as $job)
                        <div class="p-6 rounded-2xl bg-white border border-[#E2E8F0] hover:border-[#2563EB]/40 hover:shadow-md transition-smooth flex flex-col justify-between space-y-5">
                            <div class="space-y-3">
                                <div class="flex items-center justify-between gap-2">
                                    <span class="text-[11px] font-bold text-[#2563EB] uppercase tracking-wider">{{ $job['departemen'] }}</span>
                                    <x-badge variant="success" size="sm" dot>Aktif</x-badge>
                                </div>

                                <h3 class="text-base font-bold text-[#0F172A] leading-snug hover:text-[#2563EB] transition-smooth">
                                    <a href="{{ route('karir.show', $job['id']) }}">{{ $job['judul'] }}</a>
                                </h3>

                                <p class="text-xs text-[#64748B] line-clamp-3 leading-relaxed">
                                    {{ $job['deskripsi'] }}
                                </p>

                                <div class="space-y-1.5 pt-1 text-xs text-[#475569]">
                                    <div class="flex items-center gap-1.5">
                                        <svg class="w-3.5 h-3.5 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                        <span>{{ $job['lokasi'] }}</span>
                                    </div>
                                    <div class="flex items-center gap-1.5">
                                        <svg class="w-3.5 h-3.5 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        <span class="font-semibold text-[#0F172A]">Rp {{ number_format($job['gaji_min']/1000000, 0) }} - {{ number_format($job['gaji_max']/1000000, 0) }} Juta</span>
                                    </div>
                                </div>

                                <div class="pt-2 flex flex-wrap gap-1.5">
                                    @foreach(array_slice($job['kualifikasi_wajib'], 0, 3) as $k)
                                        <span class="px-2 py-0.5 rounded text-[10px] font-medium bg-slate-100 text-slate-700">{{ $k }}</span>
                                    @endforeach
                                    @if(count($job['kualifikasi_wajib']) > 3)
                                        <span class="px-2 py-0.5 rounded text-[10px] text-slate-500">+{{ count($job['kualifikasi_wajib']) - 3 }}</span>
                                    @endif
                                </div>
                            </div>

                            <div class="pt-4 border-t border-[#E2E8F0] flex items-center justify-between">
                                <span class="text-[11px] text-[#64748B]">Kuota: {{ $job['kuota'] }} orang</span>
                                <x-button href="{{ route('karir.show', $job['id']) }}" variant="outline" size="sm">
                                    Detail Posisi
                                </x-button>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <x-empty-state
                    title="Tidak Ada Lowongan yang Sesuai"
                    description="Coba ubah kata kunci pencarian atau bersihkan filter departemen untuk menemukan lowongan lain."
                >
                    <x-button href="{{ route('karir.index') }}" variant="secondary" size="sm">
                        Tampilkan Semua Lowongan
                    </x-button>
                </x-empty-state>
            @endif
        </div>

    </div>
</div>
@endsection
