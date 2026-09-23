@extends('lowongan.layout')

@section('title', 'Skill Matching AI · ' . $lowongan->judul)

@section('content')
<a class="back-link" href="{{ route('lowongan.show', $lowongan) }}">← Kembali ke Detail Lowongan</a>

<div class="heading" style="margin-bottom: 20px;">
    <div>
        <div style="display: flex; gap: 8px; align-items: center; margin-bottom: 6px;">
            <span class="badge badge-aktif">AI Skill Matching</span>
            @if ($isAiOnline)
                <span style="font-size: 11px; font-weight: 600; color: #34d399; background: rgba(52, 211, 153, 0.1); padding: 2px 8px; border-radius: var(--radius-pill);">
                    ● FastAPI AI Engine Online (S-BERT)
                </span>
            @else
                <span style="font-size: 11px; font-weight: 600; color: #f87171; background: rgba(248, 113, 113, 0.1); padding: 2px 8px; border-radius: var(--radius-pill);">
                    ○ AI Engine Offline (Port 8001)
                </span>
            @endif
        </div>
        <h1>Automated Skill Matching: {{ $lowongan->judul }}</h1>
        <p class="muted">Perusahaan: {{ $lowongan->perusahaan }} · Lokasi: {{ $lowongan->lokasi }}</p>
    </div>
</div>

<!-- Informasi Kualifikasi Lowongan -->
<div class="panel" style="margin-bottom: 24px; background: rgba(99, 102, 241, 0.04); border-color: rgba(99, 102, 241, 0.2);">
    <h3 style="font-size: 15px; margin-bottom: 10px; color: #e0e7ff;">Target Kompetensi & Keahlian yang Dibutuhkan</h3>
    @if ($lowongan->skills)
        <div class="skill-tags" style="margin-bottom: 10px;">
            @foreach (explode(',', $lowongan->skills) as $skill)
                <span class="skill-tag" style="background: rgba(99, 102, 241, 0.15); border-color: rgba(99, 102, 241, 0.3); color: #c7d2fe;">
                    {{ trim($skill) }}
                </span>
            @endforeach
        </div>
    @else
        <p style="color: var(--text-muted); font-size: 13px;">Belum ada kriteria skill spesifik yang dimasukkan pada lowongan ini.</p>
    @endif
    <p style="color: var(--text-secondary); font-size: 12.5px; margin: 0;">
        Model S-BERT akan mengukur kemiripan semantik (Cosine Similarity) antara teks CV kandidat dengan deskripsi lowongan di atas, sekaligus memverifikasi kecocokan skill eksplisit dan alias.
    </p>
</div>

