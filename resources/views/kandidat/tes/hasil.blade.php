@extends('layouts.kandidat')

@section('title', 'Hasil Penyelesaian Tes · MURIALO')

@section('content')
<div class="space-y-8">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <a href="{{ route('kandidat.tes.index') }}" class="text-xs text-[#2563EB] hover:underline">← Kembali ke Tugas Tes</a>
                <span class="text-xs text-slate-400">/</span>
                <span class="text-xs text-[#64748B]">{{ $id }}</span>
            </div>
            <h1 class="text-2xl font-bold text-[#0F172A] tracking-tight">Status Penyelesaian Tes Esai</h1>
            <p class="text-xs text-[#64748B] mt-1">Paket A: Pemahaman Informasi & Instruksi Operasional</p>
        </div>
        <x-badge variant="success" size="lg" dot>
            Evaluasi Selesai
        </x-badge>
    </div>

    {{-- Score Hero Card --}}
    <div class="bg-white rounded-3xl border border-[#E2E8F0] p-6 sm:p-8 shadow-2xs space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-6 pb-6 border-b border-[#E2E8F0]">
            <div class="space-y-2">
                <span class="text-xs font-bold text-[#2563EB] uppercase tracking-wider">Hasil Asesmen Resmi</span>
                <h2 class="text-xl font-bold text-[#0F172A]">Skor Evaluasi Telah Dipublikasikan</h2>
                <p class="text-xs text-[#64748B] max-w-lg leading-relaxed">
                    Jawaban Anda telah dievaluasi oleh sistem Smart Grading dan telah diverifikasi oleh tim penilai Human Resources PT Muria Logika Nusantara.
                </p>
            </div>

            <div class="p-6 rounded-2xl bg-[#EFF6FF] border border-[#BFDBFE] text-center min-w-[180px]">
                <span class="text-xs text-[#1E40AF] font-bold block mb-1">Nilai Akhir Tes</span>
                <span class="text-4xl font-black text-[#2563EB]">92.0</span>
                <span class="text-xs text-[#64748B] block mt-1">Skala 0 - 100</span>
            </div>
        </div>

        {{-- Verification Metadata Grid --}}
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 text-xs text-[#64748B]">
            <div>
                <span class="block text-slate-400">Tanggal Pengerjaan</span>
                <strong class="text-[#0F172A]">28 September 2026, 10:22 WIB</strong>
            </div>
            <div>
                <span class="block text-slate-400">Durasi Pengerjaan Digunakan</span>
                <strong class="text-[#0F172A]">22 Menit (Alokasi: 25 Menit)</strong>
            </div>
            <div>
                <span class="block text-slate-400">Jumlah Soal Terjawab</span>
                <strong class="text-[#0F172A]">5 dari 5 Butir (100% Lengkap)</strong>
            </div>
        </div>
    </div>

    {{-- General Reviewer Feedback (Aman untuk kandidat) --}}
    <div class="grid grid-cols-1 md:grid-cols-12 gap-8">
        <div class="md:col-span-8 space-y-6">
            <x-card title="Catatan Evaluasi Umum dari Tim Seleksi">
                <div class="p-4 rounded-xl bg-slate-50 border border-[#E2E8F0] space-y-2 text-xs leading-relaxed text-[#334155]">
                    <p class="font-semibold text-[#0F172A]">Catatan Penilai HR:</p>
                    <p>
                        "Pemahaman teks bacaan dan penangkapan instruksi kerja kandidat sangat baik, runut, dan langsung mengarah ke inti pertanyaan. Menunjukkan daya nalar analisis situasi kerja yang kuat dan siap diterapkan dalam koordinasi tim teknis."
                    </p>
                </div>
            </x-card>

            <x-card title="Daftar Butir Soal Terjawab">
                <ul class="divide-y divide-[#E2E8F0] text-xs">
                    <li class="py-3 flex items-center justify-between">
                        <span class="font-medium text-[#0F172A]">Soal 1: Jadwal dan Syarat Pergantian Shift Gudang</span>
                        <x-badge variant="success" size="sm">Terverifikasi Sesuai</x-badge>
                    </li>
                    <li class="py-3 flex items-center justify-between">
                        <span class="font-medium text-[#0F172A]">Soal 2: Kebijakan Pengembalian Dana Perlengkapan</span>
                        <x-badge variant="success" size="sm">Terverifikasi Sesuai</x-badge>
                    </li>
                    <li class="py-3 flex items-center justify-between">
                        <span class="font-medium text-[#0F172A]">Soal 3: Spesifikasi Suhu Ruang Penyimpanan Sampel</span>
                        <x-badge variant="success" size="sm">Terverifikasi Sesuai</x-badge>
                    </li>
                    <li class="py-3 flex items-center justify-between">
                        <span class="font-medium text-[#0F172A]">Soal 4: Ketentuan Hak Akses Ruang Server Pusat</span>
                        <x-badge variant="success" size="sm">Terverifikasi Sesuai</x-badge>
                    </li>
                    <li class="py-3 flex items-center justify-between">
                        <span class="font-medium text-[#0F172A]">Soal 5: Instruksi Penanganan Kebocoran Bahan Kimia</span>
                        <x-badge variant="success" size="sm">Terverifikasi Sesuai</x-badge>
                    </li>
                </ul>
            </x-card>
        </div>

        <div class="md:col-span-4 space-y-6">
            <div class="p-6 rounded-2xl bg-white border border-[#E2E8F0] shadow-2xs space-y-4 text-xs">
                <h3 class="font-bold text-[#0F172A] border-b border-[#E2E8F0] pb-2">Status Seleksi Terkini</h3>
                <p class="text-[#64748B] leading-relaxed">
                    Hasil asesmen Anda telah dimasukkan ke dalam berkas seleksi lamaran posisi <strong class="text-[#0F172A]">{{ $job['judul'] }}</strong>.
                </p>
                <x-button href="{{ route('kandidat.lamaran.show', 'APP-101') }}" variant="primary" size="sm" class="w-full">
                    Lihat Progres Lamaran Saya →
                </x-button>
            </div>
        </div>
    </div>
</div>
@endsection
