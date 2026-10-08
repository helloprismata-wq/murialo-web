@extends('layouts.hr')

@section('title', 'Kelola Lowongan · HR Portal')
@section('page_title', 'Lowongan')
@section('header_title', 'Pengelolaan Lowongan Pekerjaan')

@section('content')
<div class="space-y-6">
    {{-- Header Action Bar --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <p class="text-xs text-[#64748B]">Kelola status publikasi, kualifikasi, kuota, dan pelamar posisi pekerjaan</p>
        </div>
        <x-button href="{{ route('hr.lowongan.create') }}" variant="primary" size="md">
            + Tambah Lowongan Baru
        </x-button>
    </div>

    {{-- Lowongan Table Card --}}
    <div class="bg-white rounded-3xl border border-[#E2E8F0] shadow-2xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-[#E2E8F0] text-[#64748B] font-bold uppercase tracking-wider text-[11px]">
                        <th class="py-4 px-6">Posisi Lowongan</th>
                        <th class="py-4 px-6">Departemen & Tipe</th>
                        <th class="py-4 px-6">Pelamar Terdata</th>
                        <th class="py-4 px-6">Batas Lamaran</th>
                        <th class="py-4 px-6">Status Publikasi</th>
                        <th class="py-4 px-6 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#E2E8F0] text-[#0F172A]">
                    @foreach($lowongan as $job)
                        <tr class="hover:bg-slate-50/70 transition-smooth">
                            <td class="py-4 px-6">
                                <a href="{{ route('karir.show', $job['id']) }}" class="font-bold text-sm text-[#0F172A] hover:text-[#2563EB] block">
                                    {{ $job['judul'] }}
                                </a>
                                <span class="text-[11px] text-[#64748B]">Rp {{ number_format($job['gaji_min']/1000000, 0) }} - {{ number_format($job['gaji_max']/1000000, 0) }} Juta</span>
                            </td>
                            <td class="py-4 px-6">
                                <span class="font-semibold text-[#0F172A] block">{{ $job['departemen'] }}</span>
                                <span class="text-[11px] text-[#64748B]">{{ $job['tipe_pekerjaan'] }} · {{ $job['lokasi'] }}</span>
                            </td>
                            <td class="py-4 px-6">
                                <span class="font-bold text-[#2563EB] text-sm">{{ $job['jumlah_pelamar'] }}</span>
                                <span class="text-[11px] text-[#64748B] block">Kuota: {{ $job['kuota'] }} orang</span>
                            </td>
                            <td class="py-4 px-6 text-[#64748B]">
                                {{ date('d M Y', strtotime($job['deadline'])) }}
                            </td>
                            <td class="py-4 px-6">
                                @if($job['status'] === 'aktif')
                                    <x-badge variant="success" size="sm" dot>Dipublikasikan</x-badge>
                                @else
                                    <x-badge variant="neutral" size="sm" dot>Draf</x-badge>
                                @endif
                            </td>
                            <td class="py-4 px-6 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('hr.skill_matching', ['lowongan_id' => $job['id']]) }}" class="p-1.5 rounded-lg text-slate-500 hover:text-blue-600 hover:bg-slate-100" title="Automated Skill Matching">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                                    </a>
                                    <a href="{{ route('hr.lowongan.edit', $job['id']) }}" class="p-1.5 rounded-lg text-slate-500 hover:text-[#0F172A] hover:bg-slate-100" title="Edit">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <x-pagination :current="1" :total="1" :from="1" :to="count($lowongan)" :totalItems="count($lowongan)" />
    </div>
</div>
@endsection
