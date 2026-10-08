@extends('layouts.hr')

@section('title', 'Tambah Lowongan Baru · HR Portal')
@section('page_title', 'Lowongan / Baru')
@section('header_title', 'Buat Lowongan Pekerjaan Baru')

@section('content')
<div class="space-y-6 max-w-4xl" x-data="{ published: false }">
    <div class="flex items-center justify-between">
        <a href="{{ route('hr.lowongan.index') }}" class="text-xs text-[#2563EB] hover:underline">
            ← Kembali ke Daftar Lowongan
        </a>
    </div>

    <form action="{{ route('hr.lowongan.index') }}" method="GET" class="space-y-6">
        <x-card title="Informasi Dasar Lowongan">
            <div class="space-y-4">
                <x-input label="Judul Posisi Pekerjaan" name="judul" placeholder="Contoh: Senior Frontend Engineer" required />
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <x-select label="Departemen" name="departemen" required>
                        <option value="Engineering">Engineering</option>
                        <option value="Product & Design">Product & Design</option>
                        <option value="AI Research Lab">AI Research Lab</option>
                        <option value="Human Resources">Human Resources</option>
                    </x-select>
                    <x-select label="Tipe Pekerjaan" name="tipe_pekerjaan" required>
                        <option value="Penuh Waktu">Penuh Waktu</option>
                        <option value="Kontrak">Kontrak</option>
                        <option value="Magang">Magang</option>
                    </x-select>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <x-input label="Lokasi / Skema Kerja" name="lokasi" value="Jakarta Selatan (Hybrid)" required />
                    <x-input label="Gaji Minimum (Rp)" name="gaji_min" type="number" value="12000000" />
                    <x-input label="Gaji Maksimum (Rp)" name="gaji_max" type="number" value="18000000" />
                </div>
                <x-textarea label="Deskripsi Pekerjaan & Tanggung Jawab" name="deskripsi" rows="4" placeholder="Jelaskan peran pekerjaan..." required />
            </div>
        </x-card>

        <x-card title="Kualifikasi & Ambang Batas AI">
            <div class="space-y-4">
                <x-textarea
                    label="Keahlian Wajib (Pisahkan dengan koma)"
                    name="kualifikasi_wajib"
                    rows="2"
                    value="Python, FastAPI, Laravel, PostgreSQL, Docker"
                    helper="Skill ini akan diutamakan pada kalkulasi bobot semantik S-BERT."
                />
                <x-textarea
                    label="Keahlian Tambahan / Opsional"
                    name="kualifikasi_opsional"
                    rows="2"
                    value="Redis, PyTorch, Kubernetes"
                />
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <x-input label="Batas Waktu Lamaran" name="deadline" type="date" value="2026-11-30" required />
                    <x-input label="Kuota Kebutuhan Rekrutmen" name="kuota" type="number" value="2" required />
                </div>
            </div>
        </x-card>

        <div class="flex items-center justify-end gap-3 pt-3">
            <x-button href="{{ route('hr.lowongan.index') }}" variant="secondary" size="md">
                Batal
            </x-button>
            <x-button type="submit" variant="primary" size="md">
                Publikasikan Lowongan (Demo)
            </x-button>
        </div>
    </form>
</div>
@endsection
