@extends('layouts.kandidat')

@section('title', 'Dashboard Kandidat · MURIALO')

@section('content')
<div class="space-y-8">
    {{-- Welcome Banner --}}
    <div class="bg-gradient-to-r from-[#2563EB] to-indigo-700 rounded-3xl p-6 sm:p-8 text-white shadow-md relative overflow-hidden">
        <div class="relative z-10 max-w-2xl space-y-3">
            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-white/20 text-xs font-semibold backdrop-blur-xs">
                <span>✦</span> Status: Aktif dalam Proses Seleksi
            </span>
            <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight">
                Halo, {{ $candidate['nama'] }}!
            </h1>
            <p class="text-xs sm:text-sm text-blue-100 leading-relaxed">
                Lamaran Anda untuk posisi <strong class="text-white">{{ $candidate['posisi_dilamar'] }}</strong> sedang berada pada tahapan <strong class="underline decoration-blue-300 underline-offset-4">{{ $candidate['status_pipeline'] }}</strong>.
            </p>
        </div>
    </div>

    {{-- Status Highlights Grid --}}
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        {{-- Kelengkapan Profil --}}
        <div class="p-5 rounded-2xl bg-white border border-[#E2E8F0] shadow-2xs space-y-2">
            <div class="flex items-center justify-between text-xs text-[#64748B]">
                <span>Kelengkapan Profil</span>
                <span class="font-bold text-[#2563EB]">{{ $candidate['kelengkapan_profil'] }}%</span>
            </div>
            <div class="w-full bg-slate-100 h-2 rounded-full overflow-hidden">
                <div class="bg-[#2563EB] h-full rounded-full" style="width: {{ $candidate['kelengkapan_profil'] }}%"></div>
            </div>
            <p class="text-[11px] text-[#64748B]">Profil Anda memenuhi kriteria peninjauan HR.</p>
        </div>

        {{-- CV Terverifikasi --}}
        <div class="p-5 rounded-2xl bg-white border border-[#E2E8F0] shadow-2xs space-y-1">
            <span class="text-xs text-[#64748B]">Berkas CV Aktif</span>
            <p class="text-sm font-bold text-[#0F172A] truncate">{{ $candidate['cv_filename'] }}</p>
            <div class="pt-1 flex items-center gap-1 text-[11px] text-emerald-600 font-semibold">
                <span>✓ Terurai oleh Parser</span>
                <span>({{ count($candidate['skills']) }} skill)</span>
            </div>
        </div>

        {{-- Lamaran Berjalan --}}
        <div class="p-5 rounded-2xl bg-white border border-[#E2E8F0] shadow-2xs space-y-1">
            <span class="text-xs text-[#64748B]">Lamaran Aktif</span>
            <p class="text-2xl font-black text-[#0F172A]">1</p>
            <p class="text-[11px] text-[#2563EB] font-semibold">{{ $candidate['status_pipeline'] }}</p>
        </div>

        {{-- Tugas Tes --}}
        <div class="p-5 rounded-2xl bg-white border border-[#E2E8F0] shadow-2xs space-y-1">
            <span class="text-xs text-[#64748B]">Tugas Tes Tersedia</span>
            <p class="text-2xl font-black text-amber-600">1</p>
            <p class="text-[11px] text-amber-700 font-medium">Batas: 5 Hari Lagi</p>
        </div>
    </div>

    {{-- Main 2-Column Grid: Ongoing Application vs Quick Tasks --}}
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        
        {{-- Left: Lamaran Berjalan & Timeline --}}
        <div class="lg:col-span-8 space-y-6">
            <x-card title="Lamaran yang Sedang Berjalan" subtitle="Pantau kemajuan tahapan seleksi Anda secara transparan">
                <div class="p-5 rounded-2xl bg-slate-50 border border-[#E2E8F0] space-y-5">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-4 border-b border-[#E2E8F0]">
                        <div>
                            <span class="text-[11px] font-bold text-[#2563EB] uppercase tracking-wider">PT Muria Logika Nusantara</span>
                            <h3 class="text-base font-bold text-[#0F172A]">{{ $candidate['posisi_dilamar'] }}</h3>
                            <p class="text-xs text-[#64748B] mt-0.5">Dikirim pada {{ date('d M Y', strtotime($candidate['tanggal_lamar'])) }}</p>
                        </div>
                        <x-badge variant="primary" size="md">
                            {{ $candidate['status_pipeline'] }}
                        </x-badge>
                    </div>

                    {{-- Stepper Progress Visual --}}
                    <div class="space-y-2">
                        <div class="flex justify-between text-xs font-semibold text-[#0F172A]">
                            <span>Tahapan: Wawancara HR</span>
                            <span class="text-[#2563EB]">Langkah 3 dari 5</span>
                        </div>
                        <div class="grid grid-cols-5 gap-2">
                            <div class="h-2 rounded-full bg-emerald-500" title="1. Berkas Masuk"></div>
                            <div class="h-2 rounded-full bg-emerald-500" title="2. Evaluasi CV"></div>
                            <div class="h-2 rounded-full bg-[#2563EB] animate-pulse" title="3. Wawancara HR"></div>
                            <div class="h-2 rounded-full bg-slate-200" title="4. Wawancara User"></div>
                            <div class="h-2 rounded-full bg-slate-200" title="5. Penawaran"></div>
                        </div>
                    </div>

                    {{-- Action Button --}}
                    <div class="pt-2 flex items-center justify-between">
                        <span class="text-xs text-[#64748B]">Jadwal wawancara akan dihubungi via WhatsApp / Email</span>
                        <x-button href="{{ route('kandidat.lamaran.show', 'APP-101') }}" variant="secondary" size="sm">
                            Detail Timeline Seleksi →
                        </x-button>
                    </div>
                </div>
            </x-card>

            {{-- Tes yang Perlu Dikerjakan --}}
            <x-card title="Tes Esai yang Perlu Dikerjakan" subtitle="Asesmen penalaran dan pemahaman operasional pekerjaan">
                <div class="p-5 rounded-2xl bg-amber-50/70 border border-amber-200 space-y-4">
                    <div class="flex items-start justify-between gap-3">
                        <div class="space-y-1">
                            <x-badge variant="warning" size="sm">Menunggu Pengerjaan</x-badge>
                            <h4 class="text-sm font-bold text-[#0F172A]">Paket A: Pemahaman Informasi & Instruksi Operasional</h4>
                            <p class="text-xs text-[#64748B]">Durasi pengerjaan: 25 Menit · 5 Butir Soal Esai Jawaban Singkat</p>
                        </div>
                    </div>

                    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 pt-2 border-t border-amber-200/80">
                        <span class="text-xs text-amber-800 font-medium">Batas pengerjaan: 5 hari kalender</span>
                        <x-button href="{{ route('kandidat.tes.kerjakan', 'TES-A01') }}" variant="primary" size="sm">
                            Mulai Kerjakan Tes Sekarang →
                        </x-button>
                    </div>
                </div>
            </x-card>
        </div>

        {{-- Right: Langkah Berikutnya & Rekomendasi Lowongan --}}
        <div class="lg:col-span-4 space-y-6">
            {{-- Card Langkah Berikutnya --}}
            <div class="bg-white rounded-2xl border border-[#E2E8F0] p-6 shadow-2xs space-y-4">
                <h3 class="text-sm font-bold text-[#0F172A] pb-3 border-b border-[#E2E8F0]">Langkah Berikutnya</h3>
                <ul class="space-y-3 text-xs">
                    <li class="flex items-start gap-2.5">
                        <span class="w-5 h-5 rounded-full bg-blue-100 text-[#2563EB] flex items-center justify-center font-bold shrink-0">1</span>
                        <div>
                            <p class="font-semibold text-[#0F172A]">Selesaikan Tes Esai Online</p>
                            <p class="text-[#64748B]">Kerjakan Paket A sebelum batas waktu berakhir.</p>
                        </div>
                    </li>
                    <li class="flex items-start gap-2.5">
                        <span class="w-5 h-5 rounded-full bg-slate-100 text-[#64748B] flex items-center justify-center font-bold shrink-0">2</span>
                        <div>
                            <p class="font-semibold text-[#0F172A]">Konfirmasi Keahlian Terurai</p>
                            <p class="text-[#64748B]">Pastikan data hasil ekstraksi parser CV sudah sesuai.</p>
                        </div>
                    </li>
                    <li class="flex items-start gap-2.5">
                        <span class="w-5 h-5 rounded-full bg-slate-100 text-[#64748B] flex items-center justify-center font-bold shrink-0">3</span>
                        <div>
                            <p class="font-semibold text-[#0F172A]">Persiapkan Wawancara</p>
                            <p class="text-[#64748B]">Tinjau kembali ringkasan proyek teknis di portofolio.</p>
                        </div>
                    </li>
                </ul>
            </div>

            {{-- Rekomendasi Lowongan Terkait --}}
            <div class="bg-white rounded-2xl border border-[#E2E8F0] p-6 shadow-2xs space-y-4">
                <h3 class="text-sm font-bold text-[#0F172A] pb-3 border-b border-[#E2E8F0]">Lowongan Terkait Lainnya</h3>
                <div class="space-y-3">
                    @foreach($lowongan as $job)
                        <div class="p-3 rounded-xl bg-slate-50 border border-[#E2E8F0] hover:border-[#2563EB]/40 transition-smooth space-y-1">
                            <span class="text-[10px] font-bold text-[#2563EB] uppercase">{{ $job['departemen'] }}</span>
                            <h4 class="text-xs font-bold text-[#0F172A]">
                                <a href="{{ route('karir.show', $job['id']) }}" class="hover:underline">{{ $job['judul'] }}</a>
                            </h4>
                            <p class="text-[11px] text-[#64748B]">{{ $job['lokasi'] }} · {{ $job['tipe_pekerjaan'] }}</p>
                        </div>
                    @endforeach
                </div>
                <div class="pt-2 text-center">
                    <a href="{{ route('kandidat.lowongan') }}" class="text-xs font-semibold text-[#2563EB] hover:underline">
                        Jelajah Seluruh Lowongan →
                    </a>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
