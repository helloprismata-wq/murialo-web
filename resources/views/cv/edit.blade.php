@extends('layouts.app')

@section('title', 'Edit Data Pelamar')
@section('meta_description', 'Edit data pelamar ' . $pelamar->nama . ' di sistem Murialo.')

@push('styles')
<style>
    .page-header {
        display: flex;
        align-items: center;
        gap: 16px;
        margin-bottom: 2rem;
        animation: fadeInUp 0.5s ease;
    }

    .btn-back {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 8px 16px;
        border-radius: 9px;
        border: 1px solid var(--border);
        color: var(--text-secondary);
        text-decoration: none;
        font-size: 0.85rem;
        font-weight: 500;
        transition: all 0.2s;
        background: transparent;
        flex-shrink: 0;
    }

    .btn-back:hover { background: var(--bg-card); color: var(--text-primary); }

    .page-header-info h1 { font-size: 1.5rem; font-weight: 800; }
    .page-header-info p { font-size: 0.875rem; color: var(--text-secondary); margin-top: 2px; }

    /* ── Form ── */
    .form-wrapper {
        max-width: 680px;
        animation: fadeInUp 0.6s ease;
    }

    .form-card {
        background: var(--bg-card);
        border: 1px solid var(--border);
        border-radius: var(--radius-lg);
        padding: 2.5rem;
        box-shadow: var(--shadow);
        position: relative;
        overflow: hidden;
    }

    .form-card::before {
        content: '';
        position: absolute;
        top: 0; left: 0; right: 0;
        height: 3px;
        background: linear-gradient(90deg, var(--warning), #f97316, var(--accent-1));
    }

    .form-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1.25rem;
    }

    .form-group { display: flex; flex-direction: column; gap: 6px; }
    .form-group.full { grid-column: 1 / -1; }

    label {
        font-size: 0.82rem;
        font-weight: 600;
        color: var(--text-secondary);
        text-transform: uppercase;
        letter-spacing: 0.04em;
    }

    label .required { color: #f87171; margin-left: 2px; }

    .form-control {
        background: var(--bg-card-2);
        border: 1.5px solid var(--border);
        border-radius: 10px;
        padding: 12px 16px;
        color: var(--text-primary);
        font-size: 0.95rem;
        font-family: inherit;
        transition: all 0.25s;
        outline: none;
        width: 100%;
    }

    .form-control::placeholder { color: var(--text-muted); }
    .form-control:focus {
        border-color: var(--warning);
        box-shadow: 0 0 0 3px rgba(245, 158, 11, 0.15);
        background: rgba(26, 34, 53, 0.8);
    }

    .form-control.is-invalid {
        border-color: var(--error);
        box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.12);
    }

    .error-msg {
        font-size: 0.78rem;
        color: #fca5a5;
        display: flex;
        align-items: center;
        gap: 4px;
    }

    /* ── File CV info ── */
    .current-cv-box {
        background: var(--bg-card-2);
        border: 1.5px solid var(--border);
        border-radius: 10px;
        padding: 14px 16px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 10px;
        margin-bottom: 12px;
    }

    .current-cv-info { display: flex; align-items: center; gap: 10px; }
    .current-cv-icon { font-size: 1.5rem; }
    .current-cv-label { font-size: 0.85rem; font-weight: 600; color: var(--text-primary); }
    .current-cv-sub { font-size: 0.75rem; color: var(--text-muted); margin-top: 2px; }

    .btn-view-cv {
        padding: 6px 14px;
        border-radius: 7px;
        text-decoration: none;
        font-size: 0.78rem;
        font-weight: 600;
        color: var(--accent-3);
        border: 1px solid rgba(6,182,212,0.3);
        transition: all 0.2s;
    }

    .btn-view-cv:hover { background: rgba(6,182,212,0.1); }

    .hint-text { font-size: 0.78rem; color: var(--text-muted); }

    /* ── File upload ── */
    .file-upload-area {
        border: 2px dashed var(--border);
        border-radius: 10px;
        padding: 1.75rem 1.5rem;
        text-align: center;
        cursor: pointer;
        transition: all 0.3s;
        position: relative;
        background: var(--bg-card-2);
    }

    .file-upload-area:hover { border-color: var(--warning); background: rgba(245,158,11,0.04); }
    .file-upload-area.has-file { border-color: var(--success); background: rgba(16,185,129,0.05); }

    #file_cv {
        position: absolute; inset: 0;
        width: 100%; height: 100%;
        opacity: 0; cursor: pointer;
    }

    .file-icon { font-size: 2rem; margin-bottom: 0.5rem; display: block; }
    .file-title { font-size: 0.9rem; font-weight: 600; color: var(--text-primary); margin-bottom: 4px; }
    .file-subtitle { font-size: 0.78rem; color: var(--text-muted); }

    /* ── Buttons ── */
    .btn-actions {
        display: flex;
        gap: 12px;
        margin-top: 0.5rem;
    }

    .btn-save {
        flex: 1;
        padding: 14px;
        border: none;
        border-radius: 12px;
        font-size: 0.95rem;
        font-weight: 700;
        font-family: inherit;
        color: white;
        background: linear-gradient(135deg, var(--warning), #f97316);
        cursor: pointer;
        transition: all 0.3s;
    }

    .btn-save:hover { transform: translateY(-2px); box-shadow: 0 8px 24px rgba(245,158,11,0.35); }

    .btn-cancel-form {
        padding: 14px 24px;
        border: 1.5px solid var(--border);
        border-radius: 12px;
        font-size: 0.95rem;
        font-weight: 600;
        font-family: inherit;
        color: var(--text-secondary);
        background: transparent;
        cursor: pointer;
        text-decoration: none;
        transition: all 0.2s;
        display: inline-flex;
        align-items: center;
    }

    .btn-cancel-form:hover { background: var(--bg-card-2); color: var(--text-primary); }

    @media (max-width: 640px) {
        .form-grid { grid-template-columns: 1fr; }
        .form-card { padding: 1.5rem; }
        .btn-actions { flex-direction: column-reverse; }
    }
</style>
@endpush

@section('content')

{{-- ── Header ─────────────────────────────────────────────── --}}
<div class="page-header">
    <a href="{{ route('cv.index') }}" class="btn-back">← Kembali</a>
    <div class="page-header-info">
        <h1>✏️ Edit Data Pelamar</h1>
        <p>Perbarui informasi <strong>{{ $pelamar->nama }}</strong></p>
    </div>
</div>

{{-- ── Form Edit ─────────────────────────────────────────── --}}
<div class="form-wrapper">
    <div class="form-card">
        <form id="editForm"
              action="{{ route('cv.update', $pelamar) }}"
              method="POST"
              enctype="multipart/form-data"
              novalidate>
            @csrf
            @method('PUT')

            <div class="form-grid">

                {{-- Nama --}}
                <div class="form-group">
                    <label for="nama">Nama Lengkap <span class="required">*</span></label>
                    <input id="nama"
                           type="text"
                           name="nama"
                           class="form-control {{ $errors->has('nama') ? 'is-invalid' : '' }}"
                           value="{{ old('nama', $pelamar->nama) }}"
                           required>
                    @error('nama')
                        <span class="error-msg">⚠️ {{ $message }}</span>
                    @enderror
                </div>

                {{-- Email --}}
                <div class="form-group">
                    <label for="email">Alamat Email <span class="required">*</span></label>
                    <input id="email"
                           type="email"
                           name="email"
                           class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}"
                           value="{{ old('email', $pelamar->email) }}"
                           required>
                    @error('email')
                        <span class="error-msg">⚠️ {{ $message }}</span>
                    @enderror
                </div>

                {{-- Nomor HP --}}
                <div class="form-group">
                    <label for="nomor_hp">Nomor HP / WhatsApp <span class="required">*</span></label>
                    <input id="nomor_hp"
                           type="tel"
                           name="nomor_hp"
                           class="form-control {{ $errors->has('nomor_hp') ? 'is-invalid' : '' }}"
                           value="{{ old('nomor_hp', $pelamar->nomor_hp) }}"
                           required>
                    @error('nomor_hp')
                        <span class="error-msg">⚠️ {{ $message }}</span>
                    @enderror
                </div>

                {{-- Posisi --}}
                <div class="form-group">
                    <label for="posisi_dilamar">Posisi yang Dilamar <span class="required">*</span></label>
                    <input id="posisi_dilamar"
                           type="text"
                           name="posisi_dilamar"
                           class="form-control {{ $errors->has('posisi_dilamar') ? 'is-invalid' : '' }}"
                           value="{{ old('posisi_dilamar', $pelamar->posisi_dilamar) }}"
                           list="posisi-list"
                           required>
                    <datalist id="posisi-list">
                        <option value="Frontend Developer">
                        <option value="Backend Developer">
                        <option value="Fullstack Developer">
                        <option value="Mobile Developer">
                        <option value="UI/UX Designer">
                        <option value="Data Scientist">
                        <option value="Machine Learning Engineer">
                        <option value="DevOps Engineer">
                        <option value="QA Engineer">
                        <option value="Product Manager">
                    </datalist>
                    @error('posisi_dilamar')
                        <span class="error-msg">⚠️ {{ $message }}</span>
                    @enderror
                </div>

                {{-- CV saat ini --}}
                <div class="form-group full">
                    <label>File CV</label>

                    {{-- CV sekarang --}}
                    <div class="current-cv-box">
                        <div class="current-cv-info">
                            <span class="current-cv-icon">📄</span>
                            <div>
                                <div class="current-cv-label">CV tersimpan saat ini</div>
                                <div class="current-cv-sub">
                                    {{ basename($pelamar->file_cv) }}
                                </div>
                            </div>
                        </div>
                        <a href="{{ route('cv.download', $pelamar) }}"
                           class="btn-view-cv"
                           target="_blank">
                            ⬇️ Download
                        </a>
                    </div>

                    {{-- Upload CV baru (opsional) --}}
                    <div class="file-upload-area" id="dropZone">
                        <input type="file"
                               id="file_cv"
                               name="file_cv"
                               accept=".pdf">

                        <div id="fileDefault">
                            <span class="file-icon">🔄</span>
                            <p class="file-title">Ganti dengan CV baru (opsional)</p>
                            <p class="file-subtitle">Biarkan kosong jika tidak ingin mengganti CV · PDF · Maks. 5MB</p>
                        </div>

                        <div id="fileSelected" style="display:none">
                            <span class="file-icon">✅</span>
                            <p class="file-title" id="fileNameDisplay"></p>
                            <p class="file-subtitle" id="fileSizeDisplay"></p>
                        </div>
                    </div>

                    @error('file_cv')
                        <span class="error-msg">⚠️ {{ $message }}</span>
                    @enderror
                </div>

            </div>{{-- /form-grid --}}

            <div class="btn-actions">
                <a href="{{ route('cv.index') }}" class="btn-cancel-form">Batal</a>
                <button type="submit" class="btn-save">💾 Simpan Perubahan</button>
            </div>

        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
    const fileInput    = document.getElementById('file_cv');
    const dropZone     = document.getElementById('dropZone');
    const fileDefault  = document.getElementById('fileDefault');
    const fileSelected = document.getElementById('fileSelected');
    const fileNameEl   = document.getElementById('fileNameDisplay');
    const fileSizeEl   = document.getElementById('fileSizeDisplay');

    function formatBytes(bytes) {
        return bytes < 1024 * 1024
            ? (bytes / 1024).toFixed(1) + ' KB'
            : (bytes / (1024 * 1024)).toFixed(2) + ' MB';
    }

    fileInput.addEventListener('change', function () {
        if (this.files.length > 0) {
            const file = this.files[0];
            fileDefault.style.display  = 'none';
            fileSelected.style.display = 'block';
            fileNameEl.textContent     = file.name;
            fileSizeEl.textContent     = formatBytes(file.size);
            dropZone.classList.add('has-file');
        }
    });
</script>
@endpush
