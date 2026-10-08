@extends('layouts.hr')

@section('title', 'Edit Lowongan · HR Portal')
@section('page_title', 'Lowongan / Edit')
@section('header_title', 'Perbarui Lowongan Pekerjaan')

@section('content')
<div class="space-y-6 max-w-4xl" x-data="{ updated: false }">
    <div class="flex items-center justify-between">
        <a href="{{ route('hr.lowongan.index') }}" class="text-xs text-[#2563EB] hover:underline">
            ← Kembali ke Daftar Lowongan
        </a>
        <x-badge variant="success" size="sm" dot>Status: {{ $job['status'] }}</x-badge>
    </div>

    <form action="{{ route('hr.lowongan.index') }}" method="GET" class="space-y-6">
        <x-card title="Informasi Lowongan">
            <div class="space-y-4">
                <x-input label="Judul Posisi Pekerjaan" name="judul" value="{{ $job['judul'] }}" required />
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <x-input label="Departemen" name="departemen" value="{{ $job['departemen'] }}" required />
                    <x-input label="Tipe Pekerjaan" name="tipe_pekerjaan" value="{{ $job['tipe_pekerjaan'] }}" required />
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <x-input label="Lokasi" name="lokasi" value="{{ $job['lokasi'] }}" required />
                    <x-input label="Gaji Minimum (Rp)" name="gaji_min" type="number" value="{{ $job['gaji_min'] }}" />
                    <x-input label="Gaji Maksimum (Rp)" name="gaji_max" type="number" value="{{ $job['gaji_max'] }}" />
                </div>
                <x-textarea label="Deskripsi Pekerjaan" name="deskripsi" rows="4" value="{{ $job['deskripsi'] }}" required />
            </div>
        </x-card>

        <x-card title="Kualifikasi Keahlian">
            <div class="space-y-4">
                <x-textarea
                    label="Kualifikasi Wajib"
                    name="kualifikasi_wajib"
                    rows="2"
                    value="{{ implode(', ', $job['kualifikasi_wajib']) }}"
                />
                <x-textarea
                    label="Kualifikasi Opsional"
                    name="kualifikasi_opsional"
                    rows="2"
                    value="{{ implode(', ', $job['kualifikasi_opsional']) }}"
                />
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <x-input label="Batas Waktu" name="deadline" type="date" value="{{ $job['deadline'] }}" required />
                    <x-input label="Kuota Kebutuhan" name="kuota" type="number" value="{{ $job['kuota'] }}" required />
                </div>
            </div>
        </x-card>

        <div class="flex items-center justify-end gap-3 pt-3">
            <x-button href="{{ route('hr.lowongan.index') }}" variant="secondary" size="md">
                Batal
            </x-button>
            <x-button type="submit" variant="primary" size="md">
                Simpan Perubahan (Demo)
            </x-button>
        </div>
    </form>
</div>
@endsection
