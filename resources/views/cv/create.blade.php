@extends('layouts.app')

@section('title', 'Upload CV')
@section('meta_description', 'Kirimkan CV Anda ke Murialo dan bergabunglah dengan tim kami. Proses rekrutmen cepat dan transparan berbasis AI.')

@push('styles')
<style>
    /* ── Hero section ── */
    .hero {
        text-align: center;
        padding: 3rem 1rem 2rem;
        animation: fadeInUp 0.6s ease;
    }

    .hero-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: rgba(99, 102, 241, 0.15);
        border: 1px solid rgba(99, 102, 241, 0.35);
        padding: 6px 16px;
        border-radius: 999px;
        font-size: 0.78rem;
        font-weight: 600;
        color: #a5b4fc;
        margin-bottom: 1.25rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    .hero h1 {
        font-size: clamp(2rem, 5vw, 3rem);
        font-weight: 800;
        line-height: 1.15;
        margin-bottom: 1rem;
    }

    .hero p {
        color: var(--text-secondary);
        font-size: 1rem;
        max-width: 520px;
        margin: 0 auto 2rem;
    }

    /* ── Steps indicator ── */
    .steps {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0;
        margin-bottom: 2.5rem;
        flex-wrap: wrap;
    }

    .step {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 0.8rem;
        font-weight: 500;
        color: var(--text-muted);
    }

    .step.active { color: var(--accent-1); }
    .step.active .step-num { background: var(--accent-1); color: white; }

    .step-num {
        width: 26px; height: 26px;
        border-radius: 50%;
        background: var(--bg-card-2);
        border: 1px solid var(--border);
        display: grid; place-items: center;
        font-size: 0.75rem; font-weight: 700;
        transition: all 0.3s;
    }

    .step-divider {
        width: 40px; height: 1px;
        background: var(--border);
        margin: 0 8px;
    }

    /* ── Form card ── */
    .form-wrapper {
        max-width: 680px;
        margin: 0 auto;
        animation: fadeInUp 0.7s ease;
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
        background: linear-gradient(90deg, var(--accent-1), var(--accent-2), var(--accent-3));
    }

    /* ── Form elements ── */
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
        border-color: var(--accent-1);
        box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.15);
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

    /* ── File upload ── */
    .file-upload-area {
        border: 2px dashed var(--border);
        border-radius: 12px;
        padding: 2.5rem 1.5rem;
        text-align: center;
        cursor: pointer;
        transition: all 0.3s;
        position: relative;
        background: var(--bg-card-2);
    }

    .file-upload-area:hover, .file-upload-area.drag-over {
        border-color: var(--accent-1);
        background: rgba(99, 102, 241, 0.06);
    }

    .file-upload-area.has-file {
        border-color: var(--success);
        background: rgba(16, 185, 129, 0.06);
    }

    .file-upload-area.is-invalid { border-color: var(--error); }

    .file-icon { font-size: 2.5rem; margin-bottom: 0.75rem; display: block; }

    .file-title {
        font-size: 0.95rem;
        font-weight: 600;
        color: var(--text-primary);
        margin-bottom: 4px;
    }

    .file-subtitle {
        font-size: 0.8rem;
        color: var(--text-muted);
    }

    .file-name-display {
        margin-top: 10px;
        font-size: 0.85rem;
        font-weight: 600;
        color: var(--success);
        display: none;
    }

    .file-upload-area.has-file .file-name-display { display: block; }
    .file-upload-area.has-file .file-default { display: none; }

    #file_cv {
        position: absolute; inset: 0;
        width: 100%; height: 100%;
        opacity: 0; cursor: pointer;
    }

    /* ── Submit button ── */
    .btn-submit {
        width: 100%;
        padding: 15px;
        border: none;
        border-radius: 12px;
        font-size: 1rem;
        font-weight: 700;
        font-family: inherit;
        color: white;
        background: linear-gradient(135deg, var(--accent-1), var(--accent-2));
        cursor: pointer;
        transition: all 0.3s;
        margin-top: 0.5rem;
        letter-spacing: 0.02em;
        position: relative;
        overflow: hidden;
    }

    .btn-submit::after {
        content: '';
        position: absolute;
        inset: 0;
        background: linear-gradient(135deg, var(--accent-2), var(--accent-3));
        opacity: 0;
        transition: opacity 0.3s;
    }

    .btn-submit:hover { transform: translateY(-2px); box-shadow: 0 8px 24px rgba(99,102,241,0.4); }
    .btn-submit:hover::after { opacity: 1; }
    .btn-submit:active { transform: translateY(0); }

    .btn-submit span { position: relative; z-index: 1; }

    /* ── Info cards ── */
    .info-cards {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 1rem;
        margin-top: 2rem;
        max-width: 680px;
        margin-left: auto;
        margin-right: auto;
    }

    .info-card {
        background: var(--bg-card);
        border: 1px solid var(--border);
        border-radius: var(--radius);
        padding: 1.25rem;
        text-align: center;
    }

    .info-card-icon { font-size: 1.75rem; margin-bottom: 0.5rem; }
    .info-card-title { font-size: 0.85rem; font-weight: 700; color: var(--text-primary); margin-bottom: 4px; }
    .info-card-desc { font-size: 0.75rem; color: var(--text-muted); }

    @media (max-width: 640px) {
        .form-grid { grid-template-columns: 1fr; }
        .info-cards { grid-template-columns: 1fr; }
        .form-card { padding: 1.5rem; }
    }
</style>
@endpush

@section('content')

