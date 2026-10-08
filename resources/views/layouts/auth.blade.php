<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Autentikasi') · MURIALO</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="bg-[#F8FAFC] text-[#0F172A] font-sans antialiased min-h-screen flex flex-col justify-between selection:bg-[#2563EB] selection:text-white">

    {{-- Top Navigation Bar --}}
    <header class="py-5 px-6 max-w-7xl mx-auto w-full flex items-center justify-between">
        <a href="{{ url('/') }}" class="flex items-center gap-2.5 group">
            <div class="w-8 h-8 rounded-lg bg-[#2563EB] flex items-center justify-center text-white font-extrabold text-base shadow-xs group-hover:scale-105 transition-smooth">
                M
            </div>
            <span class="text-lg font-bold text-[#0F172A] tracking-tight">MURIALO</span>
        </a>

        <a href="{{ url('/') }}" class="text-xs font-semibold text-[#64748B] hover:text-[#2563EB] flex items-center gap-1.5 transition-smooth">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            <span>Kembali ke Beranda</span>
        </a>
    </header>

    {{-- Main Container --}}
    <main class="flex-1 flex items-center justify-center p-4 sm:p-6 my-4">
        <div class="w-full max-w-md">
            @yield('content')
        </div>
    </main>

    {{-- Footer --}}
    <footer class="py-6 text-center text-xs text-[#64748B] border-t border-[#E2E8F0]">
        <p>© {{ date('Y') }} PT Muria Logika Nusantara · Prototipe Desain Platform Rekrutmen</p>
    </footer>

    <x-toast />
    @stack('scripts')
</body>
</html>
