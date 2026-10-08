@extends('layouts.public')

@section('title', 'MURIALO · Rekrut Talenta yang Tepat, Lebih Terarah')

@section('content')
{{-- ── HERO SECTION ──────────────────────────────────────────────────────── --}}
<section class="relative pt-12 pb-20 md:pt-18 md:pb-28 overflow-hidden bg-gradient-to-b from-white via-[#F8FAFC] to-[#F1F5F9]">
    {{-- Subtle Decorative Gradient Grid --}}
    <div class="absolute inset-0 bg-[linear-gradient(to_right,#E2E8F015_1px,transparent_1px),linear-gradient(to_bottom,#E2E8F015_1px,transparent_1px)] bg-[size:4rem_4rem] [mask-image:radial-gradient(ellipse_60%_50%_at_50%_0%,#000_70%,transparent_100%)] pointer-events-none"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
            
            {{-- Left Column: Value Prop --}}
            <div class="lg:col-span-7 space-y-6 text-center lg:text-left">
                <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-[#EFF6FF] border border-[#BFDBFE] text-xs font-semibold text-[#1D4ED8] shadow-2xs">
                    <span class="w-2 h-2 rounded-full bg-[#2563EB] animate-pulse"></span>
                    <span>Platform Rekrutmen Cerdas Enterprise</span>
                </div>

                <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold tracking-tight text-[#0F172A] leading-[1.1]">
                    Rekrut talenta yang tepat, <br class="hidden sm:inline" />
                    <span class="text-[#2563EB]">lebih terarah.</span>
                </h1>

                <p class="text-base sm:text-lg text-[#64748B] max-w-xl mx-auto lg:mx-0 leading-relaxed">
                    Sistem rekrutmen terpadu yang menghubungkan lowongan, ekstraksi CV otomatis, evaluasi tes esai cerdas, dan analitik talenta.
                </p>

                {{-- Dual Action CTAs --}}
                <div class="flex flex-col sm:flex-row items-center justify-center lg:justify-start gap-3.5 pt-2">
                    <x-button href="{{ route('karir.index') }}" variant="primary" size="lg" class="w-full sm:w-auto shadow-md">
                        Jelajah Lowongan Terbuka
                    </x-button>
                    <x-button href="{{ route('hr.dashboard') }}" variant="secondary" size="lg" class="w-full sm:w-auto">
                        Masuk Portal HR
                    </x-button>
                </div>

                {{-- Fast Stats Metrics --}}
                <div class="pt-6 grid grid-cols-3 gap-4 border-t border-[#E2E8F0] max-w-md mx-auto lg:mx-0 text-left">
                    <div>
                        <p class="text-2xl font-extrabold text-[#0F172A]">{{ $kpis['lowongan_aktif'] }}</p>
                        <p class="text-xs text-[#64748B]">Lowongan Aktif</p>
                    </div>
                    <div>
                        <p class="text-2xl font-extrabold text-[#0F172A]">{{ $kpis['total_lamaran'] }}</p>
                        <p class="text-xs text-[#64748B]">Pelamar Terdata</p>
                    </div>
                    <div>
                        <p class="text-2xl font-extrabold text-[#2563EB]">S-BERT</p>
                        <p class="text-xs text-[#64748B]">Semantic Matching</p>
                    </div>
                </div>
            </div>

            {{-- Right Column: Interactive Real Component Preview --}}
            <div class="lg:col-span-5 relative">
                <div class="bg-white rounded-3xl border border-[#E2E8F0] p-6 shadow-xl space-y-5 relative">
                    <div class="flex items-center justify-between pb-3 border-b border-[#E2E8F0]">
                        <div class="flex items-center gap-2.5">
                            <span class="w-3 h-3 rounded-full bg-red-400"></span>
                            <span class="w-3 h-3 rounded-full bg-amber-400"></span>
                            <span class="w-3 h-3 rounded-full bg-emerald-400"></span>
                        </div>
                        <x-badge variant="primary" size="sm">Live Evaluation Demo</x-badge>
                    </div>

                    {{-- Sample Candidate Matching Card --}}
                    <div class="p-4 rounded-xl bg-slate-50 border border-[#E2E8F0] space-y-3">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-[#2563EB] text-white font-bold flex items-center justify-center">
                                    BS
                                </div>
                                <div>
                                    <h4 class="text-sm font-bold text-[#0F172A]">Budi Santoso</h4>
                                    <p class="text-xs text-[#64748B]">Senior Backend Engineer</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <span class="text-lg font-extrabold text-emerald-600">92%</span>
                                <p class="text-[10px] text-[#64748B] uppercase">Kesesuaian</p>
                            </div>
                        </div>

                        <div class="space-y-1.5">
                            <div class="flex justify-between text-xs text-[#64748B]">
                                <span>Verifikasi Skill Wajib</span>
                                <span class="font-semibold text-[#0F172A]">6 / 6 Terpenuhi</span>
                            </div>
                            <div class="w-full bg-slate-200 h-2 rounded-full overflow-hidden">
                                <div class="bg-[#2563EB] h-full rounded-full" style="width: 92%"></div>
                            </div>
                        </div>

                        <div class="flex flex-wrap gap-1.5 pt-1">
                            <span class="px-2 py-0.5 rounded text-[10px] font-semibold bg-emerald-100 text-emerald-800">Python ✓</span>
                            <span class="px-2 py-0.5 rounded text-[10px] font-semibold bg-emerald-100 text-emerald-800">FastAPI ✓</span>
                            <span class="px-2 py-0.5 rounded text-[10px] font-semibold bg-emerald-100 text-emerald-800">Laravel ✓</span>
                            <span class="px-2 py-0.5 rounded text-[10px] font-semibold bg-emerald-100 text-emerald-800">Docker ✓</span>
                        </div>
                    </div>

                    {{-- AI Smart Grading Preview Snippet --}}
                    <div class="p-4 rounded-xl bg-[#EFF6FF] border border-[#BFDBFE] space-y-2">
                        <div class="flex items-center justify-between text-xs">
                            <span class="font-bold text-[#1E40AF]">Smart Grading Esai</span>
                            <span class="font-extrabold text-[#2563EB]">Nilai: 92.0 / 100</span>
                        </div>
                        <p class="text-xs text-[#1E3A8A] leading-relaxed">
                            "Jawaban mencakup parameter batas waktu 1 jam secara tepat dengan penalaran konsekuensi operasional yang lengkap."
                        </p>
                    </div>

                    {{-- Quick Action Link --}}
                    <div class="pt-2 text-center">
                        <a href="{{ route('hr.skill_matching') }}" class="text-xs font-semibold text-[#2563EB] hover:underline inline-flex items-center gap-1">
                            <span>Buka Modul Automated Skill Matching</span>
                            <span>→</span>
                        </a>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

{{-- ── 4 PILAR FITUR MURIALO ─────────────────────────────────────────────── --}}
<section id="fitur" class="py-20 bg-white border-y border-[#E2E8F0]">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="max-w-2xl mx-auto text-center space-y-3 mb-16">
            <h2 class="text-3xl font-extrabold text-[#0F172A] tracking-tight">Empat Pilar Evaluasi Rekrutmen</h2>
            <p class="text-sm text-[#64748B] leading-relaxed">
                MURIALO menggabungkan kecerdasan buatan terapan untuk mempermudah HR dalam menyeleksi kandidat berkualitas secara transparan.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            {{-- Pilar 1: Resume Parser --}}
            <div class="p-6 rounded-2xl border border-[#E2E8F0] bg-slate-50 hover:bg-white hover:shadow-md transition-smooth space-y-4">
                <div class="w-12 h-12 rounded-xl bg-blue-100 text-[#2563EB] flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
                <h3 class="text-base font-bold text-[#0F172A]">CV & Resume Parser</h3>
                <p class="text-xs text-[#64748B] leading-relaxed">
                    Mengekstrak data pengalaman, riwayat pendidikan, dan keahlian dari berkas PDF secara otomatis ke dalam entitas terstruktur.
                </p>
                <a href="{{ route('hr.cv_parser') }}" class="text-xs font-semibold text-[#2563EB] hover:underline inline-block pt-1">
                    Lihat Modul CV Parser →
                </a>
            </div>

            {{-- Pilar 2: Automated Skill Matching --}}
            <div class="p-6 rounded-2xl border border-[#E2E8F0] bg-slate-50 hover:bg-white hover:shadow-md transition-smooth space-y-4">
                <div class="w-12 h-12 rounded-xl bg-emerald-100 text-[#059669] flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                    </svg>
                </div>
                <h3 class="text-base font-bold text-[#0F172A]">Automated Skill Matching</h3>
                <p class="text-xs text-[#64748B] leading-relaxed">
                    Menghitung skor kesesuaian semantik antara kualifikasi lowongan dan bukti kompetensi kandidat menggunakan Sentence-BERT.
                </p>
                <a href="{{ route('hr.skill_matching') }}" class="text-xs font-semibold text-[#2563EB] hover:underline inline-block pt-1">
                    Lihat Skill Matching →
                </a>
            </div>

            {{-- Pilar 3: Smart Grading Test --}}
            <div class="p-6 rounded-2xl border border-[#E2E8F0] bg-slate-50 hover:bg-white hover:shadow-md transition-smooth space-y-4">
                <div class="w-12 h-12 rounded-xl bg-amber-100 text-[#D97706] flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                    </svg>
                </div>
                <h3 class="text-base font-bold text-[#0F172A]">Smart Grading Test</h3>
                <p class="text-xs text-[#64748B] leading-relaxed">
                    Evaluasi cerdas jawaban tes esai berdasarkan rubrik acuan, disajikan berdampingan dengan nilai manual reviewer HR.
                </p>
                <a href="{{ route('hr.smart_grading.index') }}" class="text-xs font-semibold text-[#2563EB] hover:underline inline-block pt-1">
                    Lihat Smart Grading →
                </a>
            </div>

            {{-- Pilar 4: Analitik Rekrutmen --}}
            <div class="p-6 rounded-2xl border border-[#E2E8F0] bg-slate-50 hover:bg-white hover:shadow-md transition-smooth space-y-4">
                <div class="w-12 h-12 rounded-xl bg-purple-100 text-[#7C3AED] flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z" />
                    </svg>
                </div>
                <h3 class="text-base font-bold text-[#0F172A]">Analitik & Forecasting</h3>
                <p class="text-xs text-[#64748B] leading-relaxed">
                    Proyeksi tren lamaran Time Series, klasterisasi talenta K-Means, dan ringkasan insight otomatis berbasis Natural Language Generation.
                </p>
                <a href="{{ route('hr.analitik') }}" class="text-xs font-semibold text-[#2563EB] hover:underline inline-block pt-1">
                    Lihat Modul Analitik →
                </a>
            </div>
        </div>
    </div>
</section>

{{-- ── LOWONGAN PILIHAN SHOWCASE ────────────────────────────────────────── --}}
<section class="py-20 bg-[#F8FAFC]">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4 mb-10">
            <div>
                <h2 class="text-2xl sm:text-3xl font-extrabold text-[#0F172A] tracking-tight">Lowongan Pilihan Perusahaan</h2>
                <p class="text-xs sm:text-sm text-[#64748B] mt-1">Peluang karier terbaru di PT Muria Logika Nusantara</p>
            </div>
            <x-button href="{{ route('karir.index') }}" variant="secondary" size="md">
                Lihat Semua Lowongan ({{ count($lowongan) }}+)
            </x-button>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @foreach($lowongan as $job)
                <div class="p-6 rounded-2xl bg-white border border-[#E2E8F0] hover:border-[#2563EB]/40 hover:shadow-md transition-smooth flex flex-col justify-between space-y-4">
                    <div class="space-y-3">
                        <div class="flex items-start justify-between gap-2">
                            <div>
                                <span class="text-[11px] font-bold text-[#2563EB] uppercase tracking-wider">{{ $job['departemen'] }}</span>
                                <h3 class="text-lg font-bold text-[#0F172A] hover:text-[#2563EB] transition-smooth">
                                    <a href="{{ route('karir.show', $job['id']) }}">{{ $job['judul'] }}</a>
                                </h3>
                            </div>
                            <x-badge variant="success" size="sm" dot>Aktif</x-badge>
                        </div>

                        <p class="text-xs text-[#64748B] line-clamp-2 leading-relaxed">
                            {{ $job['deskripsi'] }}
                        </p>

                        <div class="flex flex-wrap items-center gap-2 pt-1 text-xs text-[#475569]">
                            <span class="inline-flex items-center gap-1 bg-slate-100 px-2.5 py-1 rounded-md">
                                <svg class="w-3.5 h-3.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                {{ $job['lokasi'] }}
                            </span>
                            <span class="inline-flex items-center gap-1 bg-slate-100 px-2.5 py-1 rounded-md">
                                <svg class="w-3.5 h-3.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                {{ $job['tipe_pekerjaan'] }}
                            </span>
                            <span class="inline-flex items-center gap-1 bg-slate-100 px-2.5 py-1 rounded-md">
                                <svg class="w-3.5 h-3.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                Rp {{ number_format($job['gaji_min']/1000000, 0) }} - {{ number_format($job['gaji_max']/1000000, 0) }} Juta
                            </span>
                        </div>
                    </div>

                    <div class="pt-4 border-t border-[#E2E8F0] flex items-center justify-between">
                        <span class="text-[11px] text-[#64748B]">Batas lamaran: {{ date('d M Y', strtotime($job['deadline'])) }}</span>
                        <x-button href="{{ route('karir.show', $job['id']) }}" variant="outline" size="sm">
                            Rincian & Lamar
                        </x-button>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ── ALUR SELEKSI TRANSPARAN ───────────────────────────────────────────── --}}
<section id="alur" class="py-20 bg-white border-y border-[#E2E8F0]">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="max-w-2xl mx-auto text-center space-y-3 mb-16">
            <h2 class="text-3xl font-extrabold text-[#0F172A] tracking-tight">Alur Seleksi Terpadu</h2>
            <p class="text-sm text-[#64748B]">
                Pengalaman transparan bagi kandidat dan efisiensi waktu peninjauan bagi tim HR.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-8 relative">
            {{-- Step 1 --}}
            <div class="space-y-3 text-center sm:text-left">
                <div class="w-10 h-10 rounded-full bg-[#2563EB] text-white font-extrabold flex items-center justify-center text-sm shadow-sm">
                    1
                </div>
                <h3 class="text-base font-bold text-[#0F172A]">Kirim CV & Profil</h3>
                <p class="text-xs text-[#64748B] leading-relaxed">
                    Kandidat mengunggah CV. Modul Resume Parser mengekstrak skill dan pengalaman secara instan.
                </p>
            </div>

            {{-- Step 2 --}}
            <div class="space-y-3 text-center sm:text-left">
                <div class="w-10 h-10 rounded-full bg-[#2563EB] text-white font-extrabold flex items-center justify-center text-sm shadow-sm">
                    2
                </div>
                <h3 class="text-base font-bold text-[#0F172A]">Pencocokan Semantik</h3>
                <p class="text-xs text-[#64748B] leading-relaxed">
                    Automated Skill Matching mengevaluasi kesesuaian keahlian kandidat terhadap kualifikasi lowongan.
                </p>
            </div>

            {{-- Step 3 --}}
            <div class="space-y-3 text-center sm:text-left">
                <div class="w-10 h-10 rounded-full bg-[#2563EB] text-white font-extrabold flex items-center justify-center text-sm shadow-sm">
                    3
                </div>
                <h3 class="text-base font-bold text-[#0F172A]">Asesmen Tes Esai</h3>
                <p class="text-xs text-[#64748B] leading-relaxed">
                    Kandidat mengerjakan soal situasional operasional. Smart Grading membantu reviewer HR dalam scoring objektif.
                </p>
            </div>

            {{-- Step 4 --}}
            <div class="space-y-3 text-center sm:text-left">
                <div class="w-10 h-10 rounded-full bg-[#10B981] text-white font-extrabold flex items-center justify-center text-sm shadow-sm">
                    4
                </div>
                <h3 class="text-base font-bold text-[#0F172A]">Wawancara & Keputusan</h3>
                <p class="text-xs text-[#64748B] leading-relaxed">
                    Kandidat terbaik diundang ke wawancara akhir. Status penerimaan diperbarui di portal kandidat.
                </p>
            </div>
        </div>
    </div>
</section>

{{-- ── FAQ INTERAKTIF (ACCORDION) ───────────────────────────────────────── --}}
<section id="faq" class="py-20 bg-[#F8FAFC]">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
        <div class="text-center space-y-3">
            <h2 class="text-3xl font-extrabold text-[#0F172A] tracking-tight">Pertanyaan yang Sering Diajukan</h2>
            <p class="text-sm text-[#64748B]">Informasi seputar cara kerja platform dan mode evaluasi MURIALO.</p>
        </div>

        <div class="space-y-3" x-data="{ activeFaq: 1 }">
            {{-- FAQ 1 --}}
            <div class="rounded-xl border border-[#E2E8F0] bg-white overflow-hidden shadow-2xs">
                <button
                    type="button"
                    @click="activeFaq = (activeFaq === 1 ? 0 : 1)"
                    class="w-full px-6 py-4.5 text-left font-bold text-sm text-[#0F172A] flex items-center justify-between gap-4"
                >
                    <span>Bagaimana cara kerja pencocokan keahlian semantik?</span>
                    <svg class="w-4 h-4 text-slate-400 transition-transform" :class="activeFaq === 1 ? 'rotate-180 text-[#2563EB]' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
                <div x-show="activeFaq === 1" x-transition class="px-6 pb-4.5 text-xs text-[#64748B] leading-relaxed border-t border-slate-100 pt-3">
                    Sistem menggunakan model Sentence-BERT untuk memahami konteks keahlian pada berkas CV dan mencocokkannya dengan deskripsi kualifikasi lowongan. Skor yang dihasilkan mencerminkan tingkat relevansi semantik, bukan sekadar pencarian kata kunci eksak.
                </div>
            </div>

            {{-- FAQ 2 --}}
            <div class="rounded-xl border border-[#E2E8F0] bg-white overflow-hidden shadow-2xs">
                <button
                    type="button"
                    @click="activeFaq = (activeFaq === 2 ? 0 : 2)"
                    class="w-full px-6 py-4.5 text-left font-bold text-sm text-[#0F172A] flex items-center justify-between gap-4"
                >
                    <span>Apakah evaluasi tes esai dilakukan sepenuhnya oleh AI?</span>
                    <svg class="w-4 h-4 text-slate-400 transition-transform" :class="activeFaq === 2 ? 'rotate-180 text-[#2563EB]' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
                <div x-show="activeFaq === 2" x-transition class="px-6 pb-4.5 text-xs text-[#64748B] leading-relaxed border-t border-slate-100 pt-3">
                    Tidak. Di MURIALO, Smart Grading berfungsi sebagai asisten penilai awal. Keputusan nilai akhir dan verifikasi rubrik tetap berada di tangan penilai HR manusia untuk menjaga keadilan dan objektivitas evaluasi.
                </div>
            </div>

            {{-- FAQ 3 --}}
            <div class="rounded-xl border border-[#E2E8F0] bg-white overflow-hidden shadow-2xs">
                <button
                    type="button"
                    @click="activeFaq = (activeFaq === 3 ? 0 : 3)"
                    class="w-full px-6 py-4.5 text-left font-bold text-sm text-[#0F172A] flex items-center justify-between gap-4"
                >
                    <span>Apakah prototipe ini sudah dapat diperagakan secara interaktif?</span>
                    <svg class="w-4 h-4 text-slate-400 transition-transform" :class="activeFaq === 3 ? 'rotate-180 text-[#2563EB]' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
                <div x-show="activeFaq === 3" x-transition class="px-6 pb-4.5 text-xs text-[#64748B] leading-relaxed border-t border-slate-100 pt-3">
                    Ya. Seluruh halaman telah dilengkapi dataset demo terpadu yang konsisten antara KPI, lowongan, daftar kandidat, dan simulasi alur peninjauan HR maupun pengerjaan tes esai kandidat.
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ── BOTTOM CALL TO ACTION ─────────────────────────────────────────────── --}}
<section class="py-16 bg-[#2563EB] text-white">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 text-center space-y-6">
        <h2 class="text-3xl sm:text-4xl font-extrabold tracking-tight">Siap mengeksplorasi prototipe MURIALO?</h2>
        <p class="text-base text-blue-100 max-w-xl mx-auto leading-relaxed">
            Mulai eksplorasi modul rekrutmen perusahaan sebagai recruiter atau coba pengalaman melamar sebagai kandidat.
        </p>
        <div class="flex flex-col sm:flex-row items-center justify-center gap-4 pt-2">
            <x-button href="{{ route('hr.dashboard') }}" variant="secondary" size="lg" class="w-full sm:w-auto shadow-md">
                Buka Dashboard HR
            </x-button>
            <x-button href="{{ route('kandidat.dashboard') }}" variant="outline" size="lg" class="w-full sm:w-auto text-white border-white hover:bg-white/10">
                Buka Portal Kandidat
            </x-button>
        </div>
    </div>
</section>
@endsection
