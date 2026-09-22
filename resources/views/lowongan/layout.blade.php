<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Murialo — Sistem rekrutmen cerdas berbasis AI. Kelola lowongan, temukan talenta terbaik.">
    <title>@yield('title') · Murialo</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/lowongan.css') }}">
</head>
<body>
    <header>
        <nav>
            <a class="brand" href="{{ auth()->check() && auth()->user()->isKandidat() ? route('kandidat.index') : route('lowongan.index') }}">murialo<span>/ Rekrutmen</span></a>
            
            @auth
                <div class="nav-links">
                    @if (auth()->user()->isHr())
                        <a href="{{ route('lowongan.index') }}" class="{{ request()->routeIs('lowongan.*') ? 'active' : '' }}">Lowongan</a>
                        <a href="{{ route('soal.index') }}" class="{{ request()->routeIs('soal.*') ? 'active' : '' }}">Bank Soal</a>
                        <a href="{{ route('paket.index') }}" class="{{ request()->routeIs('paket.*') ? 'active' : '' }}">Paket Tes</a>
                        <a href="{{ route('penugasan.index') }}" class="{{ request()->routeIs('penugasan.*') ? 'active' : '' }}">Penugasan</a>
                        <a href="{{ route('penilaian.index') }}" class="{{ request()->routeIs('penilaian.*') ? 'active' : '' }}">Hasil Tes</a>
                    @else
                        <a href="{{ route('kandidat.index') }}" class="{{ request()->routeIs('kandidat.*') ? 'active' : '' }}">Tes Saya</a>
                    @endif
                </div>

                <div class="nav-user">
                    <span class="role-tag">{{ auth()->user()->role ?? 'HR' }}</span>
                    <div class="avatar">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div>
                    <span>{{ auth()->user()->name }}</span>
                </div>
            @endauth
        </nav>
    </header>
    <main>
        @if (session('success'))
            <div class="notice" role="status">{{ session('success') }}</div>
        @endif
        @if ($errors->any())
            <div class="errors" role="alert">
                <strong>⚠ Periksa kembali isian berikut:</strong>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        @yield('content')
    </main>
    <footer>Murialo · Kelola peluang, temukan talenta terbaik.</footer>
</body>
</html>
