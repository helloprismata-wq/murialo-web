@extends('layouts.hr')

@section('title', 'Daftar Pelamar · HR Portal')
@section('page_title', 'Pelamar')
@section('header_title', 'Manajemen Pelamar & Screening')

@section('content')
<div class="space-y-6">
    {{-- Search & Filter Controls --}}
    <div class="p-6 rounded-3xl bg-white border border-[#E2E8F0] shadow-2xs space-y-4">
        <form action="{{ route('hr.pelamar.index') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-12 gap-3">
            <div class="sm:col-span-5">
                <x-input
                    name="q"
                    value="{{ $search ?? '' }}"
                    placeholder="Cari nama kandidat, email, atau posisi..."
                    icon='<svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>'
                />
            </div>
            <div class="sm:col-span-4">
                <x-select name="status">
                    <option value="">Semua Tahapan Seleksi</option>
                    <option value="Review CV" {{ ($status ?? '') === 'Review CV' ? 'selected' : '' }}>Review CV</option>
                    <option value="Tes Esai" {{ ($status ?? '') === 'Tes Esai' ? 'selected' : '' }}>Tes Esai</option>
                    <option value="Wawancara HR" {{ ($status ?? '') === 'Wawancara HR' ? 'selected' : '' }}>Wawancara HR</option>
                    <option value="Wawancara User" {{ ($status ?? '') === 'Wawancara User' ? 'selected' : '' }}>Wawancara User</option>
                    <option value="Offering" {{ ($status ?? '') === 'Offering' ? 'selected' : '' }}>Offering</option>
                    <option value="Diterima" {{ ($status ?? '') === 'Diterima' ? 'selected' : '' }}>Diterima</option>
                </x-select>
            </div>
            <div class="sm:col-span-3 flex items-end gap-2">
                <x-button type="submit" variant="primary" size="md" class="w-full">
                    Terapkan Filter
                </x-button>
                @if($search || $status)
                    <x-button href="{{ route('hr.pelamar.index') }}" variant="secondary" size="md">
                        Reset
                    </x-button>
                @endif
            </div>
        </form>
    </div>

    {{-- Applicant List Table --}}
    <div class="bg-white rounded-3xl border border-[#E2E8F0] shadow-2xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-[#E2E8F0] text-[#64748B] font-bold uppercase tracking-wider text-[11px]">
                        <th class="py-4 px-6">Kandidat</th>
                        <th class="py-4 px-6">Posisi Dilamar</th>
                        <th class="py-4 px-6">Kesesuaian Skill (AI)</th>
                        <th class="py-4 px-6">Tahapan Seleksi</th>
                        <th class="py-4 px-6">Status Tes Esai</th>
                        <th class="py-4 px-6">Tanggal Masuk</th>
                        <th class="py-4 px-6 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#E2E8F0] text-[#0F172A]">
                    @forelse($candidates as $c)
                        <tr class="hover:bg-slate-50/70 transition-smooth">
                            <td class="py-4 px-6">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-xl bg-[#EFF6FF] text-[#2563EB] font-bold flex items-center justify-center shrink-0 text-xs border border-blue-200">
                                        {{ substr($c['nama'], 0, 1) }}
                                    </div>
                                    <div>
                                        <a href="{{ route('hr.pelamar.show', $c['id']) }}" class="font-bold text-sm text-[#0F172A] hover:text-[#2563EB]">
                                            {{ $c['nama'] }}
                                        </a>
                                        <p class="text-[11px] text-[#64748B]">{{ $c['email'] }} · {{ $c['pengalaman_tahun'] }} Thn Pengalaman</p>
                                    </div>
                                </div>
                            </td>
                            <td class="py-4 px-6 font-medium text-[#334155]">
                                {{ $c['posisi_dilamar'] }}
                            </td>
                            <td class="py-4 px-6">
                                <div class="flex items-center gap-2">
                                    <span class="font-bold text-sm text-[#2563EB]">{{ $c['skor_matching'] }}%</span>
                                    <div class="w-16 bg-slate-200 h-1.5 rounded-full overflow-hidden">
                                        <div class="bg-[#2563EB] h-full rounded-full" style="width: {{ $c['skor_matching'] }}%"></div>
                                    </div>
                                </div>
                                <span class="text-[10px] text-slate-400">Sentence-BERT</span>
                            </td>
                            <td class="py-4 px-6">
                                <x-badge variant="primary" size="md">
                                    {{ $c['status_pipeline'] }}
                                </x-badge>
                            </td>
                            <td class="py-4 px-6">
                                @if($c['status_tes'] === 'Selesai Dinilai')
                                    <span class="text-xs font-bold text-emerald-600">✓ {{ $c['skor_tes'] }}</span>
                                @elseif($c['status_tes'] === 'Menunggu Review HR')
                                    <x-badge variant="warning" size="sm" dot>Review HR</x-badge>
                                @else
                                    <span class="text-slate-500">{{ $c['status_tes'] }}</span>
                                @endif
                            </td>
                            <td class="py-4 px-6 text-[#64748B]">
                                {{ date('d M Y', strtotime($c['tanggal_lamar'])) }}
                            </td>
                            <td class="py-4 px-6 text-right">
                                <x-button href="{{ route('hr.pelamar.show', $c['id']) }}" variant="secondary" size="sm">
                                    Detail Pelamar →
                                </x-button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-8 text-center text-slate-400">
                                Tidak ada data pelamar yang cocok dengan kriteria filter.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <x-pagination :current="1" :total="1" :from="1" :to="count($candidates)" :totalItems="count($candidates)" />
    </div>
</div>
@endsection
