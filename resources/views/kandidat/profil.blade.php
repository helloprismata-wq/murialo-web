@extends('layouts.kandidat')

@section('title', 'Profil & Pengaturan Akun · MURIALO')

@section('content')
<div class="space-y-8" x-data="{ saved: false }">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-[#0F172A] tracking-tight">Profil & Pengaturan Akun</h1>
            <p class="text-xs text-[#64748B] mt-1">Perbarui biodata diri, riwayat pendidikan, dan pengalaman profesional Anda</p>
        </div>
        <x-button type="button" @click="saved = true; $dispatch('show-toast', { message: 'Perubahan profil berhasil disimpan (Simulasi Demo)', type: 'success' })" variant="primary" size="md">
            Simpan Perubahan
        </x-button>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        {{-- Left: Form Profil Utama --}}
        <div class="lg:col-span-8 space-y-6">
            {{-- Identitas Diri --}}
            <x-card title="Data Pribadi & Kontak">
                <div class="space-y-4">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <x-input label="Nama Lengkap" name="nama" value="{{ $candidate['nama'] }}" required />
                        <x-input label="Alamat Email" name="email" type="email" value="{{ $candidate['email'] }}" required />
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <x-input label="Nomor Telepon / WhatsApp" name="telepon" value="{{ $candidate['telepon'] }}" required />
                        <x-input label="Domisili Kota" name="lokasi" value="{{ $candidate['lokasi'] }}" required />
                    </div>
                    <x-textarea
                        label="Ringkasan Profesional"
                        name="ringkasan"
                        rows="3"
                        value="Senior Software Engineer dengan spesialisasi pengembangan backend skalabel menggunakan Python, FastAPI, dan Laravel. Terbiasa mengelola arsitektur database relasional PostgreSQL serta orkestrasi kontainer Docker."
                    />
                </div>
            </x-card>

            {{-- Pendidikan & Pengalaman --}}
            <x-card title="Pendidikan & Pengalaman Kerja">
                <div class="space-y-4">
                    <x-input
                        label="Pendidikan Terakhir"
                        name="pendidikan"
                        value="{{ $candidate['pendidikan_terakhir'] }}"
                    />
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <x-input label="Total Tahun Pengalaman" name="pengalaman" value="{{ $candidate['pengalaman_tahun'] }} Tahun" />
                        <x-input label="Posisi Saat Ini / Terakhir" name="posisi_terakhir" value="Backend Software Engineer" />
                    </div>
                </div>
            </x-card>

            {{-- Keahlian --}}
            <x-card title="Daftar Keahlian Utama">
                <p class="text-xs text-[#64748B] mb-3">Keahlian ini digunakan oleh sistem Automated Skill Matching untuk mencocokkan profil Anda.</p>
                <div class="flex flex-wrap gap-2">
                    @foreach($candidate['skills'] as $skill)
                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold bg-[#EFF6FF] text-[#1D4ED8] border border-[#BFDBFE]">
                            <span>{{ $skill }}</span>
                            <button type="button" class="text-blue-400 hover:text-blue-700 font-bold" @click="$dispatch('show-toast', { message: 'Keahlian diperbarui', type: 'info' })">×</button>
                        </span>
                    @endforeach
                    <button type="button" @click="$dispatch('show-toast', { message: 'Silakan ketik keahlian baru di modul CV Ekstraksi', type: 'info' })" class="px-3 py-1.5 rounded-lg text-xs font-medium border border-dashed border-slate-300 text-slate-500 hover:border-blue-400 hover:text-blue-600 transition-smooth">
                        + Tambah Keahlian
                    </button>
                </div>
            </x-card>
        </div>

        {{-- Right: Status Akun & Keamanan --}}
        <div class="lg:col-span-4 space-y-6">
            <x-card title="Status Akun">
                <div class="space-y-4">
                    <div class="flex items-center justify-between text-xs pb-3 border-b border-[#E2E8F0]">
                        <span class="text-[#64748B]">Peran Akun</span>
                        <x-badge variant="success" size="sm">Kandidat Terdaftar</x-badge>
                    </div>
                    <div class="flex items-center justify-between text-xs pb-3 border-b border-[#E2E8F0]">
                        <span class="text-[#64748B]">Kelengkapan Data</span>
                        <strong class="text-[#2563EB] font-bold">{{ $candidate['kelengkapan_profil'] }}%</strong>
                    </div>
                    <div class="flex items-center justify-between text-xs">
                        <span class="text-[#64748B]">Keamanan Akun</span>
                        <span class="text-emerald-600 font-semibold">Tervalidasi ✓</span>
                    </div>
                </div>
            </x-card>

            <x-card title="Keamanan Kata Sandi">
                <div class="space-y-3">
                    <x-input label="Kata Sandi Baru" type="password" placeholder="••••••••" />
                    <x-input label="Ulangi Kata Sandi" type="password" placeholder="••••••••" />
                    <x-button type="button" @click="$dispatch('show-toast', { message: 'Kata sandi berhasil diperbarui (Simulasi)', type: 'success' })" variant="secondary" size="sm" class="w-full">
                        Perbarui Kata Sandi
                    </x-button>
                </div>
            </x-card>
        </div>
    </div>
</div>
@endsection
