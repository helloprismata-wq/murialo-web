@extends('layouts.hr')

@section('title', 'Pipeline Seleksi · HR Portal')
@section('page_title', 'Pipeline')
@section('header_title', 'Papan Pipeline Seleksi Kandidat')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <p class="text-xs text-[#64748B]">Pantau distribusi tahapan kandidat secara visual dari screening awal hingga penawaran kerja</p>
        </div>
        <div class="flex items-center gap-2">
            <x-badge variant="neutral" size="md">Total 15 Pelamar Terdata</x-badge>
        </div>
    </div>

    {{-- Kanban Board Grid (5 Columns) --}}
    <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-5 gap-4 items-start">
        @foreach($columns as $colName => $items)
            <div class="bg-slate-100/90 rounded-2xl p-4 border border-[#E2E8F0] space-y-3 min-h-[500px] flex flex-col justify-between">
                <div class="space-y-3">
                    {{-- Column Header --}}
                    <div class="flex items-center justify-between pb-2 border-b border-slate-200">
                        <span class="text-xs font-bold text-[#0F172A] truncate" title="{{ $colName }}">{{ $colName }}</span>
                        <span class="px-2 py-0.5 rounded-full bg-white text-xs font-bold text-[#2563EB] border border-slate-200 shadow-2xs">
                            {{ count($items) }}
                        </span>
                    </div>

                    {{-- Candidate Cards in Column --}}
                    <div class="space-y-3">
                        @forelse($items as $candidate)
                            <div class="p-3.5 rounded-xl bg-white border border-[#E2E8F0] shadow-2xs hover:shadow-md hover:border-[#2563EB]/40 transition-smooth space-y-2.5">
                                <div class="flex items-start justify-between gap-2">
                                    <div>
                                        <a href="{{ route('hr.pelamar.show', $candidate['id']) }}" class="text-xs font-bold text-[#0F172A] hover:text-[#2563EB] block">
                                            {{ $candidate['nama'] }}
                                        </a>
                                        <span class="text-[10px] text-[#64748B] block truncate max-w-[140px]">{{ $candidate['posisi_dilamar'] }}</span>
                                    </div>
                                    <span class="text-xs font-extrabold text-[#2563EB] shrink-0">{{ $candidate['skor_matching'] }}%</span>
                                </div>

                                <div class="flex items-center justify-between text-[11px] pt-1 border-t border-slate-100">
                                    <span class="text-slate-500">{{ $candidate['pengalaman_tahun'] }} Thn Pengalaman</span>
                                    @if($candidate['anomali_detected'])
                                        <span class="text-amber-600 font-bold" title="Temuan anomali">⚠ Anomali</span>
                                    @endif
                                </div>

                                {{-- Quick Action Buttons --}}
                                <div class="flex items-center justify-between gap-1 pt-1">
                                    <a href="{{ route('hr.pelamar.show', $candidate['id']) }}" class="text-[10px] font-semibold text-[#2563EB] hover:underline">
                                        Detail
                                    </a>
                                    <button
                                        type="button"
                                        @click="$dispatch('show-toast', { message: 'Kandidat {{ $candidate['nama'] }} dipindahkan tahapan (Simulasi)', type: 'success' })"
                                        class="px-2 py-0.5 rounded bg-slate-50 hover:bg-slate-200 text-[10px] font-semibold text-[#0F172A] border border-slate-200 transition-smooth"
                                    >
                                        Pindah →
                                    </button>
                                </div>
                            </div>
                        @empty
                            <div class="p-4 rounded-xl border border-dashed border-slate-300 text-center text-xs text-slate-400">
                                Tidak ada kandidat di tahap ini
                            </div>
                        @endforelse
                    </div>
                </div>

                {{-- Column Footer --}}
                <div class="pt-2 text-center text-[10px] text-slate-400 border-t border-slate-200">
                    {{ count($items) }} kandidat aktif
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection
