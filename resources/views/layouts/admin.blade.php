<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Platform') · MURIALO Administrator</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="bg-[#F8FAFC] text-[#0F172A] font-sans antialiased min-h-screen flex selection:bg-[#2563EB] selection:text-white" x-data="{ mobileNav: false }">

    {{-- Admin Sidebar (256px) --}}
    <aside class="hidden lg:flex lg:flex-col lg:w-64 bg-slate-900 text-slate-300 fixed inset-y-0 z-30">
        <div class="h-18 px-5 border-b border-slate-800 flex items-center justify-between">
            <a href="{{ route('admin.users') }}" class="flex items-center gap-2.5">
                <div class="w-8 h-8 rounded-lg bg-purple-600 flex items-center justify-center text-white font-black text-base shadow-xs">
                    M
                </div>
                <div>
                    <span class="text-base font-bold text-white tracking-tight block leading-none">MURIALO</span>
                    <span class="text-[10px] font-semibold text-purple-400 tracking-wider uppercase">Platform Admin</span>
                </div>
            </a>
            <span class="px-2 py-0.5 rounded text-[10px] font-semibold bg-purple-500/20 text-purple-300 border border-purple-500/30">Superadmin</span>
        </div>

        <div class="px-4 py-3 bg-slate-950/60 border-b border-slate-800">
            <p class="text-[11px] text-slate-400">Instansi / Tenant:</p>
            <p class="text-xs font-semibold text-white truncate">PT Muria Logika Nusantara</p>
        </div>

        <div class="flex-1 overflow-y-auto px-3 py-4 space-y-6">
            <div>
                <p class="px-3 text-[11px] font-bold tracking-wider text-slate-400 uppercase mb-2">Administrasi Sistem</p>
                <div class="space-y-1">
                    <a href="{{ route('admin.users') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-xs font-medium transition-smooth {{ request()->routeIs('admin.users*') ? 'bg-purple-600 text-white font-semibold' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                        <span>Pengelolaan Pengguna</span>
                    </a>
                    <a href="{{ route('admin.roles') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-xs font-medium transition-smooth {{ request()->routeIs('admin.roles*') ? 'bg-purple-600 text-white font-semibold' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                        </svg>
                        <span>Role & Hak Akses</span>
                    </a>
                    <a href="{{ route('admin.settings') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-xs font-medium transition-smooth {{ request()->routeIs('admin.settings*') ? 'bg-purple-600 text-white font-semibold' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        <span>Pengaturan Platform</span>
                    </a>
                    <a href="{{ route('admin.audit_log') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-xs font-medium transition-smooth {{ request()->routeIs('admin.audit_log*') ? 'bg-purple-600 text-white font-semibold' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <span>Audit Log Keamanan</span>
                    </a>
                </div>
            </div>

            <div>
                <p class="px-3 text-[11px] font-bold tracking-wider text-slate-400 uppercase mb-2">Pintasan Lain</p>
                <div class="space-y-1">
                    <a href="{{ route('hr.dashboard') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-xs font-medium text-blue-400 hover:bg-slate-800 hover:text-blue-300">
                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                        </svg>
                        <span>Masuk Portal HR</span>
                    </a>
                    <a href="{{ url('/') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-xs font-medium text-slate-400 hover:bg-slate-800 hover:text-white">
                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        <span>Ke Landing Page</span>
                    </a>
                </div>
            </div>
        </div>

        <div class="p-3 border-t border-slate-800">
            <div class="flex items-center gap-3 p-2 rounded-xl bg-slate-950/70 border border-slate-800">
                <div class="w-8 h-8 rounded-lg bg-purple-600 text-white font-bold text-xs flex items-center justify-center">
                    AD
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-xs font-semibold text-white truncate">Administrator Murialo</p>
                    <p class="text-[11px] text-slate-400 truncate">admin@murialo.test</p>
                </div>
            </div>
        </div>
    </aside>

    {{-- Main Admin Wrapper --}}
    <div class="lg:pl-64 flex flex-col flex-1 min-w-0">
        <header class="h-18 sticky top-0 z-20 bg-white border-b border-[#E2E8F0] px-4 sm:px-8 flex items-center justify-between">
            <div>
                <div class="text-xs text-[#64748B]">Platform Admin / <span class="text-[#0F172A] font-semibold">@yield('page_title', 'Pengaturan')</span></div>
                <h1 class="text-base sm:text-lg font-bold text-[#0F172A]">@yield('header_title', 'Administrasi Platform')</h1>
            </div>

            <div class="flex items-center gap-3">
                <x-badge variant="purple" size="md">Mode Administrator</x-badge>
                <a href="{{ route('hr.dashboard') }}" class="text-xs font-semibold text-[#2563EB] hover:underline">
                    Ke Portal HR →
                </a>
            </div>
        </header>

        <main class="flex-1 p-4 sm:p-8 max-w-7xl w-full mx-auto">
            @yield('content')
        </main>

        <footer class="py-4 px-8 border-t border-[#E2E8F0] bg-white text-xs text-[#64748B]">
            © {{ date('Y') }} PT Muria Logika Nusantara · Area Administrasi Platform Rekrutmen Terpadu
        </footer>
    </div>

    <x-toast />
    @stack('scripts')
</body>
</html>
