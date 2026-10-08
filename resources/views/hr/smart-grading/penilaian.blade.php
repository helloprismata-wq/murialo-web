@extends('layouts.hr')

@section('title', 'Penilaian Tes: ' . $candidate['nama'] . ' · HR Portal')
@section('page_title', 'Smart Grading / Penilaian')
@section('header_title', 'Evaluasi Rubrik & Verifikasi Nilai Esai')

@section('content')
<div class="space-y-8" x-data="{ published: false }">
    {{-- Header Reviewer Info Card --}}
    <div class="bg-white rounded-3xl border border-[#E2E8F0] p-6 sm:p-8 shadow-2xs space-y-6">
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6 pb-6 border-b border-[#E2E8F0]">
            <div>
                <div class="flex items-center gap-2 mb-1">
                    <a href="{{ route('hr.smart_grading.index') }}" class="text-xs text-[#2563EB] hover:underline">← Kembali ke Smart Grading</a>
                    <span class="text-xs text-slate-400">/</span>
                    <span class="text-xs text-[#64748B]">Lembar Penilaian</span>
                </div>
                <h1 class="text-xl sm:text-2xl font-extrabold text-[#0F172A] tracking-tight">
                    Penilaian Jawaban: {{ $candidate['nama'] }}
                </h1>
                <p class="text-xs text-[#64748B] mt-0.5">
                    Paket A: Pemahaman Informasi & Prosedur · Posisi: {{ $candidate['posisi_dilamar'] }}
                </p>
            </div>

            <div class="flex items-center gap-4">
                {{-- Score Summary Boxes --}}
                <div class="p-3 rounded-xl bg-blue-50 border border-blue-200 text-center min-w-[120px]">
                    <span class="text-[10px] font-bold text-[#1E40AF] uppercase block">Prediksi AI</span>
                    <span class="text-xl font-black text-[#2563EB]">92.5 / 100</span>
                </div>
                <div class="p-3 rounded-xl bg-emerald-50 border border-emerald-200 text-center min-w-[120px]">
                    <span class="text-[10px] font-bold text-emerald-800 uppercase block">Nilai Manual HR</span>
                    <span class="text-xl font-black text-emerald-700">92.0 / 100</span>
                </div>
            </div>
        </div>

        <div class="flex flex-col sm:flex-row items-center justify-between gap-4 text-xs">
            <span class="text-[#64748B]">
                Waktu pengerjaan: <strong>22 menit</strong> · Status: <span class="text-emerald-600 font-bold">Terverifikasi Reviewer</span>
            </span>
            <div class="flex items-center gap-2">
                <x-button type="button" @click="published = true; $dispatch('show-toast', { message: 'Nilai akhir resmi dipublikasikan ke portal kandidat!', type: 'success' })" variant="primary" size="md">
                    Publikasikan Hasil ke Kandidat ✓
                </x-button>
            </div>
        </div>
    </div>

    {{-- Question Item Breakdown --}}
    <div class="space-y-6">
        @foreach($items as $idx => $item)
            <div class="bg-white rounded-3xl border border-[#E2E8F0] shadow-2xs p-6 sm:p-8 space-y-6">
                {{-- Item Header --}}
                <div class="flex items-center justify-between pb-3 border-b border-[#E2E8F0]">
                    <div class="flex items-center gap-3">
                        <span class="w-7 h-7 rounded-lg bg-[#2563EB] text-white font-bold flex items-center justify-center text-xs">
                            {{ $idx + 1 }}
                        </span>
                        <h3 class="text-sm font-bold text-[#0F172A]">{{ $item['judul'] }}</h3>
                    </div>
                    <div class="flex items-center gap-2 text-xs">
                        <span class="text-slate-500">Prediksi AI: <strong class="text-[#2563EB]">{{ $item['skor_ai_prediksi'] }}</strong> / {{ $item['skor_maks'] }}</span>
                    </div>
                </div>

                {{-- Teks Bacaan & Pertanyaan --}}
                <div class="p-4 rounded-xl bg-slate-50 border border-slate-200 text-xs text-[#334155] leading-relaxed">
                    <span class="font-bold text-[#0F172A] block mb-1">Teks Skenario Soal:</span>
                    <p>{{ $item['teks'] }}</p>
                    <div class="mt-2 pt-2 border-t border-slate-200 font-semibold text-[#0F172A]">
                        Pertanyaan: {{ $item['pertanyaan'] }}
                    </div>
                </div>

                {{-- Jawaban Kandidat vs Jawaban Acuan (Berdampingan) --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-xs">
                    {{-- Jawaban Kandidat --}}
                    <div class="p-4 rounded-2xl bg-white border border-[#CBD5E1] space-y-2">
                        <div class="flex justify-between font-bold text-[#0F172A]">
                            <span>Jawaban Kandidat:</span>
                            <x-badge variant="neutral" size="sm">Teks Asli</x-badge>
                        </div>
                        <p class="text-[#0F172A] leading-relaxed font-medium bg-slate-50 p-3 rounded-xl border border-slate-200">
                            "{{ $item['jawaban_kandidat'] }}"
                        </p>
                    </div>

                    {{-- Jawaban Acuan Internal HR --}}
                    <div class="p-4 rounded-2xl bg-blue-50/70 border border-blue-200 space-y-2">
                        <div class="flex justify-between font-bold text-[#1E40AF]">
                            <span>Jawaban Acuan (Internal HR):</span>
                            <x-badge variant="primary" size="sm">Kunci Standar</x-badge>
                        </div>
                        <p class="text-[#1E3A8A] leading-relaxed bg-white p-3 rounded-xl border border-blue-100">
                            "{{ $item['jawaban_acuan'] }}"
                        </p>
                    </div>
                </div>

                {{-- Rubrik Penilaian Manual HR --}}
                <div class="p-4 rounded-2xl bg-slate-50 border border-[#E2E8F0] space-y-3">
                    <h4 class="text-xs font-bold text-[#0F172A] uppercase tracking-wider">Verifikasi Rubrik Penilaian Manual (HR Reviewer):</h4>
                    <div class="space-y-2">
                        @foreach($item['rubrik'] as $r)
                            <div class="p-3 rounded-xl bg-white border border-[#E2E8F0] flex flex-col sm:flex-row sm:items-center justify-between gap-3 text-xs">
                                <div class="space-y-0.5">
                                    <p class="font-bold text-[#0F172A]">{{ $r['kriteria'] }}</p>
                                    <p class="text-[11px] text-[#64748B]">{{ $r['deskripsi'] }}</p>
                                </div>
                                <div class="flex items-center gap-2 shrink-0">
                                    <span class="text-slate-500 text-[11px]">Skor (Maks {{ $r['poin_maks'] }}):</span>
                                    <input
                                        type="number"
                                        value="{{ $r['skor_diberikan'] }}"
                                        max="{{ $r['poin_maks'] }}"
                                        min="0"
                                        class="w-16 rounded-lg border border-[#CBD5E1] p-1.5 text-center font-bold text-xs text-[#0F172A] focus:border-[#2563EB] outline-none"
                                    />
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    {{-- Reviewer General Notes --}}
    <x-card title="Catatan & Kesimpulan Evaluasi Reviewer">
        <div class="space-y-4">
            <x-textarea
                name="catatan_umum"
                rows="3"
                value="Pemahaman teks dan instruksi sangat baik dan runut. Memenuhi kriteria standar operasional kerja PT Muria Logika Nusantara."
            />
            <div class="flex items-center justify-end gap-3 pt-2">
                <x-button type="button" @click="$dispatch('show-toast', { message: 'Draf penilaian disimpan ke sistem', type: 'info' })" variant="secondary" size="md">
                    Simpan Draf
                </x-button>
                <x-button type="button" @click="$dispatch('show-toast', { message: 'Hasil penilaian resmi disahkan!', type: 'success' })" variant="primary" size="md">
                    Sahkan Nilai Akhir
                </x-button>
            </div>
        </div>
    </x-card>
</div>
@endsection