{{-- ── Hero ─────────────────────────────────────── --}}
<div class="hero">
    <span class="hero-badge">🤖 AI-Powered Recruitment</span>
    <h1>Kirim CV Anda,<br><span class="text-gradient">Kami Temukan Peluang Terbaik</span></h1>
    <p>Unggah CV Anda dan biarkan AI kami mencocokkan Anda dengan posisi yang paling sesuai dengan keahlian Anda.</p>

    <div class="steps">
        <div class="step active">
            <div class="step-num">1</div>
            <span>Isi Data</span>
        </div>
        <div class="step-divider"></div>
        <div class="step">
            <div class="step-num">2</div>
            <span>Analisis AI</span>
        </div>
        <div class="step-divider"></div>
        <div class="step">
            <div class="step-num">3</div>
            <span>Dihubungi</span>
        </div>
    </div>
</div>

{{-- ── Form Card ────────────────────────────────── --}}
<div class="form-wrapper">
    <div class="form-card">
        <form id="uploadForm"
              action="{{ route('cv.store') }}"
              method="POST"
              enctype="multipart/form-data"
              novalidate>
            @csrf

            <div class="form-grid">

                {{-- Nama --}}
                <div class="form-group">
                    <label for="nama">Nama Lengkap <span class="required">*</span></label>
                    <input id="nama"
                           type="text"
                           name="nama"
                           class="form-control {{ $errors->has('nama') ? 'is-invalid' : '' }}"
                           placeholder="Contoh: Ahmad Khamdanul Jiyad"
                           value="{{ old('nama') }}"
                           autocomplete="name">
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
                           placeholder="nama@email.com"
                           value="{{ old('email') }}"
                           autocomplete="email">
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
                           placeholder="08xx-xxxx-xxxx"
                           value="{{ old('nomor_hp') }}"
                           autocomplete="tel">
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
                           placeholder="Contoh: Backend Developer"
                           value="{{ old('posisi_dilamar') }}"
                           list="posisi-list">
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

                {{-- Upload CV --}}
                <div class="form-group full">
                    <label>File CV (PDF, maks. 5MB) <span class="required">*</span></label>
                    <div class="file-upload-area {{ $errors->has('file_cv') ? 'is-invalid' : '' }}"
                         id="dropZone">
                        <input type="file"
                               id="file_cv"
                               name="file_cv"
                               accept=".pdf">

                        <div class="file-default">
                            <span class="file-icon">📄</span>
                            <p class="file-title">Klik atau seret file PDF ke sini</p>
                            <p class="file-subtitle">Format: PDF &nbsp;·&nbsp; Maks. 5MB</p>
                        </div>

                        <div style="display:none" class="file-selected">
                            <span class="file-icon">✅</span>
                            <p class="file-title" id="fileNameDisplay">File terpilih</p>
                            <p class="file-subtitle" id="fileSizeDisplay"></p>
                        </div>
                    </div>
                    @error('file_cv')
                        <span class="error-msg">⚠️ {{ $message }}</span>
                    @enderror
                </div>

            </div>{{-- /form-grid --}}

            <button type="submit" class="btn-submit" id="submitBtn">
                <span>🚀 Kirim Lamaran Sekarang</span>
            </button>

        </form>
    </div>

    {{-- Info cards --}}
    <div class="info-cards">
        <div class="info-card">
            <div class="info-card-icon">🔒</div>
            <div class="info-card-title">Data Aman</div>
            <div class="info-card-desc">CV Anda hanya diakses oleh tim rekrutmen kami</div>
        </div>
        <div class="info-card">
            <div class="info-card-icon">⚡</div>
            <div class="info-card-title">Proses Cepat</div>
            <div class="info-card-desc">AI kami menganalisis CV dalam hitungan detik</div>
        </div>
        <div class="info-card">
            <div class="info-card-icon">📞</div>
            <div class="info-card-title">Respon 3 Hari</div>
            <div class="info-card-desc">Tim HRD akan menghubungi Anda dalam 3 hari kerja</div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    const fileInput   = document.getElementById('file_cv');
    const dropZone    = document.getElementById('dropZone');
    const fileDefault = dropZone.querySelector('.file-default');
    const fileSelected = dropZone.querySelector('.file-selected');
    const fileNameEl  = document.getElementById('fileNameDisplay');
    const fileSizeEl  = document.getElementById('fileSizeDisplay');

    function formatBytes(bytes) {
        return bytes < 1024 * 1024
            ? (bytes / 1024).toFixed(1) + ' KB'
            : (bytes / (1024 * 1024)).toFixed(2) + ' MB';
    }

    function showFile(file) {
        fileDefault.style.display  = 'none';
        fileSelected.style.display = 'block';
        fileNameEl.textContent     = file.name;
        fileSizeEl.textContent     = formatBytes(file.size);
        dropZone.classList.add('has-file');
        dropZone.classList.remove('is-invalid');
    }

    fileInput.addEventListener('change', function () {
        if (this.files.length > 0) showFile(this.files[0]);
    });

    // Drag & drop
    dropZone.addEventListener('dragover', e => { e.preventDefault(); dropZone.classList.add('drag-over'); });
    dropZone.addEventListener('dragleave', () => dropZone.classList.remove('drag-over'));
    dropZone.addEventListener('drop', e => {
        e.preventDefault();
        dropZone.classList.remove('drag-over');
        const file = e.dataTransfer.files[0];
        if (file && file.type === 'application/pdf') {
            const dt = new DataTransfer();
            dt.items.add(file);
            fileInput.files = dt.files;
            showFile(file);
        } else {
            alert('Hanya file PDF yang diperbolehkan.');
        }
    });

    // Loading state saat submit
    document.getElementById('uploadForm').addEventListener('submit', function () {
        const btn = document.getElementById('submitBtn');
        btn.disabled = true;
        btn.querySelector('span').textContent = '⏳ Mengirim lamaran...';
    });
</script>
@endpush
