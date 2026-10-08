@extends('layouts.kandidat')

@section('title', 'CV & Berkas Saya · MURIALO')

@section('content')
<div class="space-y-8" x-data="{ uploadModal: false }">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-[#0F172A] tracking-tight">Curriculum Vitae (CV) Saya</h1>
            <p class="text-xs text-[#64748B] mt-1">Kelola berkas CV aktif yang digunakan untuk melamar lowongan di MURIALO</p>
        </div>
        <div class="flex items-center gap-3">
            <x-button href="{{ route('kandidat.cv.ekstraksi') }}" variant="secondary" size="md">
                Lihat Hasil Ekstraksi AI →
            </x-button>
            <x-button type="button" @click="uploadModal = true" variant="primary" size="md">
                Unggah CV Baru
            </x-button>
        </div>
    </div>

    {{-- Active CV Card Preview --}}
    <div class="bg-white rounded-3xl border border-[#E2E8F0] p-6 sm:p-8 shadow-2xs space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-6 border-b border-[#E2E8F0]">
            <div class="flex items-center gap-4">
                <div class="w-14 h-14 rounded-2xl bg-red-50 border border-red-200 text-red-600 flex items-center justify-center font-bold text-lg shrink-0">
                    PDF
                </div>
                <div>
                    <div class="flex items-center gap-2">
                        <h2 class="text-base font-bold text-[#0F172A]">{{ $candidate['cv_filename'] }}</h2>
                        <x-badge variant="success" size="sm" dot>Aktif</x-badge>
                    </div>
                    <p class="text-xs text-[#64748B] mt-0.5">Ukuran: 1.4 MB · Diperbarui pada 22 September 2026</p>
                </div>
            </div>

            <div class="flex items-center gap-2.5">
                <x-button type="button" @click="$dispatch('show-toast', { message: 'Mengunduh CV aktif...', type: 'info' })" variant="secondary" size="sm">
                    Unduh Berkas
                </x-button>
                <x-button href="{{ route('kandidat.cv.ekstraksi') }}" variant="primary" size="sm">
                    Periksa Data Terurai
                </x-button>
            </div>
        </div>

        {{-- Document Metadata & Parser Summary --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 pt-2">
            <div class="p-4 rounded-xl bg-slate-50 border border-[#E2E8F0] space-y-1">
                <span class="text-xs text-[#64748B]">Status Ekstraksi AI</span>
                <p class="text-sm font-bold text-emerald-600 flex items-center gap-1.5">
                    <span>✓ Berhasil Diparse Penuh</span>
                </p>
                <p class="text-[11px] text-[#64748B]">Resume Parser FastAPI (Port 8001)</p>
            </div>

            <div class="p-4 rounded-xl bg-slate-50 border border-[#E2E8F0] space-y-1">
                <span class="text-xs text-[#64748B]">Keahlian Teridentifikasi</span>
                <p class="text-sm font-bold text-[#0F172A]">{{ count($candidate['skills']) }} Keahlian Terdeteksi</p>
                <p class="text-[11px] text-[#64748B]">Python, Laravel, Docker, PostgreSQL, dll.</p>
            </div>

            <div class="p-4 rounded-xl bg-slate-50 border border-[#E2E8F0] space-y-1">
                <span class="text-xs text-[#64748B]">Relevansi Lowongan</span>
                <p class="text-sm font-bold text-[#2563EB]">{{ $candidate['skor_matching'] }}% Skor Kesesuaian</p>
                <p class="text-[11px] text-[#64748B]">Tinggi untuk Backend Engineering</p>
            </div>
        </div>

        {{-- Document Visual Mock Viewer --}}
        <div class="border border-[#E2E8F0] rounded-2xl p-6 bg-slate-100/70 text-center space-y-3">
            <div class="max-w-md mx-auto bg-white rounded-xl shadow-xs border border-slate-200 p-8 text-left space-y-4">
                <div class="border-b border-slate-200 pb-3">
                    <h3 class="text-base font-bold text-[#0F172A]">{{ $candidate['nama'] }}</h3>
                    <p class="text-xs text-[#64748B]">{{ $candidate['email'] }} · {{ $candidate['telepon'] }} · {{ $candidate['lokasi'] }}</p>
                </div>
                <div class="space-y-1 text-xs">
                    <strong class="text-[#0F172A] block">Ringkasan Karier</strong>
                    <p class="text-slate-600 leading-relaxed text-[11px]">
                        5+ tahun pengalaman membangun sistem backend skala besar, optimasi kueri basis data relasional, dan integrasi API microservice.
                    </p>
                </div>
                <div class="space-y-1 text-xs">
                    <strong class="text-[#0F172A] block">Pendidikan</strong>
                    <p class="text-slate-600 text-[11px]">{{ $candidate['pendidikan_terakhir'] }}</p>
                </div>
            </div>
            <p class="text-[11px] text-[#64748B]">Pratinjau struktur dokumen CV yang terbaca oleh sistem MURIALO.</p>
        </div>
    </div>

    {{-- Modal Unggah CV Baru --}}
    <x-modal name="upload-cv-modal" title="Unggah Berkas CV Baru">
        <div class="space-y-4" x-data="{ uploading: false }">
            <div class="border-2 border-dashed border-[#CBD5E1] rounded-2xl p-8 text-center bg-slate-50 hover:bg-slate-100 transition-smooth cursor-pointer">
                <svg class="w-10 h-10 text-blue-500 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                </svg>
                <p class="text-sm font-semibold text-[#0F172A]">Klik atau seret file CV ke area ini</p>
                <p class="text-xs text-[#64748B] mt-1">Mendukung format PDF atau DOCX (Maks. 5 MB)</p>
            </div>

            <div class="flex items-center justify-end gap-3 pt-3 border-t border-[#E2E8F0]">
                <x-button type="button" @click="$dispatch('close-modal', 'upload-cv-modal')" variant="ghost" size="md">
                    Batal
                </x-button>
                <x-button type="button" @click="$dispatch('close-modal', 'upload-cv-modal'); $dispatch('show-toast', { message: 'CV baru berhasil diunggah dan sedang diparse AI Engine!', type: 'success' })" variant="primary" size="md">
                    Simpan & Ekstraksi
                </x-button>
            </div>
        </div>
    </x-modal>
</div>
@endsection
