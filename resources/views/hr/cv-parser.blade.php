@extends('layouts.hr')

@section('title', 'Resume Parser AI · HR Portal')
@section('page_title', 'Evaluasi / CV Parser')
@section('header_title', 'Ekstraksi Otomatis Dokumen CV (FastAPI Engine)')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <p class="text-xs text-[#64748B]">Ekstraksi teks, entitas named-entity recognition (NER), dan keahlian dari berkas PDF pelamar</p>
        </div>
        <div class="flex items-center gap-2">
            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-emerald-50 text-emerald-700 border border-emerald-200 text-xs font-semibold">
                <span class="w-2 h-2 rounded-full bg-emerald-500"></span> FastAPI Engine Port 8001 Aktif (Simulasi)
            </span>
        </div>
    </div>

    {{-- Parser KPI Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="p-5 rounded-2xl bg-white border border-[#E2E8F0] shadow-2xs space-y-1">
            <span class="text-xs text-[#64748B]">Total Dokumen Diproses</span>
            <p class="text-2xl font-black text-[#0F172A]">{{ count($candidates) }} CV</p>
            <p class="text-[11px] text-emerald-600 font-semibold">100% Format Terbaca (PDF)</p>
        </div>
        <div class="p-5 rounded-2xl bg-white border border-[#E2E8F0] shadow-2xs space-y-1">
            <span class="text-xs text-[#64748B]">Rata-rata Waktu Ekstraksi</span>
            <p class="text-2xl font-black text-[#2563EB]">1.2 Detik</p>
            <p class="text-[11px] text-[#64748B]">OCR & Tokenisasi Teks</p>
        </div>
        <div class="p-5 rounded-2xl bg-white border border-[#E2E8F0] shadow-2xs space-y-1">
            <span class="text-xs text-[#64748B]">Akurasi Pemetaan Skill</span>
            <p class="text-2xl font-black text-emerald-600">94.8%</p>
            <p class="text-[11px] text-[#64748B]">Tervalidasi Kamus Standar</p>
        </div>
    </div>

    {{-- Applicant CV Parsing Table --}}
    <div class="bg-white rounded-3xl border border-[#E2E8F0] shadow-2xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-[#E2E8F0] text-[#64748B] font-bold uppercase tracking-wider text-[11px]">
                        <th class="py-4 px-6">Pelamar & Dokumen</th>
                        <th class="py-4 px-6">Pendidikan Terurai</th>
                        <th class="py-4 px-6">Pengalaman Terdeteksi</th>
                        <th class="py-4 px-6">Skill Terekstrak</th>
                        <th class="py-4 px-6">Status Parser</th>
                        <th class="py-4 px-6 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#E2E8F0] text-[#0F172A]">
                    @foreach($candidates as $c)
                        <tr class="hover:bg-slate-50/70 transition-smooth">
                            <td class="py-4 px-6">
                                <span class="font-bold text-sm text-[#0F172A] block">{{ $c['nama'] }}</span>
                                <span class="text-[11px] text-slate-500 font-mono">{{ $c['cv_filename'] }}</span>
                            </td>
                            <td class="py-4 px-6 text-[#334155]">
                                {{ $c['pendidikan_terakhir'] }}
                            </td>
                            <td class="py-4 px-6">
                                <span class="font-bold text-[#0F172A]">{{ $c['pengalaman_tahun'] }} Tahun</span>
                                <span class="text-[11px] text-[#64748B] block">{{ $c['posisi_dilamar'] }}</span>
                            </td>
                            <td class="py-4 px-6 max-w-xs">
                                <div class="flex flex-wrap gap-1">
                                    @foreach(array_slice($c['skills'], 0, 3) as $s)
                                        <span class="px-2 py-0.5 rounded text-[10px] bg-slate-100 text-slate-700 font-medium">{{ $s }}</span>
                                    @endforeach
                                    @if(count($c['skills']) > 3)
                                        <span class="px-1.5 py-0.5 rounded text-[10px] text-slate-500 font-semibold">+{{ count($c['skills']) - 3 }}</span>
                                    @endif
                                </div>
                            </td>
                            <td class="py-4 px-6">
                                <x-badge variant="success" size="sm" dot>Berhasil Diparse</x-badge>
                            </td>
                            <td class="py-4 px-6 text-right">
                                <x-button href="{{ route('hr.pelamar.show', $c['id']) }}" variant="secondary" size="sm">
                                    Pratinjau Hasil
                                </x-button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
