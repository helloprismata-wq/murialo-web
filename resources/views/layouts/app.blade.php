<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="@yield('meta_description', 'Murialo — Sistem Rekrutmen Berbasis AI. Temukan kandidat terbaik dengan kecerdasan buatan.')">
    <title>@yield('title', 'Murialo') | Sistem Rekrutmen AI</title>

    {{-- Google Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --bg-dark:     #0a0e1a;
            --bg-card:     #111827;
            --bg-card-2:   #1a2235;
            --accent-1:    #6366f1;  /* indigo */
            --accent-2:    #8b5cf6;  /* violet */
            --accent-3:    #06b6d4;  /* cyan */
            --text-primary:  #f0f4ff;
            --text-secondary: #94a3b8;
            --text-muted:  #64748b;
            --border:      #1e2d45;
            --success:     #10b981;
            --error:       #ef4444;
            --warning:     #f59e0b;
            --radius:      12px;
            --radius-lg:   20px;
            --shadow:      0 4px 24px rgba(0,0,0,0.4);
        }

        html { scroll-behavior: smooth; }

        body {
            font-family: 'Inter', sans-serif;
            background: var(--bg-dark);
            color: var(--text-primary);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            line-height: 1.6;
        }

        /* ── Navbar ── */
        .navbar {
            position: sticky;
            top: 0;
            z-index: 100;
            padding: 0 2rem;
            height: 64px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: rgba(10, 14, 26, 0.85);
            backdrop-filter: blur(20px);
            border-bottom: 1px solid var(--border);
        }

        .navbar-brand {
            display: flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
        }

        .navbar-logo {
            width: 36px;
            height: 36px;
            background: linear-gradient(135deg, var(--accent-1), var(--accent-2));
            border-radius: 10px;
            display: grid;
            place-items: center;
            font-weight: 800;
            font-size: 16px;
            color: white;
        }

        .navbar-name {
            font-size: 1.2rem;
            font-weight: 700;
            background: linear-gradient(135deg, var(--accent-1), var(--accent-3));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .navbar-nav { display: flex; align-items: center; gap: 8px; }

        .nav-link {
            padding: 8px 16px;
            border-radius: 8px;
            text-decoration: none;
            color: var(--text-secondary);
            font-size: 0.875rem;
            font-weight: 500;
            transition: all 0.2s;
        }

        .nav-link:hover, .nav-link.active {
            background: rgba(99, 102, 241, 0.12);
            color: var(--text-primary);
        }

        .btn-nav {
            padding: 8px 18px;
            border-radius: 8px;
            text-decoration: none;
            font-size: 0.875rem;
            font-weight: 600;
            background: linear-gradient(135deg, var(--accent-1), var(--accent-2));
            color: white;
            transition: all 0.2s;
            border: none;
            cursor: pointer;
        }

        .btn-nav:hover { opacity: 0.88; transform: translateY(-1px); }

        /* ── Main content ── */
        main { flex: 1; padding: 2rem; max-width: 1200px; margin: 0 auto; width: 100%; }

        /* ── Alerts flash ── */
        .alert {
            padding: 14px 18px;
            border-radius: var(--radius);
            margin-bottom: 1.5rem;
            font-size: 0.9rem;
            display: flex;
            align-items: flex-start;
            gap: 10px;
            border: 1px solid;
        }

        .alert-success {
            background: rgba(16, 185, 129, 0.1);
            border-color: rgba(16, 185, 129, 0.3);
            color: #6ee7b7;
        }

        .alert-error {
            background: rgba(239, 68, 68, 0.1);
            border-color: rgba(239, 68, 68, 0.3);
            color: #fca5a5;
        }

        .alert-icon { font-size: 1.1rem; flex-shrink: 0; margin-top: 1px; }

        /* ── Footer ── */
        footer {
            padding: 1.5rem 2rem;
            text-align: center;
            color: var(--text-muted);
            font-size: 0.8rem;
            border-top: 1px solid var(--border);
        }

        footer a { color: var(--accent-3); text-decoration: none; }
        footer a:hover { text-decoration: underline; }

        /* ── Utilities ── */
        .text-gradient {
            background: linear-gradient(135deg, var(--accent-1), var(--accent-3));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        .fade-in { animation: fadeInUp 0.5s ease forwards; }
    </style>

    @stack('styles')
</head>
<body>

    {{-- ── Navbar ─────────────────────────────────────────── --}}
    <nav class="navbar">
        <a href="{{ url('/') }}" class="navbar-brand">
            <div class="navbar-logo">M</div>
            <span class="navbar-name">Murialo</span>
        </a>

        <div class="navbar-nav">
            <a href="{{ route('cv.create') }}"
               class="nav-link {{ request()->routeIs('cv.create') ? 'active' : '' }}">
                Upload CV
            </a>
            <a href="{{ route('cv.index') }}"
               class="nav-link {{ request()->routeIs('cv.*') && !request()->routeIs('cv.create') ? 'active' : '' }}">
                Manajemen CV
            </a>
        </div>
    </nav>

    {{-- ── Flash Messages ────────────────────────────────── --}}
    <div style="max-width:1200px;margin:1.5rem auto 0;padding:0 2rem;width:100%">
        @if(session('success'))
            <div class="alert alert-success fade-in">
                <span class="alert-icon">✅</span>
                <span>{!! session('success') !!}</span>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-error fade-in">
                <span class="alert-icon">❌</span>
                <span>{!! session('error') !!}</span>
            </div>
        @endif
    </div>

    {{-- ── Main Content ─────────────────────────────────── --}}
    <main>
        @yield('content')
    </main>

    {{-- ── Footer ───────────────────────────────────────── --}}
    <footer>
        <p>© {{ date('Y') }} <strong>Murialo</strong> — Sistem Rekrutmen Berbasis AI &nbsp;|&nbsp;
           Dibangun dengan ❤️ oleh Tim Murialo</p>
    </footer>

    @stack('scripts')
</body>
</html>
