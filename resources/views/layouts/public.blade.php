<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="@yield('meta_description', 'MURIALO — Platform rekrutmen cerdas enterprise berbasis AI. Hubungkan lowongan, CV parser, skill matching, dan tes esai terpadu.')">
    <title>@yield('title', 'MURIALO') · Platform Rekrutmen Terpadu</title>

    {{-- Plus Jakarta Sans Font --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="bg-[#F8FAFC] text-[#0F172A] font-sans antialiased min-h-screen flex flex-col selection:bg-[#2563EB] selection:text-white">

    {{-- Top Demo Mode Floating Bar --}}
    <aside aria-label="Demo Bar" class="bg-[#0F172A] text-slate-300 text-xs py-2 px-4 border-b border-slate-800">
        <div class="max-w-7xl mx-auto flex flex-col sm:flex-row items-center justify-between gap-2 text-center sm:text-left">
            <div class="flex items-center gap-2">
                <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-semibold bg-[#2563EB] text-white">PROTOTIPE DEMO</span>
                <span class="text-slate-400">Pilih peran untuk eksplorasi simulasi cepat:</span>
            </div>
            <div class="flex items-center gap-2 overflow-x-auto text-[11px]">
                <a href="{{ route('demo.login', ['role' => 'hr']) }}" class="px-2.5 py-1 rounded bg-slate-800 hover:bg-slate-700 text-white font-medium transition-smooth flex items-center gap-1.5">
                    <span class="w-2 h-2 rounded-full bg-blue-400"></span> HR / Recruiter
                </a>
                <a href="{{ route('demo.login', ['role' => 'kandidat']) }}" class="px-2.5 py-1 rounded bg-slate-800 hover:bg-slate-700 text-white font-medium transition-smooth flex items-center gap-1.5">
                    <span class="w-2 h-2 rounded-full bg-emerald-400"></span> Kandidat (Pelamar)
                </a>
                <a href="{{ route('demo.login', ['role' => 'admin']) }}" class="px-2.5 py-1 rounded bg-slate-800 hover:bg-slate-700 text-white font-medium transition-smooth flex items-center gap-1.5">
                    <span class="w-2 h-2 rounded-full bg-purple-400"></span> Administrator
                </a>
            </div>
        </div>
    </aside>

    {{-- Header & Navigasi Publik --}}
    <header x-data="{ mobileMenu: false }" class="sticky top-0 z-40 bg-white/90 backdrop-blur-md border-b border-[#E2E8F0]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-18">
                
                {{-- Brand Logo --}}
                <a href="{{ url('/') }}" class="flex items-center gap-3 group">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-[#2563EB] to-[#1D4ED8] flex items-center justify-center text-white font-black text-xl shadow-sm group-hover:scale-105 transition-smooth">
                        M
                    </div>
                    <div>
                        <span class="text-xl font-bold tracking-tight text-[#0F172A] block leading-none">MURIALO</span>
                        <span class="text-[10px] font-medium tracking-wider text-[#64748B] uppercase">Recruitment Platform</span>
                    </div>
                </a>

                {{-- Desktop Nav Links --}}
                <nav class="hidden md:flex items-center gap-8 text-sm font-medium text-[#64748B]">
                    <a href="{{ url('/') }}#fitur" class="hover:text-[#2563EB] transition-smooth">Fitur Unggulan</a>
                    <a href="{{ url('/') }}#alur" class="hover:text-[#2563EB] transition-smooth">Alur Seleksi</a>
                    <a href="{{ route('karir.index') }}" class="hover:text-[#2563EB] transition-smooth {{ request()->routeIs('karir.*') ? 'text-[#2563EB] font-semibold' : '' }}">Jelajah Lowongan</a>
                    <a href="{{ url('/') }}#faq" class="hover:text-[#2563EB] transition-smooth">FAQ</a>
                </nav>

                {{-- CTA Buttons --}}
                <div class="hidden md:flex items-center gap-3">
                    <a href="{{ route('demo.login') }}" class="text-sm font-semibold text-[#0F172A] hover:text-[#2563EB] px-3 py-2 transition-smooth">
                        Masuk
                    </a>
                    <x-button href="{{ route('karir.index') }}" variant="primary" size="md">
                        Cari Lowongan
                    </x-button>
                </div>

                {{-- Mobile Menu Trigger --}}
                <div class="flex md:hidden">
                    <button
                        type="button"
                        @click="mobileMenu = !mobileMenu"
                        class="p-2 rounded-lg text-slate-600 hover:text-[#0F172A] hover:bg-slate-100 transition-smooth"
                        aria-label="Buka menu"
                    >
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        {{-- Mobile Nav Drawer --}}
        <div
            x-show="mobileMenu"
            x-transition
            @click.away="mobileMenu = false"
            class="md:hidden border-t border-[#E2E8F0] bg-white px-4 pt-3 pb-6 space-y-3"
            style="display: none;"
        >
            <a href="{{ url('/') }}#fitur" @click="mobileMenu = false" class="block py-2 text-sm font-medium text-[#0F172A]">Fitur Unggulan</a>
            <a href="{{ url('/') }}#alur" @click="mobileMenu = false" class="block py-2 text-sm font-medium text-[#0F172A]">Alur Seleksi</a>
            <a href="{{ route('karir.index') }}" @click="mobileMenu = false" class="block py-2 text-sm font-medium text-[#0F172A]">Jelajah Lowongan</a>
            <a href="{{ url('/') }}#faq" @click="mobileMenu = false" class="block py-2 text-sm font-medium text-[#0F172A]">FAQ</a>
            <div class="pt-4 border-t border-[#E2E8F0] flex flex-col gap-2">
                <x-button href="{{ route('demo.login') }}" variant="secondary" size="md" class="w-full">
                    Masuk ke Akun
                </x-button>
                <x-button href="{{ route('karir.index') }}" variant="primary" size="md" class="w-full">
                    Cari Lowongan
                </x-button>
            </div>
        </div>
    </header>

    {{-- Main Content --}}
    <main class="flex-1">
        @yield('content')
    </main>

    {{-- Toast Global Notification --}}
    <x-toast />

    {{-- Footer Publik --}}
    <footer class="bg-white border-t border-[#E2E8F0] pt-14 pb-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-5 gap-10 pb-12 border-b border-[#E2E8F0]">
                {{-- Col 1 & 2: Brand Info --}}
                <div class="md:col-span-2 space-y-4">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-xl bg-[#2563EB] flex items-center justify-center text-white font-extrabold text-lg">
                            M
                        </div>
                        <span class="text-xl font-bold tracking-tight text-[#0F172A]">MURIALO</span>
                    </div>
                    <p class="text-xs text-[#64748B] leading-relaxed max-w-sm">
                        Platform rekrutmen terintegrasi perusahaan yang menyelaraskan seleksi CV, evaluasi skill semantik, smart grading tes esai, dan analitik talenta prediktif.
                    </p>
                    <div class="pt-2 text-xs text-[#64748B] space-y-1">
                        <p class="font-semibold text-[#0F172A]">PT Muria Logika Nusantara</p>
                        <p>Jakarta Selatan, DKI Jakarta, Indonesia</p>
                        <p>Kontak: talent@murialo.id</p>
                    </div>
                </div>

                {{-- Col 3: Solusi --}}
                <div class="space-y-3">
                    <h4 class="text-xs font-bold text-[#0F172A] uppercase tracking-wider">Modul Solusi</h4>
                    <ul class="space-y-2 text-xs text-[#64748B]">
                        <li><a href="{{ route('hr.cv_parser') }}" class="hover:text-[#2563EB] transition-smooth">Resume Parser AI</a></li>
                        <li><a href="{{ route('hr.skill_matching') }}" class="hover:text-[#2563EB] transition-smooth">Automated Skill Matching</a></li>
                        <li><a href="{{ route('hr.smart_grading.index') }}" class="hover:text-[#2563EB] transition-smooth">Smart Grading Test</a></li>
                        <li><a href="{{ route('hr.rekomendasi') }}" class="hover:text-[#2563EB] transition-smooth">Rekomendasi Kandidat</a></li>
                        <li><a href="{{ route('hr.analitik') }}" class="hover:text-[#2563EB] transition-smooth">Analitik Rekrutmen</a></li>
                    </ul>
                </div>

                {{-- Col 4: Untuk Pengguna --}}
                <div class="space-y-3">
                    <h4 class="text-xs font-bold text-[#0F172A] uppercase tracking-wider">Akses Portal</h4>
                    <ul class="space-y-2 text-xs text-[#64748B]">
                        <li><a href="{{ route('karir.index') }}" class="hover:text-[#2563EB] transition-smooth">Katalog Lowongan</a></li>
                        <li><a href="{{ route('kandidat.dashboard') }}" class="hover:text-[#2563EB] transition-smooth">Portal Kandidat</a></li>
                        <li><a href="{{ route('hr.dashboard') }}" class="hover:text-[#2563EB] transition-smooth">Dashboard HR / Recruiter</a></li>
                        <li><a href="{{ route('admin.users') }}" class="hover:text-[#2563EB] transition-smooth">Administrator Platform</a></li>
                        <li><a href="{{ route('demo.login') }}" class="hover:text-[#2563EB] transition-smooth">Ganti Peran Demo</a></li>
                    </ul>
                </div>

                {{-- Col 5: Panduan --}}
                <div class="space-y-3">
                    <h4 class="text-xs font-bold text-[#0F172A] uppercase tracking-wider">Dokumentasi Tim</h4>
                    <ul class="space-y-2 text-xs text-[#64748B]">
                        <li><span class="text-slate-500">Laravel 12 & Blade</span></li>
                        <li><span class="text-slate-500">Tailwind CSS v4 & Alpine.js</span></li>
                        <li><span class="text-slate-500">Dataset Sintetis Konsisten</span></li>
                        <li><span class="text-slate-500">Mode Simulasi Demo Aktif</span></li>
                    </ul>
                </div>
            </div>

            <div class="pt-8 flex flex-col sm:flex-row items-center justify-between gap-4 text-xs text-[#64748B]">
                <p>© {{ date('Y') }} PT Muria Logika Nusantara. Seluruh hak cipta dilindungi.</p>
                <div class="flex items-center gap-6">
                    <span class="text-[11px] text-slate-500">Branch: fitur/ui-foundation</span>
                    <span class="text-[11px] text-slate-500">Desain Sistem: MURIALO Core v1.0</span>
                </div>
            </div>
        </div>
    </footer>

    @stack('scripts')
</body>
</html>
