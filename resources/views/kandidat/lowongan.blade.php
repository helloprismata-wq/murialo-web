@extends('layouts.kandidat')

@section('title', 'Jelajah Lowongan · Portal Kandidat')

@section('content')
<div class="space-y-8">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-[#0F172A] tracking-tight">Katalog Lowongan Pekerjaan</h1>
            <p class="text-xs text-[#64748B] mt-1">Temukan posisi yang sesuai dengan keahlian dan minat karier Anda</p>
        </div>
        <x-button href="{{ route('kandidat.lamaran.index') }}" variant="secondary" size="md">
            Lihat Status Lamaran Saya
        </x-button>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($lowongan as $job)
            <div class="p-6 rounded-2xl bg-white border border-[#E2E8F0] hover:border-[#2563EB]/40 hover:shadow-md transition-smooth flex flex-col justify-between space-y-4">
                <div class="space-y-3">
                    <div class="flex items-center justify-between gap-2">
                        <span class="text-[11px] font-bold text-[#2563EB] uppercase tracking-wider">{{ $job['departemen'] }}</span>
                        <x-badge variant="success" size="sm" dot>Aktif</x-badge>
                    </div>

                    <h3 class="text-base font-bold text-[#0F172A] hover:text-[#2563EB] transition-smooth">
                        <a href="{{ route('karir.show', $job['id']) }}">{{ $job['judul'] }}</a>
                    </h3>

                    <p class="text-xs text-[#64748B] line-clamp-3 leading-relaxed">
                        {{ $job['deskripsi'] }}
                    </p>

                    <div class="text-xs text-[#475569] space-y-1 pt-1">
                        <p>📍 {{ $job['lokasi'] }} · {{ $job['tipe_pekerjaan'] }}</p>
                        <p class="font-bold text-[#0F172A]">Rp {{ number_format($job['gaji_min']/1000000, 0) }} - {{ number_format($job['gaji_max']/1000000, 0) }} Juta</p>
                    </div>
                </div>

                <div class="pt-4 border-t border-[#E2E8F0] flex items-center justify-between">
                    <span class="text-[11px] text-[#64748B]">Batas: {{ date('d M Y', strtotime($job['deadline'])) }}</span>
                    <x-button href="{{ route('karir.show', $job['id']) }}" variant="outline" size="sm">
                        Lamar Posisi
                    </x-button>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection
