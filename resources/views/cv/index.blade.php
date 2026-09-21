@extends('layouts.app')

@section('title', 'Manajemen CV Masuk')
@section('meta_description', 'Kelola dan pantau semua CV pelamar yang masuk ke sistem Murialo.')

@push('styles')
<style>
    /* ── Header section ── */
    .page-header {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 1rem;
        margin-bottom: 2rem;
        animation: fadeInUp 0.5s ease;
    }

    .page-header-left h1 {
        font-size: 1.75rem;
        font-weight: 800;
        margin-bottom: 6px;
    }

    .page-header-sub {
        display: flex;
        align-items: center;
        gap: 12px;
        flex-wrap: wrap;
    }

    .page-header-sub p {
        color: var(--text-secondary);
        font-size: 0.9rem;
        margin: 0;
    }

    .badge-ai-server {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 3px 10px;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
    }

    .badge-ai-server.online {
        background: rgba(16, 185, 129, 0.15);
        color: #34d399;
        border: 1px solid rgba(16, 185, 129, 0.3);
    }

    .badge-ai-server.offline {
        background: rgba(239, 68, 68, 0.15);
        color: #f87171;
        border: 1px solid rgba(239, 68, 68, 0.3);
    }

    .btn-primary {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 10px 20px;
        background: linear-gradient(135deg, var(--accent-1), var(--accent-2));
        color: white;
        text-decoration: none;
        border-radius: 10px;
        font-size: 0.875rem;
        font-weight: 600;
        transition: all 0.2s;
        border: none;
        cursor: pointer;
        font-family: inherit;
    }

    .btn-primary:hover { opacity: 0.88; transform: translateY(-1px); }

    /* ── Stats bar ── */
    .stats-bar {
        display: flex;
        gap: 1rem;
        margin-bottom: 1.5rem;
        flex-wrap: wrap;
    }

    .stat-card {
        background: var(--bg-card);
        border: 1px solid var(--border);
        border-radius: var(--radius);
        padding: 1rem 1.5rem;
        flex: 1;
        min-width: 160px;
        position: relative;
        overflow: hidden;
    }

    .stat-card::before {
        content: '';
        position: absolute;
        left: 0; top: 0; bottom: 0;
        width: 3px;
        background: linear-gradient(180deg, var(--accent-1), var(--accent-2));
    }

    .stat-value { font-size: 1.75rem; font-weight: 800; }
    .stat-label { font-size: 0.8rem; color: var(--text-secondary); margin-top: 2px; }

    /* ── Search/filter bar ── */
    .toolbar {
        display: flex;
        align-items: center;
        gap: 1rem;
        margin-bottom: 1.25rem;
        flex-wrap: wrap;
    }

    .search-wrap {
        flex: 1;
        min-width: 240px;
        position: relative;
    }

    .search-icon {
        position: absolute;
        left: 14px; top: 50%;
        transform: translateY(-50%);
        color: var(--text-muted);
        pointer-events: none;
    }

    .search-input {
        width: 100%;
        padding: 10px 14px 10px 40px;
        background: var(--bg-card);
        border: 1px solid var(--border);
        border-radius: 10px;
        color: var(--text-primary);
        font-size: 0.875rem;
        font-family: inherit;
        outline: none;
        transition: border-color 0.2s;
    }

    .search-input:focus { border-color: var(--accent-1); }

    /* ── Table container ── */
    .table-wrap {
        background: var(--bg-card);
        border: 1px solid var(--border);
        border-radius: var(--radius-lg);
        overflow: hidden;
        box-shadow: var(--shadow-sm);
        animation: fadeInUp 0.5s ease 0.1s both;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        font-size: 0.875rem;
    }

    thead th {
        background: var(--bg-card-2);
        padding: 14px 16px;
        text-align: left;
        font-weight: 600;
        color: var(--text-secondary);
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        border-bottom: 1px solid var(--border);
    }

    tbody td {
        padding: 14px 16px;
        border-bottom: 1px solid var(--border);
        vertical-align: middle;
    }

    tbody tr:last-child td { border-bottom: none; }
    tbody tr:hover { background: rgba(255,255,255,0.02); }

    /* ── Pelamar info cell ── */
    .pelamar-info { display: flex; align-items: center; gap: 12px; }

    .avatar {
        width: 38px;
        height: 38px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 0.9rem;
        color: white;
        flex-shrink: 0;
    }

    .pelamar-name { font-weight: 600; color: var(--text-primary); }
    .pelamar-email { font-size: 0.8rem; color: var(--text-secondary); margin-top: 1px; }

    /* ── Badge posisi ── */
    .badge-posisi {
        display: inline-block;
        padding: 4px 10px;
        border-radius: 6px;
        font-size: 0.75rem;
        font-weight: 600;
        background: rgba(99, 102, 241, 0.15);
        color: #a5b4fc;
        border: 1px solid rgba(99, 102, 241, 0.25);
        white-space: nowrap;
    }

    /* ── AI Column Styles ── */
    .ai-status-wrap {
        display: flex;
        flex-direction: column;
        gap: 6px;
        align-items: flex-start;
    }

    .badge-ai-parsed {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 3px 8px;
        border-radius: 6px;
        font-size: 0.75rem;
        font-weight: 700;
        background: rgba(168, 85, 247, 0.18);
        color: #d8b4fe;
        border: 1px solid rgba(168, 85, 247, 0.35);
        cursor: pointer;
        transition: all 0.2s;
    }

    .badge-ai-parsed:hover {
        background: rgba(168, 85, 247, 0.3);
        transform: translateY(-1px);
    }

    .badge-ai-pending {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 3px 8px;
        border-radius: 6px;
        font-size: 0.72rem;
        font-weight: 600;
        background: rgba(245, 158, 11, 0.15);
        color: #fcd34d;
        border: 1px solid rgba(245, 158, 11, 0.25);
    }

    .skill-chips-row {
        display: flex;
        flex-wrap: wrap;
        gap: 4px;
        max-width: 220px;
    }

    .skill-chip {
        display: inline-block;
        padding: 2px 7px;
        border-radius: 4px;
        font-size: 0.68rem;
        font-weight: 600;
        background: rgba(59, 130, 246, 0.15);
        color: #93c5fd;
        border: 1px solid rgba(59, 130, 246, 0.25);
        white-space: nowrap;
        text-transform: capitalize;
    }

    .skill-chip-more {
        display: inline-block;
        padding: 2px 6px;
        border-radius: 4px;
        font-size: 0.68rem;
        font-weight: 700;
        background: rgba(255, 255, 255, 0.08);
        color: var(--text-secondary);
        border: 1px solid var(--border);
        cursor: pointer;
    }

    .skill-chip-more:hover {
        background: rgba(255, 255, 255, 0.15);
        color: var(--text-primary);
    }

    .ai-hint {
        font-size: 0.72rem;
        color: var(--text-muted);
        font-style: italic;
    }

    /* ── Action buttons ── */
    .actions { display: flex; align-items: center; gap: 6px; }

    .btn-action {
        padding: 6px 11px;
        border-radius: 7px;
        font-size: 0.78rem;
        font-weight: 600;
        cursor: pointer;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 4px;
        transition: all 0.2s;
        border: 1px solid;
        font-family: inherit;
        background: transparent;
    }

    .btn-download { color: var(--accent-3); border-color: rgba(6,182,212,0.3); }
    .btn-download:hover { background: rgba(6,182,212,0.1); }

    .btn-ai { color: #c084fc; border-color: rgba(192, 132, 252, 0.35); }
    .btn-ai:hover { background: rgba(192, 132, 252, 0.15); }

    .btn-reparse { color: #38bdf8; border-color: rgba(56, 189, 248, 0.35); }
    .btn-reparse:hover { background: rgba(56, 189, 248, 0.15); }

    .btn-parse-now {
        padding: 3px 8px;
        font-size: 0.72rem;
        color: #38bdf8;
        border: 1px solid rgba(56, 189, 248, 0.35);
        border-radius: 5px;
        background: rgba(56, 189, 248, 0.08);
        cursor: pointer;
    }
    .btn-parse-now:hover { background: rgba(56, 189, 248, 0.2); }

    .btn-edit { color: var(--warning); border-color: rgba(245,158,11,0.3); }
    .btn-edit:hover { background: rgba(245,158,11,0.1); }

    .btn-delete { color: var(--error); border-color: rgba(239,68,68,0.3); }
    .btn-delete:hover { background: rgba(239,68,68,0.1); }

    /* ── Empty state ── */
    .empty-state {
        padding: 4rem 2rem;
        text-align: center;
        color: var(--text-muted);
    }

    .empty-state-icon { font-size: 3.5rem; margin-bottom: 1rem; }
    .empty-state-title { font-size: 1.1rem; font-weight: 700; color: var(--text-secondary); margin-bottom: 6px; }
    .empty-state-desc { font-size: 0.875rem; }

    /* ── Pagination ── */
    .pagination-wrap {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1rem 1.5rem;
        border-top: 1px solid var(--border);
        background: var(--bg-card-2);
        flex-wrap: wrap;
        gap: 0.75rem;
    }

    .pagination-info { font-size: 0.8rem; color: var(--text-muted); }

    .pagination { display: flex; gap: 4px; list-style: none; }
    .pagination li span,
    .pagination li a {
        padding: 6px 12px;
        border-radius: 7px;
        font-size: 0.8rem;
        text-decoration: none;
        border: 1px solid var(--border);
        color: var(--text-secondary);
        transition: all 0.2s;
        display: block;
    }

    .pagination li.active span { background: var(--accent-1); border-color: var(--accent-1); color: white; }
    .pagination li a:hover { background: var(--bg-card-2); color: var(--text-primary); }

    /* ── Modal Umum ── */
    .modal-overlay {
        display: none;
        position: fixed; inset: 0;
        background: rgba(0,0,0,0.7);
        z-index: 999;
        backdrop-filter: blur(5px);
        align-items: center;
        justify-content: center;
        padding: 1rem;
    }

    .modal-overlay.open { display: flex; }

    .modal-box {
        background: var(--bg-card);
        border: 1px solid var(--border);
        border-radius: var(--radius-lg);
        padding: 2rem;
        max-width: 440px;
        width: 100%;
        box-shadow: 0 24px 60px rgba(0,0,0,0.6);
        animation: fadeInUp 0.3s ease;
    }

    .modal-icon { font-size: 3rem; text-align: center; margin-bottom: 1rem; }
    .modal-title { font-size: 1.15rem; font-weight: 700; text-align: center; margin-bottom: 8px; }
    .modal-desc { font-size: 0.875rem; color: var(--text-secondary); text-align: center; margin-bottom: 1.5rem; }

    .modal-actions { display: flex; gap: 10px; }
    .modal-actions .btn-cancel {
        flex: 1; padding: 11px;
        border-radius: 10px; border: 1px solid var(--border);
        background: transparent; color: var(--text-secondary);
        font-family: inherit; font-size: 0.9rem; font-weight: 600;
        cursor: pointer; transition: all 0.2s;
    }
    .modal-actions .btn-cancel:hover { background: var(--bg-card-2); color: var(--text-primary); }

    .modal-actions .btn-confirm-delete {
        flex: 1; padding: 11px;
        border-radius: 10px; border: none;
        background: var(--error); color: white;
        font-family: inherit; font-size: 0.9rem; font-weight: 600;
        cursor: pointer; transition: all 0.2s;
    }
    .modal-actions .btn-confirm-delete:hover { opacity: 0.88; }

    /* ── Modal Detail AI Khusus ── */
    .modal-ai-box {
        background: var(--bg-card);
        border: 1px solid rgba(168, 85, 247, 0.3);
        border-radius: var(--radius-lg);
        max-width: 680px;
        width: 100%;
        max-height: 90vh;
        overflow-y: auto;
        padding: 2rem;
        box-shadow: 0 24px 70px rgba(0,0,0,0.7);
        animation: fadeInUp 0.3s ease;
        position: relative;
    }

    .modal-ai-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 1.5rem;
        padding-bottom: 1rem;
        border-bottom: 1px solid var(--border);
    }

    .modal-ai-header h3 {
        font-size: 1.25rem;
        font-weight: 800;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .modal-close-btn {
        background: none;
        border: none;
        font-size: 1.5rem;
        color: var(--text-muted);
        cursor: pointer;
        padding: 0;
        line-height: 1;
    }
    .modal-close-btn:hover { color: var(--text-primary); }

    .ai-card-section {
        background: var(--bg-card-2);
        border: 1px solid var(--border);
        border-radius: 10px;
        padding: 1.1rem;
        margin-bottom: 1rem;
    }

    .ai-card-title {
        font-size: 0.8rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        color: var(--text-secondary);
        margin-bottom: 8px;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .ai-info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
        gap: 10px;
        font-size: 0.875rem;
    }

    .ai-info-item label {
        display: block;
        font-size: 0.72rem;
        color: var(--text-muted);
        margin-bottom: 2px;
    }

    .ai-info-item span {
        font-weight: 600;
        color: var(--text-primary);
        word-break: break-word;
    }

    .ai-skills-container {
        display: flex;
        flex-wrap: wrap;
        gap: 6px;
    }

    .ai-modal-skill {
        background: rgba(99, 102, 241, 0.2);
        color: #c7d2fe;
        border: 1px solid rgba(99, 102, 241, 0.35);
        padding: 4px 10px;
        border-radius: 6px;
        font-size: 0.8rem;
        font-weight: 600;
        text-transform: capitalize;
    }

    .ai-text-block {
        font-size: 0.85rem;
        line-height: 1.6;
        color: var(--text-secondary);
        white-space: pre-line;
        background: rgba(0,0,0,0.2);
        padding: 10px 12px;
        border-radius: 6px;
    }

    .raw-text-summary {
        cursor: pointer;
        font-size: 0.8rem;
        font-weight: 600;
        color: var(--accent-3);
        outline: none;
        margin-top: 4px;
    }

    .raw-text-box {
        margin-top: 8px;
        font-size: 0.75rem;
        max-height: 160px;
        overflow-y: auto;
        background: rgba(0,0,0,0.3);
        padding: 10px;
        border-radius: 6px;
        font-family: monospace;
        color: var(--text-muted);
        white-space: pre-wrap;
    }

    @media (max-width: 900px) {
        th:nth-child(3), td:nth-child(3),
        th:nth-child(6), td:nth-child(6) { display: none; }
    }
</style>
@endpush

@section('content')

{{-- ── Page Header ──────────────────────────────────────── --}}
<div class="page-header">
    <div class="page-header-left">
        <h1>📋 Manajemen CV Masuk</h1>
        <div class="page-header-sub">
            <p>Kelola semua lamaran yang masuk & pantau hasil parsing kecerdasan buatan</p>
            @if($aiOnline)
                <span class="badge-ai-server online">🟢 AI Engine Siap (Port 8001)</span>
            @else
                <span class="badge-ai-server offline" title="Jalankan: uvicorn main:app --reload --port 8001">🔴 AI Engine Offline</span>
            @endif
        </div>
    </div>
    <a href="{{ route('cv.create') }}" class="btn-primary">
        <span>+</span> Form Upload CV
    </a>
</div>

{{-- ── Stats ─────────────────────────────────────────────── --}}
<div class="stats-bar">
    <div class="stat-card">
        <div class="stat-value">{{ $pelamars->total() }}</div>
        <div class="stat-label">Total Pelamar</div>
    </div>
    <div class="stat-card">
        <div class="stat-value">
            {{ $pelamars->filter(fn($p) => $p->hasilParsing !== null)->count() }}
        </div>
        <div class="stat-label">Sudah Diparse AI</div>
    </div>
    <div class="stat-card">
        <div class="stat-value">{{ $pelamars->count() }}</div>
        <div class="stat-label">Ditampilkan di Halaman Ini</div>
    </div>
</div>

{{-- ── Search/Filter ─────────────────────────────────────── --}}
<div class="toolbar">
    <div class="search-wrap">
        <span class="search-icon">🔍</span>
        <input type="text"
               id="searchInput"
               class="search-input"
               placeholder="Cari nama, posisi, atau keahlian pelamar...">
    </div>
</div>

{{-- ── Table ──────────────────────────────────────────────── --}}
<div class="table-wrap">
    @if($pelamars->isEmpty())
        <div class="empty-state">
            <div class="empty-state-icon">📭</div>
            <div class="empty-state-title">Belum ada CV yang masuk</div>
            <div class="empty-state-desc">CV pelamar yang dikirim melalui halaman upload akan muncul di sini beserta hasil analisis AI.</div>
        </div>
    @else
        <table id="cvTable">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Pelamar</th>
                    <th>Nomor HP</th>
                    <th>Posisi Dilamar</th>
                    <th>🤖 Analisis AI Resume</th>
                    <th>Tanggal Masuk</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($pelamars as $i => $pelamar)
                <tr>
                    <td style="color:var(--text-muted);font-size:0.8rem">
                        {{ ($pelamars->currentPage() - 1) * $pelamars->perPage() + $i + 1 }}
                    </td>
                    <td>
                        <div class="pelamar-info">
                            <div class="avatar" style="background:hsl({{ (crc32($pelamar->nama) % 360 + 360) % 360 }},55%,45%)">
                                {{ strtoupper(substr($pelamar->nama, 0, 1)) }}
                            </div>
                            <div>
                                <div class="pelamar-name">{{ $pelamar->nama }}</div>
                                <div class="pelamar-email">{{ $pelamar->email }}</div>
                            </div>
                        </div>
                    </td>
                    <td style="color:var(--text-secondary)">{{ $pelamar->nomor_hp }}</td>
                    <td><span class="badge-posisi">{{ $pelamar->posisi_dilamar }}</span></td>
                    <td>
                        @if($pelamar->hasilParsing)
                            <div class="ai-status-wrap">
                                <span class="badge-ai-parsed" onclick="openAiModal({{ $pelamar->id }})" title="Klik untuk lihat detail ekstraksi AI">
                                    🤖 AI Parsed
                                </span>
                                @if(count($pelamar->hasilParsing->skill_list) > 0)
                                    <div class="skill-chips-row">
                                        @foreach(array_slice($pelamar->hasilParsing->skill_list, 0, 3) as $skill)
                                            <span class="skill-chip">{{ $skill }}</span>
                                        @endforeach
                                        @if(count($pelamar->hasilParsing->skill_list) > 3)
                                            <span class="skill-chip-more" onclick="openAiModal({{ $pelamar->id }})" title="Lihat semua keahlian">
                                                +{{ count($pelamar->hasilParsing->skill_list) - 3 }}
                                            </span>
                                        @endif
                                    </div>
                                @else
                                    <div class="ai-hint">Tidak ada skill khusus terdeteksi</div>
                                @endif
                            </div>
                        @else
                            <div class="ai-status-wrap">
                                <span class="badge-ai-pending">⏳ Belum diparse</span>
                                <form action="{{ route('cv.reparse', $pelamar) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn-parse-now" title="Kirim ke AI Engine">
                                        ⚡ Parse AI
                                    </button>
                                </form>
                            </div>
                        @endif
                    </td>
                    <td style="color:var(--text-muted);font-size:0.8rem;white-space:nowrap">
                        {{ $pelamar->created_at->format('d M Y') }}<br>
                        <span style="font-size:0.72rem">{{ $pelamar->created_at->format('H:i') }} WIB</span>
                    </td>
                    <td>
                        <div class="actions">
                            {{-- Download --}}
                            <a href="{{ route('cv.download', $pelamar) }}"
                               class="btn-action btn-download"
                               title="Download File CV">
                                ⬇️
                            </a>

                            {{-- Detail AI --}}
                            @if($pelamar->hasilParsing)
                                <button type="button"
                                        class="btn-action btn-ai"
                                        title="Buka Hasil AI Lengkap"
                                        onclick="openAiModal({{ $pelamar->id }})">
                                    🤖
                                </button>
                            @endif

                            {{-- Re-parse AI --}}
                            <form action="{{ route('cv.reparse', $pelamar) }}" method="POST" style="display:inline">
                                @csrf
                                <button type="submit"
                                        class="btn-action btn-reparse"
                                        title="Proses ulang dengan AI Engine">
                                    🔄
                                </button>
                            </form>

                            {{-- Edit --}}
                            <a href="{{ route('cv.edit', $pelamar) }}"
                               class="btn-action btn-edit"
                               title="Edit data">
                                ✏️
                            </a>

                            {{-- Hapus --}}
                            <button type="button"
                                    class="btn-action btn-delete"
                                    title="Hapus"
                                    onclick="confirmDelete({{ $pelamar->id }}, '{{ addslashes($pelamar->nama) }}')">
                                🗑️
                            </button>

                            {{-- Hidden delete form --}}
                            <form id="deleteForm-{{ $pelamar->id }}"
                                  action="{{ route('cv.destroy', $pelamar) }}"
                                  method="POST"
                                  style="display:none">
                                @csrf
                                @method('DELETE')
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        {{-- Pagination --}}
        @if($pelamars->hasPages())
        <div class="pagination-wrap">
            <div class="pagination-info">
                Menampilkan {{ $pelamars->firstItem() }}–{{ $pelamars->lastItem() }}
                dari {{ $pelamars->total() }} pelamar
            </div>
            {{ $pelamars->links() }}
        </div>
        @endif
    @endif
</div>

{{-- ── Modal Detail AI ───────────────────────────────────── --}}
<div class="modal-overlay" id="aiModal">
    <div class="modal-ai-box">
        <div class="modal-ai-header">
            <div>
                <h3 id="aiModalPelamarName">🤖 Hasil Ekstraksi AI</h3>
                <span id="aiModalParsedAt" style="font-size:0.75rem; color:var(--text-muted)"></span>
            </div>
            <button class="modal-close-btn" onclick="closeAiModal()">&times;</button>
        </div>

        <div id="aiModalLoading" style="text-align:center; padding: 2rem; color:var(--text-secondary)">
            ⏳ Mengambil data hasil AI...
        </div>

        <div id="aiModalContent" style="display:none">
            {{-- Kontak Terdeteksi --}}
            <div class="ai-card-section">
                <div class="ai-card-title">👤 Informasi Kontak Terdeteksi (spaCy NER & Regex)</div>
                <div class="ai-info-grid">
                    <div class="ai-info-item">
                        <label>Nama Hasil Ekstraksi</label>
                        <span id="aiExtractedName">-</span>
                    </div>
                    <div class="ai-info-item">
                        <label>Email Terdeteksi</label>
                        <span id="aiExtractedEmail">-</span>
                    </div>
                    <div class="ai-info-item">
                        <label>Nomor Telepon</label>
                        <span id="aiExtractedPhone">-</span>
                    </div>
                    <div class="ai-info-item">
                        <label>Nama File CV</label>
                        <span id="aiExtractedFilename">-</span>
                    </div>
                </div>
            </div>

            {{-- Keahlian / Skills --}}
            <div class="ai-card-section">
                <div class="ai-card-title">💡 Keahlian & Teknologi Terdeteksi</div>
                <div class="ai-skills-container" id="aiSkillsContainer">
                    <!-- Skills pills injected here -->
                </div>
            </div>

            {{-- Riwayat Kerja --}}
            <div class="ai-card-section">
                <div class="ai-card-title">💼 Riwayat Kerja / Pengalaman</div>
                <div class="ai-text-block" id="aiWorkExp">-</div>
            </div>

            {{-- Pendidikan --}}
            <div class="ai-card-section">
                <div class="ai-card-title">🎓 Riwayat Pendidikan</div>
                <div class="ai-text-block" id="aiEducation">-</div>
            </div>

            {{-- Teks Mentah --}}
            <details style="margin-top: 1rem">
                <summary class="raw-text-summary">📄 Tampilkan Teks Mentah Hasil Ekstraksi Dokumen</summary>
                <div class="raw-text-box" id="aiRawText"></div>
            </details>
        </div>

        <div class="modal-actions" style="margin-top: 1.5rem">
            <button class="btn-cancel" onclick="closeAiModal()">Tutup</button>
            <form id="aiReparseForm" method="POST" style="flex:1">
                @csrf
                <button type="submit" class="btn-primary" style="width:100%; justify-content:center">
                    🔄 Parse Ulang dengan AI
                </button>
            </form>
        </div>
    </div>
</div>

{{-- ── Modal Konfirmasi Hapus ───────────────────────────── --}}
<div class="modal-overlay" id="deleteModal">
    <div class="modal-box">
        <div class="modal-icon">⚠️</div>
        <div class="modal-title">Hapus Data Pelamar?</div>
        <div class="modal-desc" id="modalDesc">
            Anda akan menghapus data pelamar ini beserta file CV dan riwayat hasil analisis AI.<br>
            <strong>Tindakan ini tidak dapat dibatalkan.</strong>
        </div>
        <div class="modal-actions">
            <button class="btn-cancel" onclick="closeDeleteModal()">Batal</button>
            <button class="btn-confirm-delete" id="btnConfirmDelete">Ya, Hapus</button>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    // ── Search/filter tabel ──────────────────────────────
    const searchInput = document.getElementById('searchInput');
    const tableRows   = document.querySelectorAll('#cvTable tbody tr');

    searchInput.addEventListener('input', function () {
        const query = this.value.toLowerCase().trim();
        tableRows.forEach(row => {
            const text = row.textContent.toLowerCase();
            row.style.display = text.includes(query) ? '' : 'none';
        });
    });

    // ── Modal Hapus ──────────────────────────────────────
    let targetFormId = null;

    function confirmDelete(id, nama) {
        targetFormId = id;
        document.getElementById('modalDesc').innerHTML =
            `Anda akan menghapus data <strong>${nama}</strong> beserta file CV dan hasil AI-nya.<br>
             <strong>Tindakan ini tidak dapat dibatalkan.</strong>`;
        document.getElementById('deleteModal').classList.add('open');
    }

    function closeDeleteModal() {
        document.getElementById('deleteModal').classList.remove('open');
        targetFormId = null;
    }

    document.getElementById('btnConfirmDelete').addEventListener('click', function () {
        if (targetFormId !== null) {
            document.getElementById('deleteForm-' + targetFormId).submit();
        }
    });

    document.getElementById('deleteModal').addEventListener('click', function (e) {
        if (e.target === this) closeDeleteModal();
    });

    // ── Modal AI Detail ──────────────────────────────────
    const aiModal = document.getElementById('aiModal');

    function openAiModal(pelamarId) {
        aiModal.classList.add('open');
        document.getElementById('aiModalLoading').style.display = 'block';
        document.getElementById('aiModalContent').style.display = 'none';

        // Set action form reparse
        document.getElementById('aiReparseForm').action = `{{ url('hrd/cv') }}/${pelamarId}/reparse`;

        fetch(`{{ url('hrd/cv') }}/${pelamarId}/ai-detail`)
            .then(res => res.json())
            .then(data => {
                document.getElementById('aiModalLoading').style.display = 'none';
                document.getElementById('aiModalContent').style.display = 'block';

                const p = data.pelamar;
                const ai = data.hasil_parsing;
                const skills = data.skills || [];

                document.getElementById('aiModalPelamarName').innerHTML = `🤖 Hasil AI — ${p.nama}`;
                document.getElementById('aiModalParsedAt').textContent = ai && ai.diparse_pada ? `Diparse pada: ${ai.diparse_pada}` : 'Belum diparse';

                if (ai) {
                    document.getElementById('aiExtractedName').textContent = ai.nama_lengkap || p.nama || '-';
                    document.getElementById('aiExtractedEmail').textContent = ai.email || p.email || '-';
                    document.getElementById('aiExtractedPhone').textContent = ai.nomor_telepon || p.nomor_hp || '-';
                    document.getElementById('aiExtractedFilename').textContent = ai.nama_file || '-';

                    // Skills
                    const skillsContainer = document.getElementById('aiSkillsContainer');
                    skillsContainer.innerHTML = '';
                    if (skills.length > 0) {
                        skills.forEach(skill => {
                            const span = document.createElement('span');
                            span.className = 'ai-modal-skill';
                            span.textContent = skill;
                            skillsContainer.appendChild(span);
                        });
                    } else {
                        skillsContainer.innerHTML = '<span class="ai-hint">Tidak ada skill yang terdeteksi dari kamus AI.</span>';
                    }

                    // Riwayat kerja & pendidikan
                    document.getElementById('aiWorkExp').textContent = ai.riwayat_kerja || 'Tidak ada riwayat kerja spesifik terdeteksi.';
                    document.getElementById('aiEducation').textContent = ai.pendidikan || 'Tidak ada riwayat pendidikan spesifik terdeteksi.';
                    document.getElementById('aiRawText').textContent = ai.teks_mentah || 'Teks mentah kosong.';
                } else {
                    document.getElementById('aiExtractedName').textContent = p.nama;
                    document.getElementById('aiExtractedEmail').textContent = p.email;
                    document.getElementById('aiExtractedPhone').textContent = p.nomor_hp;
                    document.getElementById('aiExtractedFilename').textContent = p.file_cv;
                    document.getElementById('aiSkillsContainer').innerHTML = '<span class="ai-hint">Data belum di-parse oleh AI Engine.</span>';
                    document.getElementById('aiWorkExp').textContent = 'Belum di-parse.';
                    document.getElementById('aiEducation').textContent = 'Belum di-parse.';
                    document.getElementById('aiRawText').textContent = 'Teks belum diekstrak.';
                }
            })
            .catch(err => {
                document.getElementById('aiModalLoading').innerHTML = '<div style="color:var(--error)">⚠️ Gagal memuat data dari server.</div>';
            });
    }

    function closeAiModal() {
        aiModal.classList.remove('open');
    }

    aiModal.addEventListener('click', function (e) {
        if (e.target === this) closeAiModal();
    });
</script>
@endpush
