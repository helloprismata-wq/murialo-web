@extends('layouts.hr')

@section('title', 'Rekomendasi Kandidat · HR Portal')
@section('page_title', 'Insight / Rekomendasi')
@section('header_title', 'Sistem Rekomendasi Talenta (Hybrid CBF & CF)')

@section('content')
<div class="space-y-6">
    {{-- Header & Lowongan Selector --}}
    <div class="p-6 rounded-3xl bg-white border border-[#E2E8F0] shadow-2xs space-y-4">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-4 border-b border-[#E2E8F0]">
            <div>
                <h2 class="text-base font-bold text-[#0F172A]">Pilih Lowongan untuk Mendapatkan Rekomendasi</h2>
                <p class="text-xs text-[#64748B]">Sistem merekomendasikan kandidat terbaik berdasarkan pencocokan konten profil (CBF) dan pola interaksi (CF)</p>
            </div>
            <x-badge variant="purple" size="md">Rekomendasi Hybrid</x-badge>
        </div>

        <form action="{{ route('hr.rekomendasi') }}" method="GET" class="flex flex-col sm:flex-row items-center gap-3">
            <div class="flex-1 w-full">
                <x-select name="lowongan_id">
                    @foreach($lowonganList as $job)
                        <option value="{{ $job['id'] }}" {{ $selectedJobId == $job['id'] ? 'selected' : '' }}>
                            {{ $job['judul'] }} ({{ $job['departemen'] }})
                        </option>
                    @endforeach
                </x-select>
            </div>
            <x-button type="submit" variant="primary" size="md" class="w-full sm:w-auto">
                Muat Rekomendasi
            </x-button>
        </form>
    </div>

    {{-- Cold Start Alert / Information Notice --}}
    @if($recs['cf_status'] === 'cold_start')
        <x-alert variant="warning" title="Informasi Model: Kondisi Cold-Start Collaborative Filtering">
            {{ $recs['cf_message'] }}
        </x-alert>
    @endif

    {{-- Ranked Recommendation Cards Grid --}}
    <div class="space-y-4">
        <h3 class="text-base font-bold text-[#0F172A]">Peringkat Rekomendasi untuk: {{ $recs['lowongan']['judul'] }}</h3>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @foreach($recs['candidates'] as $candidate)
                <div class="p-6 rounded-3xl bg-white border border-[#E2E8F0] hover:border-[#2563EB]/40 hover:shadow-md transition-smooth flex flex-col justify-between space-y-5">
                    <div class="space-y-4">
                        {{-- Card Header --}}
                        <div class="flex items-start justify-between gap-3 pb-3 border-b border-[#E2E8F0]">
                            <div class="flex items-center gap-3">
                                <span class="w-8 h-8 rounded-xl bg-[#2563EB] text-white font-black text-sm flex items-center justify-center shadow-xs">
                                    #{{ $candidate['rank'] }}
                                </span>
                                <div>
                                    <h4 class="text-base font-bold text-[#0F172A]">{{ $candidate['nama'] }}</h4>
                                    <span class="text-xs text-[#2563EB] font-semibold">{{ $candidate['fit_level'] }}</span>
                                </div>
                            </div>
                            <div class="text-right">
                                <span class="text-2xl font-black text-[#2563EB]">{{ $candidate['skor_total'] }}%</span>
                                <span class="text-[10px] text-slate-400 block uppercase">Skor Rekomendasi</span>
                            </div>
                        </div>

                        {{-- Breakdown CBF vs CF --}}
                        <div class="grid grid-cols-2 gap-3 text-xs">
                            <div class="p-3 rounded-xl bg-slate-50 border border-slate-200">
                                <span class="text-slate-500 block text-[11px]">Content-Based (CBF)</span>
                                <strong class="text-sm font-bold text-[#0F172A]">{{ $candidate['skor_cbf'] }}%</strong>
                                <span class="text-[10px] text-slate-400 block">Kecocokan profil</span>
                            </div>
                            <div class="p-3 rounded-xl bg-slate-50 border border-slate-200">
                                <span class="text-slate-500 block text-[11px]">Collaborative (CF)</span>
                                @if($candidate['skor_cf'])
                                    <strong class="text-sm font-bold text-[#0F172A]">{{ $candidate['skor_cf'] }}%</strong>
                                @else
                                    <span class="text-xs font-semibold text-amber-600">Cold-Start (N/A)</span>
                                @endif
                                <span class="text-[10px] text-slate-400 block">Data interaksi</span>
                            </div>
                        </div>

                        {{-- Recommendation Reason --}}
                        <div class="p-4 rounded-xl bg-slate-50 border border-[#E2E8F0] space-y-1 text-xs">
                            <strong class="text-[#0F172A] block font-bold">Rasional Rekomendasi:</strong>
                            <p class="text-[#475569] leading-relaxed">
                                {{ $candidate['alasan'] }}
                            </p>
                        </div>
                    </div>

                    {{-- Action Footer --}}
                    <div class="pt-3 border-t border-[#E2E8F0] flex items-center justify-between">
                        <span class="text-[11px] text-slate-400">ID: APP-{{ $candidate['kandidat_id'] }}</span>
                        <x-button href="{{ route('hr.pelamar.show', $candidate['kandidat_id']) }}" variant="secondary" size="sm">
                            Detail Pelamar →
                        </x-button>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
