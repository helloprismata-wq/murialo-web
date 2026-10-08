<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'HR Dashboard') · MURIALO Enterprise</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="bg-[#F8FAFC] text-[#0F172A] font-sans antialiased min-h-screen flex selection:bg-[#2563EB] selection:text-white" x-data="{ mobileNav: false }">

    {{-- ── Desktop Sidebar (256px) ────────────────────────────────────────── --}}
    <aside class="hidden lg:flex lg:flex-col lg:w-64 bg-white border-r border-[#E2E8F0] fixed inset-y-0 z-30">
        {{-- Brand / Company Header --}}
        <div class="h-18 px-5 border-b border-[#E2E8F0] flex items-center justify-between">
            <a href="{{ route('hr.dashboard') }}" class="flex items-center gap-2.5">
                <div class="w-8 h-8 rounded-lg bg-[#2563EB] flex items-center justify-center text-white font-black text-base shadow-xs">
                    M
                </div>
                <div>
                    <span class="text-base font-bold text-[#0F172A] tracking-tight block leading-none">MURIALO</span>
                    <span class="text-[10px] font-semibold text-[#2563EB] tracking-wider uppercase">Portal Recruiter</span>
                </div>
            </a>
            <x-badge variant="neutral" size="sm">v1.0</x-badge>
        </div>

        {{-- Company Context Banner --}}
        <div class="px-4 py-3 bg-slate-50 border-b border-[#E2E8F0]">
            <p class="text-[11px] font-medium text-[#64748B]">Perusahaan:</p>
            <p class="text-xs font-semibold text-[#0F172A] truncate">PT Muria Logika Nusantara</p>
        </div>

        {{-- Navigation Menu Items --}}
        <div class="flex-1 overflow-y-auto px-3 py-4 space-y-6">
            
            {{-- Kelompok 1: Ringkasan --}}
            <div>
                <p class="px-3 text-[11px] font-bold tracking-wider text-[#94A3B8] uppercase mb-2">Ringkasan</p>
                <div class="space-y-1">
                    <a href="{{ route('hr.dashboard') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-xs font-semibold transition-smooth {{ request()->routeIs('hr.dashboard') ? 'bg-[#EFF6FF] text-[#2563EB]' : 'text-[#64748B] hover:bg-slate-50 hover:text-[#0F172A]' }}">
                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                        </svg>
                        <span>Dashboard Utama</span>
                    </a>
                </div>
            </div>

            {{-- Kelompok 2: Rekrutmen --}}
            <div>
                <p class="px-3 text-[11px] font-bold tracking-wider text-[#94A3B8] uppercase mb-2">Rekrutmen</p>
                <div class="space-y-1">
                    <a href="{{ route('hr.lowongan.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-xs font-medium transition-smooth {{ request()->routeIs('hr.lowongan.*') ? 'bg-[#EFF6FF] text-[#2563EB] font-semibold' : 'text-[#64748B] hover:bg-slate-50 hover:text-[#0F172A]' }}">
                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                        <span>Kelola Lowongan</span>
                    </a>
                    <a href="{{ route('hr.pelamar.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-xs font-medium transition-smooth {{ request()->routeIs('hr.pelamar.*') ? 'bg-[#EFF6FF] text-[#2563EB] font-semibold' : 'text-[#64748B] hover:bg-slate-50 hover:text-[#0F172A]' }}">
                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                        <span>Daftar Pelamar</span>
                    </a>
                    <a href="{{ route('hr.pipeline') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-xs font-medium transition-smooth {{ request()->routeIs('hr.pipeline') ? 'bg-[#EFF6FF] text-[#2563EB] font-semibold' : 'text-[#64748B] hover:bg-slate-50 hover:text-[#0F172A]' }}">
                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 10V7m0 10a2 2 0 002 2h2a2 2 0 002-2V7a2 2 0 00-2-2h-2a2 2 0 00-2 2" />
                        </svg>
                        <span>Pipeline Seleksi</span>
                    </a>
                </div>
            </div>

            {{-- Kelompok 3: Evaluasi & AI --}}
            <div>
                <p class="px-3 text-[11px] font-bold tracking-wider text-[#94A3B8] uppercase mb-2">Evaluasi & Asesmen</p>
                <div class="space-y-1">
                    <a href="{{ route('hr.cv_parser') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-xs font-medium transition-smooth {{ request()->routeIs('hr.cv_parser') ? 'bg-[#EFF6FF] text-[#2563EB] font-semibold' : 'text-[#64748B] hover:bg-slate-50 hover:text-[#0F172A]' }}">
                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <span>Resume Parser AI</span>
                    </a>
                    <a href="{{ route('hr.skill_matching') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-xs font-medium transition-smooth {{ request()->routeIs('hr.skill_matching') ? 'bg-[#EFF6FF] text-[#2563EB] font-semibold' : 'text-[#64748B] hover:bg-slate-50 hover:text-[#0F172A]' }}">
                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                        <span>Skill Matching (S-BERT)</span>
                    </a>
                    <a href="{{ route('hr.smart_grading.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-xs font-medium transition-smooth {{ request()->routeIs('hr.smart_grading.*') ? 'bg-[#EFF6FF] text-[#2563EB] font-semibold' : 'text-[#64748B] hover:bg-slate-50 hover:text-[#0F172A]' }}">
                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                        </svg>
                        <span>Smart Grading Esai</span>
                    </a>
                </div>
            </div>

            {{-- Kelompok 4: Insight & Analitik --}}
            <div>
                <p class="px-3 text-[11px] font-bold tracking-wider text-[#94A3B8] uppercase mb-2">Insight Rekrutmen</p>
                <div class="space-y-1">
                    <a href="{{ route('hr.rekomendasi') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-xs font-medium transition-smooth {{ request()->routeIs('hr.rekomendasi') ? 'bg-[#EFF6FF] text-[#2563EB] font-semibold' : 'text-[#64748B] hover:bg-slate-50 hover:text-[#0F172A]' }}">
                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                        </svg>
                        <span>Rekomendasi Kandidat</span>
                    </a>
                    <a href="{{ route('hr.anomali') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-xs font-medium transition-smooth {{ request()->routeIs('hr.anomali') ? 'bg-[#EFF6FF] text-[#2563EB] font-semibold' : 'text-[#64748B] hover:bg-slate-50 hover:text-[#0F172A]' }}">
                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                        <span>Deteksi Anomali</span>
                    </a>
                    <a href="{{ route('hr.analitik') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-xs font-medium transition-smooth {{ request()->routeIs('hr.analitik') ? 'bg-[#EFF6FF] text-[#2563EB] font-semibold' : 'text-[#64748B] hover:bg-slate-50 hover:text-[#0F172A]' }}">
                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z" />
                        </svg>
                        <span>Analitik & Proyeksi</span>
                    </a>
                </div>
            </div>

            {{-- Kelompok 5: Administrasi --}}
            <div>
                <p class="px-3 text-[11px] font-bold tracking-wider text-[#94A3B8] uppercase mb-2">Administrasi</p>
                <div class="space-y-1">
                    <a href="{{ route('admin.users') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-xs font-medium transition-smooth text-[#64748B] hover:bg-slate-50 hover:text-[#0F172A]">
                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        <span>Area Administrator</span>
                    </a>
                </div>
            </div>

        </div>

        {{-- Sidebar Footer: Current User Card --}}
        <div class="p-3 border-t border-[#E2E8F0]">
            <div class="flex items-center gap-3 p-2 rounded-xl bg-slate-50 border border-[#E2E8F0]">
                <div class="w-8 h-8 rounded-lg bg-[#2563EB] text-white font-bold text-xs flex items-center justify-center shrink-0">
                    HR
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-xs font-semibold text-[#0F172A] truncate">HRD Murialo</p>
                    <p class="text-[11px] text-[#64748B] truncate">hrd@murialo.test</p>
                </div>
            </div>
        </div>
    </aside>

    {{-- ── Main Layout Wrapper (Offset desktop 256px) ──────────────────────── --}}
    <div class="lg:pl-64 flex flex-col flex-1 min-w-0">
        
        {{-- Topbar Header (64 - 72px) --}}
        <header class="h-18 sticky top-0 z-20 bg-white/90 backdrop-blur-md border-b border-[#E2E8F0] px-4 sm:px-8 flex items-center justify-between gap-4">
            
            {{-- Mobile Drawer Toggle & Breadcrumb --}}
            <div class="flex items-center gap-3">
                <button
                    type="button"
                    @click="mobileNav = true"
                    class="lg:hidden p-2 rounded-lg text-slate-600 hover:text-[#0F172A] hover:bg-slate-100"
                    aria-label="Buka navigasi mobile"
                >
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>

                <div>
                    <div class="flex items-center gap-2 text-xs text-[#64748B]">
                        <span>Portal HR</span>
                        <span>/</span>
                        <span class="text-[#0F172A] font-semibold">@yield('page_title', 'Ringkasan')</span>
                    </div>
                    <h1 class="text-base sm:text-lg font-bold text-[#0F172A] tracking-tight">
                        @yield('header_title', 'Dashboard HR')
                    </h1>
                </div>
            </div>

            {{-- Topbar Actions: Demo Switcher & Profile --}}
            <div class="flex items-center gap-3">
                {{-- Quick Demo Role Switcher Dropdown --}}
                <div x-data="{ open: false }" class="relative">
                    <button
                        type="button"
                        @click="open = !open"
                        class="px-2.5 py-1.5 rounded-lg border border-[#E2E8F0] bg-slate-50 hover:bg-slate-100 text-xs font-semibold text-[#0F172A] flex items-center gap-2 transition-smooth"
                    >
                        <span class="w-2 h-2 rounded-full bg-blue-500"></span>
                        <span class="hidden sm:inline">Peran:</span>
                        <span class="text-[#2563EB]">HR Lead</span>
                        <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>

                    <div
                        x-show="open"
                        @click.away="open = false"
                        x-transition
                        style="display: none;"
                        class="absolute right-0 mt-1.5 w-48 bg-white rounded-xl border border-[#E2E8F0] shadow-xl py-1.5 z-50 text-xs"
                    >
                        <div class="px-3 py-1.5 text-[11px] font-bold text-slate-400 uppercase tracking-wider">Ganti Simulasi Peran</div>
                        <a href="{{ route('hr.dashboard') }}" class="block px-3 py-2 text-[#2563EB] bg-[#EFF6FF] font-semibold">HR / Recruiter (Aktif)</a>
                        <a href="{{ route('kandidat.dashboard') }}" class="block px-3 py-2 text-[#0F172A] hover:bg-slate-50">Kandidat (Budi Santoso)</a>
                        <a href="{{ route('admin.users') }}" class="block px-3 py-2 text-[#0F172A] hover:bg-slate-50">Administrator</a>
                        <div class="border-t border-[#E2E8F0] my-1"></div>
                        <a href="{{ url('/') }}" class="block px-3 py-2 text-slate-600 hover:bg-slate-50">Landing Page Publik</a>
                    </div>
                </div>

                {{-- Notifikasi Simulator --}}
                <div class="relative" x-data="{ notifOpen: false }">
                    <button
                        type="button"
                        @click="notifOpen = !notifOpen"
                        class="p-2 rounded-lg border border-[#E2E8F0] text-slate-600 hover:text-[#0F172A] hover:bg-slate-50 relative transition-smooth"
                        aria-label="Notifikasi"
                    >
                        <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                        </svg>
                        <span class="absolute top-1.5 right-1.5 w-2 h-2 rounded-full bg-blue-600 ring-2 ring-white"></span>
                    </button>

                    <div
                        x-show="notifOpen"
                        @click.away="notifOpen = false"
                        x-transition
                        style="display: none;"
                        class="absolute right-0 mt-1.5 w-72 bg-white rounded-xl border border-[#E2E8F0] shadow-xl py-2 z-50 text-xs"
                    >
                        <div class="px-4 py-2 border-b border-[#E2E8F0] flex items-center justify-between">
                            <span class="font-bold text-[#0F172A]">Notifikasi Baru</span>
                            <span class="text-[11px] text-[#2563EB]">2 Belum Dibaca</span>
                        </div>
                        <div class="divide-y divide-[#E2E8F0]">
                            <div class="p-3 hover:bg-slate-50 transition-smooth">
                                <p class="font-semibold text-[#0F172A]">Tes Esai Baru Diserahkan</p>
                                <p class="text-[#64748B] text-[11px] mt-0.5">Budi Santoso telah menyelesaikan Paket A. Siap untuk peninjauan.</p>
                                <span class="text-[10px] text-slate-400 mt-1 block">4 jam yang lalu</span>
                            </div>
                            <div class="p-3 hover:bg-slate-50 transition-smooth">
                                <p class="font-semibold text-[#0F172A]">Temuan Anomali Terdeteksi</p>
                                <p class="text-[#64748B] text-[11px] mt-0.5">Pola pengerjaan tes abnormal terdeteksi pada 1 kandidat.</p>
                                <span class="text-[10px] text-slate-400 mt-1 block">6 jam yang lalu</span>
                            </div>
                        </div>
                    </div>
                </div>

                <a href="{{ url('/') }}" class="text-xs font-semibold text-[#64748B] hover:text-[#2563EB] px-2 py-1">
                    Keluar Demo
                </a>
            </div>
        </header>

        {{-- Mobile Drawer Navigation --}}
        <div
            x-show="mobileNav"
            style="display: none;"
            class="fixed inset-0 z-50 lg:hidden"
        >
            <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-xs" @click="mobileNav = false"></div>
            <div class="fixed inset-y-0 left-0 w-72 bg-white border-r border-[#E2E8F0] shadow-2xl p-5 overflow-y-auto space-y-6">
                <div class="flex items-center justify-between pb-4 border-b border-[#E2E8F0]">
                    <div class="flex items-center gap-2">
                        <div class="w-8 h-8 rounded-lg bg-[#2563EB] text-white font-black flex items-center justify-center">M</div>
                        <span class="font-bold text-[#0F172A]">MURIALO HR</span>
                    </div>
                    <button type="button" @click="mobileNav = false" class="p-1 rounded-md text-slate-500 hover:text-slate-900">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <nav class="space-y-4 text-xs font-medium text-[#64748B]">
                    <a href="{{ route('hr.dashboard') }}" class="block p-2 rounded-lg {{ request()->routeIs('hr.dashboard') ? 'bg-[#EFF6FF] text-[#2563EB] font-bold' : '' }}">Dashboard Utama</a>
                    <a href="{{ route('hr.lowongan.index') }}" class="block p-2 rounded-lg {{ request()->routeIs('hr.lowongan.*') ? 'bg-[#EFF6FF] text-[#2563EB] font-bold' : '' }}">Kelola Lowongan</a>
                    <a href="{{ route('hr.pelamar.index') }}" class="block p-2 rounded-lg {{ request()->routeIs('hr.pelamar.*') ? 'bg-[#EFF6FF] text-[#2563EB] font-bold' : '' }}">Daftar Pelamar</a>
                    <a href="{{ route('hr.pipeline') }}" class="block p-2 rounded-lg {{ request()->routeIs('hr.pipeline') ? 'bg-[#EFF6FF] text-[#2563EB] font-bold' : '' }}">Pipeline Seleksi</a>
                    <a href="{{ route('hr.cv_parser') }}" class="block p-2 rounded-lg {{ request()->routeIs('hr.cv_parser') ? 'bg-[#EFF6FF] text-[#2563EB] font-bold' : '' }}">Resume Parser AI</a>
                    <a href="{{ route('hr.skill_matching') }}" class="block p-2 rounded-lg {{ request()->routeIs('hr.skill_matching') ? 'bg-[#EFF6FF] text-[#2563EB] font-bold' : '' }}">Skill Matching (S-BERT)</a>
                    <a href="{{ route('hr.smart_grading.index') }}" class="block p-2 rounded-lg {{ request()->routeIs('hr.smart_grading.*') ? 'bg-[#EFF6FF] text-[#2563EB] font-bold' : '' }}">Smart Grading Esai</a>
                    <a href="{{ route('hr.rekomendasi') }}" class="block p-2 rounded-lg {{ request()->routeIs('hr.rekomendasi') ? 'bg-[#EFF6FF] text-[#2563EB] font-bold' : '' }}">Rekomendasi Kandidat</a>
                    <a href="{{ route('hr.anomali') }}" class="block p-2 rounded-lg {{ request()->routeIs('hr.anomali') ? 'bg-[#EFF6FF] text-[#2563EB] font-bold' : '' }}">Deteksi Anomali</a>
                    <a href="{{ route('hr.analitik') }}" class="block p-2 rounded-lg {{ request()->routeIs('hr.analitik') ? 'bg-[#EFF6FF] text-[#2563EB] font-bold' : '' }}">Analitik & Proyeksi</a>
                </nav>
            </div>
        </div>

        {{-- Content Area --}}
        <main class="flex-1 p-4 sm:p-8 max-w-7xl w-full mx-auto">
            @yield('content')
        </main>

        {{-- HR Footer --}}
        <footer class="py-4 px-8 border-t border-[#E2E8F0] bg-white text-xs text-[#64748B] flex flex-col sm:flex-row items-center justify-between gap-2">
            <span>© {{ date('Y') }} PT Muria Logika Nusantara · Platform Rekrutmen Terpadu</span>
            <div class="flex items-center gap-3 text-[11px]">
                <span class="inline-flex items-center gap-1.5 text-emerald-600">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> AI Engine Online (Simulasi Port 8001)
                </span>
                <span class="text-slate-400">|</span>
                <span class="text-slate-500">Mode Demo Stabil</span>
            </div>
        </footer>

    </div>

    <x-toast />
    @stack('scripts')
</body>
</html>