<div class="grid" style="display: grid; grid-template-columns: 3fr 2fr; gap: 24px;">
    <!-- Bagian 1: Daftar Lamaran Masuk -->
    <div>
        <div class="panel">
            <h2 style="font-size: 17px; margin-bottom: 16px; color: var(--text);">Daftar Pelamar Masuk ({{ $lamaranList->count() }})</h2>

            @if ($lamaranList->isEmpty())
                <p style="color: var(--text-muted); padding: 20px 0; text-align: center;">
                    Belum ada kandidat yang melamar pada lowongan ini. Gunakan sandbox di sebelah kanan untuk menguji teks CV secara langsung.
                </p>
            @else
                <table class="rubrik-table" style="width: 100%;">
                    <thead>
                        <tr>
                            <th>Kandidat</th>
                            <th style="width: 130px; text-align: center;">Skor S-BERT</th>
                            <th style="width: 120px; text-align: center;">Coverage Skill</th>
                            <th style="width: 110px; text-align: right;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($lamaranList as $lamaran)
                            @php
                                $ai = $aiResults->get($lamaran->id);
                                $out = $ai ? $ai->payload_output : null;
                                $score = $ai ? $ai->score : null;
                            @endphp
                            <tr>
                                <td>
                                    <strong style="color: var(--text);">{{ $lamaran->kandidat->name ?? 'Kandidat' }}</strong>
                                    <div style="font-size: 12px; color: var(--text-muted);">{{ $lamaran->kandidat->email ?? '' }}</div>
                                    @if ($out && !empty($out['matched_skills']))
                                        <div style="margin-top: 6px; display: flex; flex-wrap: wrap; gap: 4px;">
                                            @foreach ($out['matched_skills'] as $ms)
                                                <span style="font-size: 10.5px; background: rgba(52, 211, 153, 0.15); color: #34d399; padding: 1px 6px; border-radius: 4px;">
                                                    ✓ {{ $ms['skill'] }}
                                                </span>
                                            @endforeach
                                        </div>
                                    @endif
                                </td>
                                <td style="text-align: center;">
                                    @if ($ai && $ai->status === 'completed')
                                        <span style="font-size: 18px; font-weight: 800; color: {{ $score >= 70 ? '#34d399' : ($score >= 40 ? '#fbbf24' : '#f87171') }};">
                                            {{ $score }}%
                                        </span>
                                        <div style="font-size: 11px; color: var(--text-muted);">
                                            {{ $score >= 70 ? 'Good Fit' : ($score >= 40 ? 'Potential Fit' : 'No Fit') }}
                                        </div>
                                    @elseif ($ai && $ai->status === 'processing')
                                        <span style="font-size: 12px; color: #fbbf24;">Memproses...</span>
                                    @elseif ($ai && $ai->status === 'failed')
                                        <span style="font-size: 12px; color: #f87171;">Gagal</span>
                                    @else
                                        <span style="font-size: 12px; color: var(--text-muted);">Belum dihitung</span>
                                    @endif
                                </td>
                                <td style="text-align: center;">
                                    @if ($out && isset($out['skill_coverage']))
                                        <span style="font-size: 14px; font-weight: 700; color: var(--text-secondary);">
                                            {{ $out['skill_coverage'] }}%
                                        </span>
                                    @else
                                        <span style="font-size: 12px; color: var(--text-muted);">-</span>
                                    @endif
                                </td>
                                <td style="text-align: right;">
                                    <form action="{{ route('lowongan.matching.run', $lowongan) }}" method="post" style="display:inline;">
                                        @csrf
                                        <input type="hidden" name="lamaran_id" value="{{ $lamaran->id }}">
                                        <button type="submit" class="button button-secondary" style="font-size: 12px; padding: 4px 10px;">
                                            {{ $ai ? 'Hitung Ulang' : 'Hitung AI' }}
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>

    <!-- Bagian 2: Sandbox Pengujian Teks CV Langsung (Temporary / Fast Testing) -->
    <div>
        <div class="panel" style="background: var(--bg-surface); border: 1px solid var(--border);">
            <h2 style="font-size: 17px; margin-bottom: 8px; color: var(--text);">🧪 Sandbox Pengujian Teks CV</h2>
            <p style="font-size: 12.5px; color: var(--text-muted); margin-bottom: 16px;">
                Uji langsung teks CV kandidat tanpa menunggu modul resume parser untuk memverifikasi akurasi S-BERT dan Cosine Similarity.
            </p>

            <form action="{{ route('lowongan.matching.run', $lowongan) }}" method="post">
                @csrf
                <div style="margin-bottom: 14px;">
                    <label style="display: block; font-size: 12px; font-weight: 700; color: var(--text-secondary); margin-bottom: 6px;">
                        Teks CV / Ringkasan Pengalaman:
                    </label>
                    <textarea name="cv_text" rows="8" style="width: 100%; box-sizing: border-box; background: var(--bg-surface-alt); color: var(--text); border: 1px solid var(--border); border-radius: var(--radius-sm); padding: 10px; font-family: inherit; font-size: 13px;" placeholder="Tempelkan teks CV atau profil kandidat di sini... (contoh: Software engineer dengan pengalaman 3 tahun di Python, Laravel, REST API, PyTorch, dan PostgreSQL)">{{ session('sandbox_cv') ?? '' }}</textarea>
                </div>

                <button type="submit" class="button button-primary" style="width: 100%;">
                    ⚡ Uji Kecocokan S-BERT Sekarang
                </button>
            </form>

            @if (session('sandbox_result'))
                @php $res = session('sandbox_result'); @endphp
                <div style="margin-top: 20px; padding: 16px; background: var(--bg-surface-alt); border-radius: var(--radius-sm); border-top: 3px solid #6366f1;">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px;">
                        <span style="font-size: 12px; text-transform: uppercase; font-weight: 700; color: var(--text-muted);">Hasil Analisis AI</span>
                        <span style="font-size: 11px; background: rgba(99,102,241,0.2); color: #c7d2fe; padding: 2px 8px; border-radius: 4px;">
                            {{ $res['processing_time_ms'] ?? 0 }} ms
                        </span>
                    </div>

                    <div style="text-align: center; margin: 12px 0;">
                        <div style="font-size: 38px; font-weight: 900; color: {{ ($res['similarity_score'] ?? 0) >= 70 ? '#34d399' : (($res['similarity_score'] ?? 0) >= 40 ? '#fbbf24' : '#f87171') }};">
                            {{ $res['similarity_score'] ?? 0 }}%
                        </div>
                        <div style="font-size: 13px; font-weight: 600; color: var(--text-secondary);">
                            Kategori: {{ ($res['similarity_score'] ?? 0) >= 70 ? 'Good Fit' : (($res['similarity_score'] ?? 0) >= 40 ? 'Potential Fit' : 'No Fit') }}
                        </div>
                        <div style="font-size: 11.5px; color: var(--text-muted); margin-top: 2px;">
                            Cosine Similarity: {{ round($res['cosine_similarity'] ?? 0, 4) }}
                        </div>
                    </div>

                    @if (!empty($res['matched_skills']))
                        <div style="margin-top: 14px;">
                            <div style="font-size: 11.5px; font-weight: 700; color: #34d399; margin-bottom: 6px;">Skill Cocok:</div>
                            <div style="display: flex; flex-wrap: wrap; gap: 4px;">
                                @foreach ($res['matched_skills'] as $ms)
                                    <span style="font-size: 11px; background: rgba(52, 211, 153, 0.15); color: #34d399; padding: 2px 7px; border-radius: 4px;">
                                        ✓ {{ $ms['skill'] }} ({{ $ms['match_method'] }})
                                    </span>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    @if (!empty($res['not_found_skills']))
                        <div style="margin-top: 12px;">
                            <div style="font-size: 11.5px; font-weight: 700; color: #f87171; margin-bottom: 6px;">Skill Belum Terdeteksi:</div>
                            <div style="display: flex; flex-wrap: wrap; gap: 4px;">
                                @foreach ($res['not_found_skills'] as $nfs)
                                    <span style="font-size: 11px; background: rgba(248, 113, 113, 0.12); color: #fca5a5; padding: 2px 7px; border-radius: 4px;">
                                        ✕ {{ $nfs['skill'] }}
                                    </span>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <div style="margin-top: 14px; font-size: 11px; color: var(--text-muted); border-top: 1px solid var(--border); padding-top: 8px;">
                        Model: <code>{{ $res['model_version'] ?? 'sbert-murialo' }}</code>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
