@extends('layouts.kandidat')

@section('title', 'Hasil Ekstraksi & Konfirmasi Skill · MURIALO')

@section('content')
<div class="space-y-8" x-data="{ 
    newSkill: '', 
    skills: {{ json_encode($skills) }},
    addSkill() {
        if(this.newSkill.trim() && !this.skills.includes(this.newSkill.trim())) {
            this.skills.push(this.newSkill.trim());
            this.newSkill = '';
            $dispatch('show-toast', { message: 'Keahlian baru berhasil ditambahkan.', type: 'info' });
        }
    },
    removeSkill(index) {
        this.skills.splice(index, 1);
        $dispatch('show-toast', { message: 'Keahlian dihapus.', type: 'info' });
    }
}">
    {{-- Top Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <a href="{{ route('kandidat.cv.index') }}" class="text-xs text-[#2563EB] hover:underline">← Kembali ke CV Saya</a>
                <span class="text-xs text-slate-400">/</span>
                <span class="text-xs text-[#64748B]">Ekstraksi AI</span>
            </div>
            <h1 class="text-2xl font-bold text-[#0F172A] tracking-tight">Pratinjau & Konfirmasi Data Parser CV</h1>
            <p class="text-xs text-[#64748B] mt-1">Sistem AI mengekstrak data dari dokumen Anda. Periksa dan konfirmasi akurasinya sebelum evaluasi HR.</p>
        </div>
        <x-button type="button" @click="$dispatch('show-toast', { message: 'Data hasil ekstraksi CV berhasil dikonfirmasi dan disimpan ke profil.', type: 'success' })" variant="primary" size="md">
            Konfirmasi & Simpan Data
        </x-button>
    </div>

    {{-- Banner Status Ekstraksi --}}
    <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 flex items-center justify-between gap-4">
        <div class="flex items-center gap-3">
            <span class="w-8 h-8 rounded-xl bg-emerald-600 text-white flex items-center justify-center font-bold text-sm">✓</span>
            <div>
                <h4 class="text-sm font-bold text-emerald-950">Ekstraksi CV Berhasil (Tingkat Keyakinan 94%)</h4>
                <p class="text-xs text-emerald-700">Berkas sumber: {{ $candidate['cv_filename'] }} · Diproses oleh Resume Parser AI</p>
            </div>
        </div>
        <x-badge variant="success" size="sm">Status: Terverifikasi</x-badge>
    </div>

    {{-- Data Terurai Grid --}}
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        
        {{-- Left: Entitas Terekstrak --}}
        <div class="lg:col-span-8 space-y-6">
            
            {{-- Bagian 1: Data Kontak & Identitas --}}
            <x-card title="1. Identitas & Kontak Terekstrak">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <x-input label="Nama Terdeteksi" value="{{ $candidate['nama'] }}" />
                    <x-input label="Email Terdeteksi" value="{{ $candidate['email'] }}" />
                    <x-input label="Nomor Kontak" value="{{ $candidate['telepon'] }}" />
                    <x-input label="Domisili" value="{{ $candidate['lokasi'] }}" />
                </div>
            </x-card>

            {{-- Bagian 2: Riwayat Pendidikan & Pengalaman --}}
            <x-card title="2. Pendidikan & Linimasa Kerja">
                <div class="space-y-4">
                    <div class="p-4 rounded-xl bg-slate-50 border border-[#E2E8F0] space-y-1">
                        <span class="text-[11px] font-bold text-[#2563EB] uppercase">Pendidikan Terakhir</span>
                        <h4 class="text-xs font-bold text-[#0F172A]">{{ $candidate['pendidikan_terakhir'] }}</h4>
                        <p class="text-[11px] text-[#64748B]">Tahun Lulus: 2021 · IPK Terdeteksi: 3.82 / 4.00</p>
                    </div>

                    <div class="p-4 rounded-xl bg-slate-50 border border-[#E2E8F0] space-y-1">
                        <span class="text-[11px] font-bold text-[#2563EB] uppercase">Riwayat Pekerjaan Utama</span>
                        <h4 class="text-xs font-bold text-[#0F172A]">Senior Backend Software Engineer</h4>
                        <p class="text-[11px] text-[#64748B]">Durasi Total Terhitung: {{ $candidate['pengalaman_tahun'] }} Tahun Pengalaman Profesional</p>
                        <p class="text-xs text-slate-700 leading-relaxed pt-1">
                            "Membangun arsitektur microservices berbasis FastAPI & Laravel, mengelola cluster PostgreSQL, serta memimpin tim beranggotakan 4 engineer."
                        </p>
                    </div>
                </div>
            </x-card>

            {{-- Bagian 3: Keahlian / Skill Terdeteksi & Interaktif Add/Remove --}}
            <x-card title="3. Keahlian & Skill Terdeteksi" subtitle="Anda dapat menghapus atau menambahkan skill secara manual jika ada yang belum terdeteksi">
                <div class="space-y-4">
                    <div class="flex flex-wrap gap-2">
                        <template x-for="(skill, index) in skills" :key="index">
                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold bg-[#EFF6FF] text-[#1D4ED8] border border-[#BFDBFE]">
                                <span x-text="skill"></span>
                                <button type="button" @click="removeSkill(index)" class="text-blue-400 hover:text-red-600 font-bold ml-1" title="Hapus skill">×</button>
                            </span>
                        </template>
                    </div>

                    {{-- Form Tambah Skill Manual --}}
                    <div class="pt-3 border-t border-[#E2E8F0] flex gap-2">
                        <input
                            type="text"
                            x-model="newSkill"
                            @keydown.enter.prevent="addSkill()"
                            placeholder="Ketik nama skill baru (misal: Redis, GraphQL)..."
                            class="flex-1 rounded-lg border border-[#E2E8F0] px-3.5 py-2 text-xs text-[#0F172A] focus:border-[#2563EB] focus:ring-2 focus:ring-blue-100 outline-none"
                        />
                        <x-button type="button" @click="addSkill()" variant="secondary" size="sm">
                            + Tambah
                        </x-button>
                    </div>
                </div>
            </x-card>
        </div>

        {{-- Right: Panduan & Rekap Akurasi --}}
        <div class="lg:col-span-4 space-y-6">
            <x-card title="Keterangan Parser AI">
                <div class="space-y-3 text-xs text-[#64748B]">
                    <p class="leading-relaxed">
                        Modul Resume Parser menggunakan ekstraksi teks dan pengenalan entitas bernama (NER) untuk mengklasifikasikan bagian CV secara otomatis.
                    </p>
                    <div class="p-3 rounded-xl bg-slate-50 border border-[#E2E8F0] space-y-1.5">
                        <div class="flex justify-between font-semibold text-[#0F172A]">
                            <span>Skill Terverifikasi</span>
                            <span class="text-emerald-600" x-text="skills.length + ' Skill'"></span>
                        </div>
                        <div class="flex justify-between font-semibold text-[#0F172A]">
                            <span>Pendidikan</span>
                            <span class="text-blue-600">Terdeteksi</span>
                        </div>
                        <div class="flex justify-between font-semibold text-[#0F172A]">
                            <span>Pengalaman</span>
                            <span class="text-blue-600">5 Tahun</span>
                        </div>
                    </div>
                    <p class="text-[11px] leading-relaxed">
                        Data ini langsung terhubung ke modul <strong class="text-[#0F172A]">Automated Skill Matching</strong> ketika Anda melamar ke posisi pekerjaan.
                    </p>
                </div>
            </x-card>

            <div class="p-5 rounded-2xl bg-[#EFF6FF] border border-[#BFDBFE] space-y-3">
                <h4 class="text-xs font-bold text-[#1E40AF] uppercase tracking-wider">Langkah Selanjutnya</h4>
                <p class="text-xs text-[#1E3A8A] leading-relaxed">
                    Setelah mengonfirmasi data, jelajahi lowongan yang sesuai dengan keahlian Anda untuk melihat estimasi skor kesesuaian semantik.
                </p>
                <x-button href="{{ route('kandidat.lowongan') }}" variant="primary" size="sm" class="w-full">
                    Jelajah Lowongan Terbuka →
                </x-button>
            </div>
        </div>

    </div>
</div>
@endsection
