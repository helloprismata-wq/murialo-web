@extends('layouts.kandidat')

@section('title', 'Detail Lamaran & Linimasa Seleksi · MURIALO')

@section('content')
<div class="space-y-8">
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <a href="{{ route('kandidat.lamaran.index') }}" class="text-xs text-[#2563EB] hover:underline">← Kembali ke Daftar Lamaran</a>
                <span class="text-xs text-slate-400">/</span>
                <span class="text-xs text-[#64748B]">{{ $id }}</span>
            </div>
            <h1 class="text-2xl font-bold text-[#0F172A] tracking-tight">{{ $job['judul'] }}</h1>
            <p class="text-xs text-[#64748B] mt-0.5">PT Muria Logika Nusantara · Diajukan pada {{ date('d M Y', strtotime($candidate['tanggal_lamar'])) }}</p>
        </div>
        <x-badge variant="primary" size="lg">
            Status: {{ $candidate['status_pipeline'] }}
        </x-badge>
    </div>

    {{-- Main Timeline Stepper Card --}}
    <div class="bg-white rounded-3xl border border-[#E2E8F0] p-6 sm:p-8 shadow-2xs space-y-8">
        <h2 class="text-base font-bold text-[#0F172A] border-b border-[#E2E8F0] pb-4">
            Linimasa Progres Seleksi
        </h2>

        <div class="relative pl-6 sm:pl-8 space-y-8 border-l-2 border-[#E2E8F0] ml-3 sm:ml-4">
            
            {{-- Step 1: Berkas Masuk --}}
            <div class="relative group">
                <div class="absolute -left-[33px] sm:-left-[41px] top-0 w-8 h-8 rounded-full bg-emerald-500 text-white flex items-center justify-center font-bold text-xs ring-4 ring-white shadow-xs">
                    ✓
                </div>
                <div class="space-y-1">
                    <div class="flex items-center justify-between">
                        <h3 class="text-sm font-bold text-[#0F172A]">1. Berkas Lamaran Diterima</h3>
                        <span class="text-[11px] text-[#64748B]">22 Sep 2026</span>
                    </div>
                    <p class="text-xs text-[#64748B] leading-relaxed">
                        Curriculum Vitae dan data profil telah berhasil diterima oleh sistem rekrutmen MURIALO.
                    </p>
                </div>
            </div>

            {{-- Step 2: Resume Parser & Skill Matching --}}
            <div class="relative group">
                <div class="absolute -left-[33px] sm:-left-[41px] top-0 w-8 h-8 rounded-full bg-emerald-500 text-white flex items-center justify-center font-bold text-xs ring-4 ring-white shadow-xs">
                    ✓
                </div>
                <div class="space-y-1">
                    <div class="flex items-center justify-between">
                        <h3 class="text-sm font-bold text-[#0F172A]">2. Seleksi Berkas & Evaluasi Skill</h3>
                        <span class="text-[11px] text-[#64748B]">24 Sep 2026</span>
                    </div>
                    <p class="text-xs text-[#64748B] leading-relaxed">
                        Kualifikasi keahlian terverifikasi memenuhi kriteria dasar posisi Senior Backend Engineer.
                    </p>
                </div>
            </div>

            {{-- Step 3: Tes Esai --}}
            <div class="relative group">
                <div class="absolute -left-[33px] sm:-left-[41px] top-0 w-8 h-8 rounded-full bg-emerald-500 text-white flex items-center justify-center font-bold text-xs ring-4 ring-white shadow-xs">
                    ✓
                </div>
                <div class="space-y-1">
                    <div class="flex items-center justify-between">
                        <h3 class="text-sm font-bold text-[#0F172A]">3. Asesmen Tes Esai Online</h3>
                        <span class="text-[11px] text-[#64748B]">28 Sep 2026</span>
                    </div>
                    <p class="text-xs text-[#64748B] leading-relaxed">
                        Pengerjaan Paket Tes A telah selesai dan nilai evaluasi telah dipublikasikan.
                    </p>
                    <div class="pt-1">
                        <a href="{{ route('kandidat.tes.hasil', 'TES-A01') }}" class="text-xs font-semibold text-[#2563EB] hover:underline inline-flex items-center gap-1">
                            <span>Lihat Hasil Tes yang Diizinkan</span>
                            <span>→</span>
                        </a>
                    </div>
                </div>
            </div>

            {{-- Step 4: Wawancara HR & User (Active) --}}
            <div class="relative group">
                <div class="absolute -left-[33px] sm:-left-[41px] top-0 w-8 h-8 rounded-full bg-[#2563EB] text-white flex items-center justify-center font-bold text-xs ring-4 ring-white shadow-md animate-pulse">
                    4
                </div>
                <div class="space-y-1">
                    <div class="flex items-center justify-between">
                        <h3 class="text-sm font-bold text-[#2563EB]">4. Wawancara HR & Tim Teknis (Tahapan Aktif)</h3>
                        <span class="text-[11px] font-semibold text-[#2563EB]">Sedang Berjalan</span>
                    </div>
                    <p class="text-xs text-[#334155] leading-relaxed">
                        Tim Human Resources sedang menyelaraskan jadwal wawancara teknis. Anda akan dihubungi melalui email dan WhatsApp.
                    </p>
                </div>
            </div>

            {{-- Step 5: Offering & Keputusan Akhir --}}
            <div class="relative group opacity-60">
                <div class="absolute -left-[33px] sm:-left-[41px] top-0 w-8 h-8 rounded-full bg-slate-200 text-slate-500 flex items-center justify-center font-bold text-xs ring-4 ring-white shadow-xs">
                    5
                </div>
                <div class="space-y-1">
                    <div class="flex items-center justify-between">
                        <h3 class="text-sm font-bold text-slate-700">5. Penawaran Kerja (Offering Letter)</h3>
                        <span class="text-[11px] text-[#64748B]">Menunggu</span>
                    </div>
                    <p class="text-xs text-[#64748B] leading-relaxed">
                        Pemberitahuan hasil akhir dan surat penawaran resmi dari manajemen perusahaan.
                    </p>
                </div>
            </div>

        </div>
    </div>

    {{-- Ringkasan Posisi & Detail Berkas --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <x-card title="Rincian Lowongan Dilamar">
            <div class="space-y-2 text-xs text-[#64748B]">
                <p>Posisi: <strong class="text-[#0F172A]">{{ $job['judul'] }}</strong></p>
                <p>Departemen: <strong class="text-[#0F172A]">{{ $job['departemen'] }}</strong></p>
                <p>Lokasi: <strong class="text-[#0F172A]">{{ $job['lokasi'] }}</strong></p>
                <p>Kisaran Gaji: <strong class="text-[#0F172A]">Rp {{ number_format($job['gaji_min']/1000000, 0) }} - {{ number_format($job['gaji_max']/1000000, 0) }} Juta</strong></p>
            </div>
        </x-card>

        <x-card title="Berkas yang Digunakan">
            <div class="space-y-2 text-xs text-[#64748B]">
                <p>Nama Berkas: <strong class="text-[#0F172A]">{{ $candidate['cv_filename'] }}</strong></p>
                <p>Kandidat: <strong class="text-[#0F172A]">{{ $candidate['nama'] }}</strong></p>
                <p>Email: <strong class="text-[#0F172A]">{{ $candidate['email'] }}</strong></p>
                <p>Telepon: <strong class="text-[#0F172A]">{{ $candidate['telepon'] }}</strong></p>
            </div>
        </x-card>
    </div>
</div>
@endsection
