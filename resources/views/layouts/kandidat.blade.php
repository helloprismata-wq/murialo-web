<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Portal Kandidat') · MURIALO</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="bg-[#F8FAFC] text-[#0F172A] font-sans antialiased min-h-screen flex flex-col selection:bg-[#2563EB] selection:text-white" x-data="{ mobileMenu: false }">

    {{-- Topbar Demo Switcher Bar --}}
    <aside aria-label="Demo Bar" class="bg-slate-900 text-slate-300 text-xs py-1.5 px-4 border-b border-slate-800">
        <div class="max-w-7xl mx-auto flex items-center justify-between text-[11px]">
            <div class="flex items-center gap-2">
                <span class="px-2 py-0.5 rounded font-semibold bg-emerald-600 text-white">PORTAL KANDIDAT</span>
                <span class="text-slate-400 hidden sm:inline">Simulasi Pelamar: Budi Santoso</span>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('hr.dashboard') }}" class="text-blue-400 hover:text-blue-300 font-medium">Beralih ke Portal HR →</a>
                <a href="{{ url('/') }}" class="text-slate-400 hover:text-white">Beranda</a>
            </div>
        </div>
    </aside>

    {{-- Main Navbar Kandidat --}}
    <header class="bg-white border-b border-[#E2E8F0] sticky top-0 z-30">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-18">
                
                {{-- Brand --}}
                <div class="flex items-center gap-8">
                    <a href="{{ route('kandidat.dashboard') }}" class="flex items-center gap-2.5">
                        <div class="w-9 h-9 rounded-xl bg-[#2563EB] flex items-center justify-center text-white font-extrabold text-lg shadow-2xs">
                            M
                        </div>
                        <div>
                            <span class="text-lg font-bold text-[#0F172A] tracking-tight block leading-none">MURIALO</span>
                            <span class="text-[10px] font-medium text-[#64748B]">Kandidat Talenta</span>
                        </div>
                    </a>

                    {{-- Desktop Links --}}
                    <nav class="hidden md:flex items-center gap-6 text-sm font-medium text-[#64748B]">
                        <a href="{{ route('kandidat.dashboard') }}" class="hover:text-[#2563EB] transition-smooth {{ request()->routeIs('kandidat.dashboard') ? 'text-[#2563EB] font-semibold' : '' }}">Dashboard</a>
                        <a href="{{ route('kandidat.lowongan') }}" class="hover:text-[#2563EB] transition-smooth {{ request()->routeIs('kandidat.lowongan*') ? 'text-[#2563EB] font-semibold' : '' }}">Jelajah Lowongan</a>
                        <a href="{{ route('kandidat.lamaran.index') }}" class="hover:text-[#2563EB] transition-smooth {{ request()->routeIs('kandidat.lamaran*') ? 'text-[#2563EB] font-semibold' : '' }}">Lamaran Saya</a>
                        <a href="{{ route('kandidat.cv.index') }}" class="hover:text-[#2563EB] transition-smooth {{ request()->routeIs('kandidat.cv*') ? 'text-[#2563EB] font-semibold' : '' }}">CV & Skill Saya</a>
                        <a href="{{ route('kandidat.tes.index') }}" class="hover:text-[#2563EB] transition-smooth {{ request()->routeIs('kandidat.tes*') ? 'text-[#2563EB] font-semibold' : '' }}">Tugas Tes</a>
                    </nav>
                </div>

                {{-- User Profile Pill --}}
                <div class="hidden md:flex items-center gap-4">
                    <a href="{{ route('kandidat.profil') }}" class="flex items-center gap-3 p-1.5 pl-3 rounded-full border border-[#E2E8F0] hover:bg-slate-50 transition-smooth group">
                        <div class="text-right">
                            <p class="text-xs font-bold text-[#0F172A] group-hover:text-[#2563EB] leading-tight">Budi Santoso</p>
                            <span class="text-[10px] text-emerald-600 font-semibold">Profil 95%</span>
                        </div>
                        <div class="w-8 h-8 rounded-full bg-gradient-to-tr from-[#2563EB] to-indigo-600 text-white font-bold text-xs flex items-center justify-center">
                            BS
                        </div>
                    </a>
                </div>

                {{-- Mobile Menu Button --}}
                <div class="flex md:hidden">
                    <button type="button" @click="mobileMenu = !mobileMenu" class="p-2 rounded-lg text-slate-600 hover:text-slate-900">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        {{-- Mobile Drawer --}}
        <div x-show="mobileMenu" @click.away="mobileMenu = false" class="md:hidden border-t border-[#E2E8F0] bg-white px-4 py-4 space-y-2 text-sm font-medium">
            <a href="{{ route('kandidat.dashboard') }}" class="block py-2 text-[#0F172A]">Dashboard</a>
            <a href="{{ route('kandidat.lowongan') }}" class="block py-2 text-[#0F172A]">Jelajah Lowongan</a>
            <a href="{{ route('kandidat.lamaran.index') }}" class="block py-2 text-[#0F172A]">Lamaran Saya</a>
            <a href="{{ route('kandidat.cv.index') }}" class="block py-2 text-[#0F172A]">CV & Skill Saya</a>
            <a href="{{ route('kandidat.tes.index') }}" class="block py-2 text-[#0F172A]">Tugas Tes</a>
            <a href="{{ route('kandidat.profil') }}" class="block py-2 text-[#2563EB] font-semibold border-t border-[#E2E8F0] pt-3">Profil & Pengaturan Akun</a>
        </div>
    </header>

    {{-- Content --}}
    <main class="flex-1 max-w-7xl w-full mx-auto p-4 sm:p-6 lg:p-8">
        @yield('content')
    </main>

    {{-- Footer --}}
    <footer class="bg-white border-t border-[#E2E8F0] py-6 text-center text-xs text-[#64748B]">
        <div class="max-w-7xl mx-auto px-4 flex flex-col sm:flex-row items-center justify-between gap-3">
            <p>© {{ date('Y') }} MURIALO · Portal Karir & Seleksi Perusahaan</p>
            <div class="flex items-center gap-4 text-[11px]">
                <a href="{{ route('kandidat.profil') }}" class="hover:text-[#2563EB]">Pengaturan Privasi</a>
                <span>·</span>
                <a href="{{ route('demo.login') }}" class="hover:text-[#2563EB]">Ganti Peran Demo</a>
            </div>
        </div>
    </footer>

    <x-toast />
    @stack('scripts')
</body>
</html>
