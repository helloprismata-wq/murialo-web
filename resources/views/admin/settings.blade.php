@extends('layouts.admin', [
    'title' => 'Pengaturan Platform & Perusahaan',
    'subtitle' => 'Konfigurasi identitas organisasi, preferensi AI Engine, dan parameter seleksi'
])

@section('content')
<div class="space-y-6" x-data="{
    companyName: 'PT Muria Logika Nusantara',
    brandTagline: 'Rekrut talenta yang tepat, lebih terarah.',
    emailOfficial: 'recruitment@murialogika.co.id',
    aiEndpoint: 'http://127.0.0.1:8001',
    thresholdMatching: '75',
    maxCvSize: '5',
    autoGradeEnabled: true,
    showSaved: false,

    saveSettings() {
        this.showSaved = true;
        setTimeout(() => this.showSaved = false, 3500);
    }
}">

    <form @submit.prevent="saveSettings()" class="space-y-6">
        <!-- Section 1: Profil Organisasi -->
        <div class="bg-white rounded-xl border border-slate-200 p-6 shadow-sm space-y-4">
            <div class="border-b border-slate-100 pb-3">
                <h3 class="text-base font-bold text-slate-900">Identitas Perusahaan & Portal Karir</h3>
                <p class="text-xs text-slate-500">Nama dan informasi ini akan tampil pada landing page, formulir lowongan, dan undangan tes.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Nama Organisasi / Perusahaan</label>
                    <input type="text" x-model="companyName" class="w-full text-xs sm:text-sm border border-slate-300 rounded-lg px-3 py-2 bg-white focus:outline-none focus:ring-1 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Tagline Rekrutmen</label>
                    <input type="text" x-model="brandTagline" class="w-full text-xs sm:text-sm border border-slate-300 rounded-lg px-3 py-2 bg-white focus:outline-none focus:ring-1 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Email Resmi HR</label>
                    <input type="email" x-model="emailOfficial" class="w-full text-xs sm:text-sm border border-slate-300 rounded-lg px-3 py-2 bg-white focus:outline-none focus:ring-1 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Lokasi Kantor Pusat</label>
                    <input type="text" value="Jakarta Selatan, DKI Jakarta" class="w-full text-xs sm:text-sm border border-slate-300 rounded-lg px-3 py-2 bg-white focus:outline-none focus:ring-1 focus:ring-blue-500">
                </div>
            </div>
        </div>

        <!-- Section 2: Integrasi FastAPI Microservice -->
        <div class="bg-white rounded-xl border border-slate-200 p-6 shadow-sm space-y-4">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                <div>
                    <h3 class="text-base font-bold text-slate-900">Konektivitas AI Engine (FastAPI)</h3>
                    <p class="text-xs text-slate-500">Pengaturan komunikasi service parser CV, automated skill matching, dan deteksi anomali.</p>
                </div>
                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-emerald-50 text-emerald-700 border border-emerald-200">
                    <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                    <span>Microservice Siap (Port 8001)</span>
                </span>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Service Endpoint URL</label>
                    <input type="text" x-model="aiEndpoint" class="w-full text-xs sm:text-sm border border-slate-300 rounded-lg px-3 py-2 font-mono bg-white focus:outline-none focus:ring-1 focus:ring-blue-500">
                    <span class="text-[11px] text-slate-500 mt-1 block">Default lokal: http://127.0.0.1:8001</span>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Ambang Batas Minimum Skill Match (%)</label>
                    <input type="number" x-model="thresholdMatching" class="w-full text-xs sm:text-sm border border-slate-300 rounded-lg px-3 py-2 bg-white focus:outline-none focus:ring-1 focus:ring-blue-500">
                    <span class="text-[11px] text-slate-500 mt-1 block">Pelamar di atas skor ini akan otomatis ditandai Direkomendasikan</span>
                </div>
            </div>

            <div class="pt-2 flex items-center gap-3">
                <input type="checkbox" id="autoGrade" x-model="autoGradeEnabled" class="w-4 h-4 rounded text-blue-600 border-slate-300 focus:ring-blue-500">
                <label for="autoGrade" class="text-xs sm:text-sm font-medium text-slate-800">
                    Aktifkan saran penilaian otomatis (Smart Grading Assist) saat jawaban esai dikumpulkan kandidat
                </label>
            </div>
        </div>

        <!-- Section 3: Batasan Dokumen & Unggahan -->
        <div class="bg-white rounded-xl border border-slate-200 p-6 shadow-sm space-y-4">
            <div class="border-b border-slate-100 pb-3">
                <h3 class="text-base font-bold text-slate-900">Batasan Berkas & Dokumen Seleksi</h3>
                <p class="text-xs text-slate-500">Validasi ukuran dan jenis ekstensi CV kandidat yang dapat diproses oleh parser.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Ukuran Maksimal Berkas CV (MB)</label>
                    <input type="number" x-model="maxCvSize" class="w-full text-xs sm:text-sm border border-slate-300 rounded-lg px-3 py-2 bg-white">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Format Dokumen Didukung</label>
                    <input type="text" value="PDF (.pdf), Microsoft Word (.docx)" readonly class="w-full text-xs sm:text-sm border border-slate-300 rounded-lg px-3 py-2 bg-slate-50 text-slate-600">
                </div>
            </div>
        </div>

        <!-- Tombol Simpan -->
        <div class="flex items-center justify-end gap-3 pt-2">
            <button type="submit" class="px-5 py-2.5 text-xs sm:text-sm font-semibold text-white bg-blue-600 hover:bg-blue-700 rounded-xl shadow-sm transition">
                Simpan Perubahan Pengaturan
            </button>
        </div>
    </form>

    <!-- Toast Saved -->
    <div x-show="showSaved" x-cloak class="fixed bottom-6 right-6 z-50 bg-slate-900 text-white px-4 py-3 rounded-xl shadow-lg text-xs flex items-center gap-3">
        <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
        <span>Pengaturan platform berhasil diperbarui (Simulasi Demo)</span>
    </div>

</div>
@endsection
