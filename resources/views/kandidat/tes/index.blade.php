@extends('layouts.kandidat')

@section('title', 'Tugas Tes Esai · Portal Kandidat')

@section('content')
<div class="space-y-8">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-[#0F172A] tracking-tight">Daftar Tugas Tes Esai</h1>
            <p class="text-xs text-[#64748B] mt-1">Asesmen penalaran tertulis dan pemahaman instruksi kerja untuk posisi yang dilamar</p>
        </div>
        <x-badge variant="primary" size="md">1 Tes Aktif</x-badge>
    </div>

    {{-- Active Test Cards --}}
    <div class="space-y-4">
        <h2 class="text-sm font-bold text-[#0F172A]">Tugas yang Tersedia untuk Dikerjakan</h2>

        <div class="p-6 rounded-3xl bg-white border border-[#E2E8F0] shadow-2xs hover:border-[#2563EB]/40 transition-smooth space-y-5">
            <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-4">
                <div class="space-y-2">
                    <div class="flex items-center gap-2">
                        <x-badge variant="warning" size="sm" dot>Siap Dikerjakan</x-badge>
                        <span class="text-xs text-[#64748B]">Posisi: {{ $candidate['posisi_dilamar'] }}</span>
                    </div>
                    <h3 class="text-lg font-bold text-[#0F172A]">
                        Paket A: Pemahaman Informasi & Instruksi Operasional
                    </h3>
                    <p class="text-xs text-[#64748B] max-w-2xl leading-relaxed">
                        Evaluasi kemampuan memahami teks prosedur kerja, regulasi operasional, dan instruksi penanganan skenario teknis di lingkungan kerja nyata.
                    </p>
                </div>

                <div class="text-left sm:text-right shrink-0 space-y-1">
                    <span class="text-xs text-[#64748B]">Durasi Alokasi</span>
                    <p class="text-xl font-extrabold text-[#2563EB]">25 Menit</p>
                    <span class="text-[11px] text-slate-500">5 Butir Soal Esai</span>
                </div>
            </div>

            <div class="p-4 rounded-xl bg-slate-50 border border-[#E2E8F0] flex flex-col sm:flex-row items-center justify-between gap-3 text-xs text-[#64748B]">
                <div class="flex items-center gap-2">
                    <span class="text-amber-600 font-bold">⏱ Batas Waktu:</span>
                    <span>Dapat dikerjakan hingga 5 hari ke depan</span>
                </div>
                <div class="flex items-center gap-2">
                    <span>Status Pengerjaan: <strong class="text-[#0F172A]">Belum Dimulai</strong></span>
                </div>
            </div>

            <div class="pt-2 flex flex-col sm:flex-row items-center justify-between gap-3 border-t border-[#E2E8F0]">
                <p class="text-[11px] text-[#64748B]">
                    Pastikan Anda berada di lingkungan tenang dan koneksi internet stabil sebelum memulai.
                </p>
                <x-button href="{{ route('kandidat.tes.kerjakan', 'TES-A01') }}" variant="primary" size="md" class="w-full sm:w-auto shadow-sm">
                    Mulai Kerjakan Tes Sekarang →
                </x-button>
            </div>
        </div>
    </div>

    {{-- Completed Test Archive --}}
    <div class="space-y-4 pt-4">
        <h2 class="text-sm font-bold text-[#0F172A]">Riwayat Tes yang Telah Selesai</h2>

        <div class="p-6 rounded-2xl bg-white border border-[#E2E8F0] shadow-2xs space-y-4 opacity-95">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div class="space-y-1">
                    <div class="flex items-center gap-2">
                        <x-badge variant="success" size="sm" dot>Selesai & Dinilai</x-badge>
                        <span class="text-xs text-[#64748B]">Diselesaikan pada 28 Sep 2026</span>
                    </div>
                    <h3 class="text-base font-bold text-[#0F172A]">
                        Paket A: Pemahaman Informasi & Instruksi Operasional (Sesi Simulasi 1)
                    </h3>
                </div>

                <div class="flex items-center gap-3">
                    <div class="text-right">
                        <span class="text-xs text-[#64748B]">Skor Akhir</span>
                        <p class="text-lg font-extrabold text-emerald-600">92.0 / 100</p>
                    </div>
                    <x-button href="{{ route('kandidat.tes.hasil', 'TES-A01') }}" variant="secondary" size="sm">
                        Lihat Rincian Hasil
                    </x-button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
