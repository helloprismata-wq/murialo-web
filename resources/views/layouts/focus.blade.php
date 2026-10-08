<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Pengerjaan Tes Esai') · MURIALO Exam Focus</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="bg-[#F8FAFC] text-[#0F172A] font-sans antialiased min-h-screen flex flex-col selection:bg-[#2563EB] selection:text-white">

    {{-- Focus Header Bar --}}
    <header class="h-16 bg-white border-b border-[#E2E8F0] px-4 sm:px-8 flex items-center justify-between sticky top-0 z-40 shadow-2xs">
        {{-- Exam & Candidate Info --}}
        <div class="flex items-center gap-3">
            <div class="w-8 h-8 rounded-lg bg-[#2563EB] text-white font-extrabold flex items-center justify-center text-sm shadow-xs">
                M
            </div>
            <div>
                <h2 class="text-xs sm:text-sm font-bold text-[#0F172A] leading-tight">@yield('exam_name', 'Evaluasi Tes Esai Online')</h2>
                <p class="text-[11px] text-[#64748B]">Kandidat: <span class="font-semibold text-[#0F172A]">Budi Santoso</span> · PT Muria Logika Nusantara</p>
            </div>
        </div>

        {{-- Center Autosave & Security Badge --}}
        <div class="hidden md:flex items-center gap-3 text-xs">
            <div class="flex items-center gap-1.5 px-3 py-1 rounded-full bg-slate-100 text-[#475569] border border-slate-200">
                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                <span id="autosave-status">Autosave aktif: Tersimpan otomatis</span>
            </div>
            <span class="text-slate-300">|</span>
            <span class="text-[11px] text-[#64748B] flex items-center gap-1">
                <svg class="w-3.5 h-3.5 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 1.944A11.954 11.954 0 012.166 5C2.056 5.649 2 6.319 2 7c0 5.225 3.34 9.67 8 11.317C14.66 16.67 18 12.225 18 7c0-.682-.057-1.35-.166-2.001A11.954 11.954 0 0110 1.944zM11 14a1 1 0 11-2 0 1 1 0 012 0zm0-7a1 1 0 10-2 0v3a1 1 0 102 0V7z" clip-rule="evenodd" />
                </svg>
                Sesi Ujian Aman Terproteksi
            </span>
        </div>

        {{-- Timer Indicator --}}
        <div class="flex items-center gap-3">
            @yield('header_timer')
        </div>
    </header>

    {{-- Main Focus Exam Container --}}
    <main class="flex-1 flex flex-col">
        @yield('content')
    </main>

    <x-toast />
    @stack('scripts')
</body>
</html>
