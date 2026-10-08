@extends('layouts.kandidat')

@section('title', 'Lamaran Saya · Portal Kandidat')

@section('content')
<div class="space-y-8">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-[#0F172A] tracking-tight">Riwayat Lamaran Saya</h1>
            <p class="text-xs text-[#64748B] mt-1">Daftar posisi pekerjaan yang pernah Anda lamar beserta status terkininya</p>
        </div>
        <x-button href="{{ route('kandidat.lowongan') }}" variant="primary" size="md">
            + Lamar Posisi Baru
        </x-button>
    </div>

    {{-- Application Table Card --}}
    <div class="bg-white rounded-3xl border border-[#E2E8F0] shadow-2xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-[#E2E8F0] text-[#64748B] font-bold uppercase tracking-wider text-[11px]">
                        <th class="py-4 px-6">ID & Posisi Lowongan</th>
                        <th class="py-4 px-6">Tanggal Kirim</th>
                        <th class="py-4 px-6">Tahapan Seleksi</th>
                        <th class="py-4 px-6">Status Tes Esai</th>
                        <th class="py-4 px-6 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#E2E8F0] text-[#0F172A]">
                    @foreach($applications as $app)
                        <tr class="hover:bg-slate-50/70 transition-smooth">
                            <td class="py-4 px-6">
                                <span class="text-[10px] font-bold text-slate-400 block">{{ $app['id'] }}</span>
                                <span class="font-bold text-sm text-[#0F172A] block">{{ $app['posisi'] }}</span>
                                <span class="text-[11px] text-[#64748B]">PT Muria Logika Nusantara</span>
                            </td>
                            <td class="py-4 px-6 text-[#64748B]">
                                {{ date('d M Y', strtotime($app['tanggal_lamar'])) }}
                            </td>
                            <td class="py-4 px-6">
                                <x-badge variant="primary" size="md">
                                    {{ $app['status_tahap'] }}
                                </x-badge>
                            </td>
                            <td class="py-4 px-6">
                                @if($app['status_tes'] === 'Selesai Dinilai')
                                    <x-badge variant="success" size="sm" dot>Selesai Dinilai</x-badge>
                                @elseif($app['status_tes'] === 'Menunggu Pengerjaan')
                                    <x-badge variant="warning" size="sm" dot>Tugas Tes Tersedia</x-badge>
                                @else
                                    <span class="text-[#64748B]">{{ $app['status_tes'] ?? 'Belum Ditugaskan' }}</span>
                                @endif
                            </td>
                            <td class="py-4 px-6 text-right">
                                <x-button href="{{ route('kandidat.lamaran.show', $app['id']) }}" variant="outline" size="sm">
                                    Lihat Timeline →
                                </x-button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <x-pagination :current="1" :total="1" :from="1" :to="count($applications)" :totalItems="count($applications)" />
    </div>
</div>
@endsection
