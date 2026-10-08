@extends('layouts.hr')

@section('title', 'Detail Pelamar: ' . $candidate['nama'] . ' · HR Portal')
@section('page_title', 'Pelamar / ' . $candidate['nama'])
@section('header_title', 'Profil & Evaluasi Terpadu Pelamar')

@section('content')
<div class="space-y-8" x-data="{ currentTab: 'ringkasan' }">
    {{-- Top Candidate Header Card --}}
    <div class="bg-white rounded-3xl border border-[#E2E8F0] p-6 sm:p-8 shadow-2xs space-y-6">
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6 pb-6 border-b border-[#E2E8F0]">
            <div class="flex items-start gap-4">
                <div class="w-16 h-16 rounded-2xl bg-gradient-to-tr from-[#2563EB] to-indigo-600 text-white font-extrabold text-2xl flex items-center justify-center shrink-0 shadow-sm">
                    {{ substr($candidate['nama'], 0, 1) }}
                </div>
                <div class="space-y-1">
                    <div class="flex items-center gap-2">
                        <h1 class="text-xl sm:text-2xl font-extrabold text-[#0F172A] tracking-tight">{{ $candidate['nama'] }}</h1>
                        <x-badge variant="primary" size="md">{{ $candidate['status_pipeline'] }}</x-badge>
                    </div>
                    <p class="text-xs text-[#64748B]">
                        Melamar untuk <strong class="text-[#0F172A]">{{ $candidate['posisi_dilamar'] }}</strong> · Dikirim {{ date('d M Y', strtotime($candidate['tanggal_lamar'])) }}
                    </p>
                    <div class="flex flex-wrap items-center gap-3 pt-1 text-xs text-[#64748B]">
                        <span>✉️ {{ $candidate['email'] }}</span>
                        <span>📱 {{ $candidate['telepon'] }}</span>
                        <span>📍 {{ $candidate['lokasi'] }}</span>
                    </div>
                </div>
            </div>

            <div class="flex flex-wrap items-center gap-3">
                <x-button type="button" @click="$dispatch('show-toast', { message: 'Tahapan pelamar dialihkan ke tahapan berikutnya (Simulasi)', type: 'success' })" variant="primary" size="md">
                    Ubah Tahapan Seleksi
                </x-button>
                <x-button type="button" @click="$dispatch('show-toast', { message: 'Mengunduh CV...', type: 'info' })" variant="secondary" size="md">
                    Unduh Dokumen CV
                </x-button>
            </div>
        </div>

        {{-- Quick Evaluation Metrics --}}
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 text-xs">
            <div class="p-3.5 rounded-xl bg-slate-50 border border-[#E2E8F0]">
                <span class="text-[#64748B] block mb-1">Skor Kesesuaian Skill</span>
                <span class="text-xl font-black text-[#2563EB]">{{ $candidate['skor_matching'] }}%</span>
                <span class="text-[10px] text-slate-400 block mt-0.5">Semantik S-BERT</span>
            </div>
            <div class="p-3.5 rounded-xl bg-slate-50 border border-[#E2E8F0]">
                <span class="text-[#64748B] block mb-1">Status Smart Grading</span>
                <span class="text-xl font-black text-emerald-600">{{ $candidate['skor_tes'] ?? 'Menunggu' }}</span>
                <span class="text-[10px] text-slate-400 block mt-0.5">{{ $candidate['status_tes'] }}</span>
            </div>
            <div class="p-3.5 rounded-xl bg-slate-50 border border-[#E2E8F0]">
                <span class="text-[#64748B] block mb-1">Pengalaman Relevan</span>
                <span class="text-xl font-black text-[#0F172A]">{{ $candidate['pengalaman_tahun'] }} Tahun</span>
                <span class="text-[10px] text-slate-400 block mt-0.5">Software Engineering</span>
            </div>
            <div class="p-3.5 rounded-xl bg-slate-50 border border-[#E2E8F0]">
                <span class="text-[#64748B] block mb-1">Status Anomali</span>
                @if($candidate['anomali_detected'])
                    <span class="text-base font-bold text-amber-600 block mt-0.5">Perlu Tinjauan</span>
                @else
                    <span class="text-base font-bold text-emerald-600 block mt-0.5">Normal (Bersih)</span>
                @endif
                <span class="text-[10px] text-slate-400 block mt-0.5">Pola Seleksi</span>
            </div>
        </div>
    </div>

    {{-- Tab Navigation Bar --}}
    <div class="border-b border-[#E2E8F0]">
        <nav class="flex space-x-8 overflow-x-auto text-sm font-medium">
            <button
                type="button"
                @click="currentTab = 'ringkasan'"
                :class="currentTab === 'ringkasan' ? 'border-[#2563EB] text-[#2563EB] font-bold border-b-2' : 'border-transparent text-[#64748B] hover:text-[#0F172A]'"
                class="pb-3.5 pt-1 transition-smooth cursor-pointer whitespace-nowrap"
            >
                Ringkasan Pelamar
            </button>
            <button
                type="button"
                @click="currentTab = 'cv'"
                :class="currentTab === 'cv' ? 'border-[#2563EB] text-[#2563EB] font-bold border-b-2' : 'border-transparent text-[#64748B] hover:text-[#0F172A]'"
                class="pb-3.5 pt-1 transition-smooth cursor-pointer whitespace-nowrap"
            >
                CV & Ekstraksi Parser
            </button>
            <button
                type="button"
                @click="currentTab = 'matching'"
                :class="currentTab === 'matching' ? 'border-[#2563EB] text-[#2563EB] font-bold border-b-2' : 'border-transparent text-[#64748B] hover:text-[#0F172A]'"
                class="pb-3.5 pt-1 transition-smooth cursor-pointer whitespace-nowrap"
            >
                Skill Matching (S-BERT)
            </button>
            <button
                type="button"
                @click="currentTab = 'tes'"
                :class="currentTab === 'tes' ? 'border-[#2563EB] text-[#2563EB] font-bold border-b-2' : 'border-transparent text-[#64748B] hover:text-[#0F172A]'"
                class="pb-3.5 pt-1 transition-smooth cursor-pointer whitespace-nowrap"
            >
                Hasil Tes & Smart Grading
            </button>
            <button
                type="button"
                @click="currentTab = 'riwayat'"
                :class="currentTab === 'riwayat' ? 'border-[#2563EB] text-[#2563EB] font-bold border-b-2' : 'border-transparent text-[#64748B] hover:text-[#0F172A]'"
                class="pb-3.5 pt-1 transition-smooth cursor-pointer whitespace-nowrap"
            >
                Riwayat Aktivitas
            </button>
        </nav>
    </div>

    {{-- TAB 1: RINGKASAN --}}
    <div x-show="currentTab === 'ringkasan'" class="space-y-6">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            <div class="lg:col-span-8 space-y-6">
                <x-card title="Catatan & Evaluasi Tim HR">
                    <div class="p-4 rounded-xl bg-slate-50 border border-[#E2E8F0] space-y-2 text-xs leading-relaxed text-[#334155]">
                        <p class="font-semibold text-[#0F172A]">Catatan Reviewer:</p>
                        <p>{{ $candidate['catatan_hr'] }}</p>
                    </div>
                </x-card>

                <x-card title="Pendidikan & Pengalaman Terverifikasi">
                    <div class="space-y-3 text-xs">
                        <div class="p-3.5 rounded-xl bg-slate-50 border border-[#E2E8F0]">
                            <span class="text-[11px] font-bold text-[#2563EB] uppercase">Pendidikan</span>
                            <p class="font-bold text-[#0F172A] text-sm mt-0.5">{{ $candidate['pendidikan_terakhir'] }}</p>
                        </div>
                        <div class="p-3.5 rounded-xl bg-slate-50 border border-[#E2E8F0]">
                            <span class="text-[11px] font-bold text-[#2563EB] uppercase">Pengalaman Kerja</span>
                            <p class="font-bold text-[#0F172A] text-sm mt-0.5">{{ $candidate['pengalaman_tahun'] }} Tahun Pengalaman di Bidang Software Development</p>
                        </div>
                    </div>
                </x-card>
            </div>

            <div class="lg:col-span-4 space-y-6">
                <x-card title="Informasi Lowongan">
                    <div class="space-y-2 text-xs text-[#64748B]">
                        <p>Posisi: <strong class="text-[#0F172A]">{{ $job['judul'] }}</strong></p>
                        <p>Departemen: <strong class="text-[#0F172A]">{{ $job['departemen'] }}</strong></p>
                        <p>Lokasi: <strong class="text-[#0F172A]">{{ $job['lokasi'] }}</strong></p>
                        <p>Tipe: <strong class="text-[#0F172A]">{{ $job['tipe_pekerjaan'] }}</strong></p>
                    </div>
                </x-card>
            </div>
        </div>
    </div>

    {{-- TAB 2: CV & PARSED --}}
    <div x-show="currentTab === 'cv'" style="display: none;" class="space-y-6">
        <x-card title="Hasil Ekstraksi Resume Parser AI" subtitle="Entitas diekstraksi dari berkas {{ $candidate['cv_filename'] }}">
            <div class="space-y-4">
                <div class="p-4 rounded-xl bg-slate-50 border border-[#E2E8F0] space-y-2 text-xs">
                    <span class="font-bold text-[#0F172A] block text-sm">Keahlian Terdeteksi ({{ count($candidate['skills']) }} Item)</span>
                    <div class="flex flex-wrap gap-2 pt-1">
                        @foreach($candidate['skills'] as $s)
                            <span class="px-2.5 py-1 rounded-lg text-xs font-semibold bg-emerald-50 text-emerald-800 border border-emerald-200">
                                {{ $s }}
                            </span>
                        @endforeach
                    </div>
                </div>

                <div class="p-4 rounded-xl bg-slate-50 border border-[#E2E8F0] space-y-2 text-xs">
                    <span class="font-bold text-[#0F172A] block text-sm">Pratinjau Teks Dokumen CV</span>
                    <pre class="bg-white p-4 rounded-lg border border-slate-200 text-[11px] text-slate-700 font-mono whitespace-pre-wrap leading-relaxed max-h-64 overflow-y-auto">
Nama: {{ $candidate['nama'] }}
Email: {{ $candidate['email'] }}
Kontak: {{ $candidate['telepon'] }}
Pendidikan: {{ $candidate['pendidikan_terakhir'] }}
Ringkasan: 5 tahun pengalaman membangun sistem backend scalable menggunakan Python, FastAPI, dan Laravel. Menguasai arsitektur database relasional PostgreSQL serta orkestrasi kontainer Docker.
Keahlian Utama: {{ implode(', ', $candidate['skills']) }}
                    </pre>
                </div>
            </div>
        </x-card>
    </div>

    {{-- TAB 3: SKILL MATCHING (S-BERT) --}}
    <div x-show="currentTab === 'matching'" style="display: none;" class="space-y-6">
        <x-card title="Analisis Kesesuaian Semantik Sentence-BERT" subtitle="Perbandingan kualifikasi lowongan dengan bukti teks CV">
            <div class="space-y-6">
                <div class="p-5 rounded-2xl bg-[#EFF6FF] border border-[#BFDBFE] flex items-center justify-between">
                    <div>
                        <span class="text-xs font-bold text-[#1E40AF] uppercase tracking-wider">Skor Kesesuaian Semantik</span>
                        <h4 class="text-2xl font-black text-[#2563EB] mt-0.5">{{ $candidate['skor_matching'] }}% (Tinggi)</h4>
                        <p class="text-xs text-[#1E3A8A]">Kandidat memenuhi seluruh kualifikasi wajib dan sebagian kualifikasi tambahan.</p>
                    </div>
                    <x-badge variant="primary" size="lg">S-BERT Cosine</x-badge>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <h4 class="text-xs font-bold text-emerald-800 uppercase tracking-wider">Keahlian Ditemukan Terbukti (✓)</h4>
                        <div class="space-y-2">
                            @foreach($job['kualifikasi_wajib'] as $kw)
                                <div class="p-3 rounded-xl bg-emerald-50 border border-emerald-200 text-xs flex items-center justify-between">
                                    <span class="font-semibold text-emerald-950">{{ $kw }}</span>
                                    <span class="text-[11px] text-emerald-700 font-medium">Terbukti di CV ✓</span>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="space-y-2">
                        <h4 class="text-xs font-bold text-slate-600 uppercase tracking-wider">Keahlian Tambahan / Opsional</h4>
                        <div class="space-y-2">
                            @foreach($job['kualifikasi_opsional'] as $ko)
                                <div class="p-3 rounded-xl bg-slate-50 border border-slate-200 text-xs flex items-center justify-between">
                                    <span class="font-medium text-slate-800">{{ $ko }}</span>
                                    <span class="text-[11px] text-slate-500">Opsional</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </x-card>
    </div>

    {{-- TAB 4: TES & SMART GRADING --}}
    <div x-show="currentTab === 'tes'" style="display: none;" class="space-y-6">
        <x-card title="Hasil Penilaian Smart Grading" subtitle="Perbandingan prediksi skor AI dan nilai akhir reviewer HR">
            <div class="space-y-5">
                <div class="flex items-center justify-between p-4 rounded-xl bg-slate-50 border border-[#E2E8F0]">
                    <div>
                        <h4 class="text-sm font-bold text-[#0F172A]">Paket A: Pemahaman Informasi & Instruksi Operasional</h4>
                        <p class="text-xs text-[#64748B]">Durasi pengerjaan: 22 Menit dari 25 Menit alokasi</p>
                    </div>
                    <div class="text-right">
                        <span class="text-xs text-[#64748B]">Nilai Akhir:</span>
                        <p class="text-2xl font-black text-emerald-600">92.0 / 100</p>
                    </div>
                </div>

                <div class="pt-2">
                    <x-button href="{{ route('hr.smart_grading.penilaian', $candidate['id']) }}" variant="primary" size="md">
                        Buka Lembar Evaluasi Rubrik Detail →
                    </x-button>
                </div>
            </div>
        </x-card>
    </div>

    {{-- TAB 5: RIWAYAT AKTIVITAS --}}
    <div x-show="currentTab === 'riwayat'" style="display: none;" class="space-y-6">
        <x-card title="Riwayat Log Aktivitas Seleksi">
            <ul class="divide-y divide-[#E2E8F0] text-xs space-y-2">
                <li class="py-3 flex items-center justify-between">
                    <div>
                        <strong class="text-[#0F172A] block">Publikasi Hasil Tes Smart Grading</strong>
                        <span class="text-[#64748B]">Nilai 92.0 disahkan oleh HRD Murialo</span>
                    </div>
                    <span class="text-[#64748B]">28 Sep 2026, 15:30 WIB</span>
                </li>
                <li class="py-3 flex items-center justify-between">
                    <div>
                        <strong class="text-[#0F172A] block">Penyelesaian Tes Esai Online</strong>
                        <span class="text-[#64748B]">5 butir soal diserahkan oleh kandidat</span>
                    </div>
                    <span class="text-[#64748B]">28 Sep 2026, 10:22 WIB</span>
                </li>
                <li class="py-3 flex items-center justify-between">
                    <div>
                        <strong class="text-[#0F172A] block">Ekstraksi Resume Parser AI</strong>
                        <span class="text-[#64748B]">9 keahlian teridentifikasi dengan skor kesesuaian 92%</span>
                    </div>
                    <span class="text-[#64748B]">22 Sep 2026, 14:10 WIB</span>
                </li>
                <li class="py-3 flex items-center justify-between">
                    <div>
                        <strong class="text-[#0F172A] block">Berkas Lamaran Diterima</strong>
                        <span class="text-[#64748B]">Lamaran masuk melalui portal karir publik</span>
                    </div>
                    <span class="text-[#64748B]">22 Sep 2026, 14:05 WIB</span>
                </li>
            </ul>
        </x-card>
    </div>
</div>
@endsection
