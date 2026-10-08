@extends('layouts.public')

@section('title', $job['judul'] . ' · Karier MURIALO')

@section('content')
<div class="py-10 bg-[#F8FAFC]" x-data="{ applyModal: false, applySuccess: false }">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
        
        {{-- Breadcrumb Navigation --}}
        <nav class="flex items-center gap-2 text-xs text-[#64748B]">
            <a href="{{ route('karir.index') }}" class="hover:text-[#2563EB]">Karier</a>
            <span>/</span>
            <span class="text-slate-400">{{ $job['departemen'] }}</span>
            <span>/</span>
            <span class="text-[#0F172A] font-semibold">{{ $job['judul'] }}</span>
        </nav>

        {{-- Job Hero Header Card --}}
        <div class="bg-white rounded-3xl border border-[#E2E8F0] p-6 sm:p-8 shadow-2xs space-y-6">
            <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6 pb-6 border-b border-[#E2E8F0]">
                <div class="space-y-3">
                    <div class="flex items-center gap-2.5">
                        <x-badge variant="primary" size="md">{{ $job['departemen'] }}</x-badge>
                        <x-badge variant="success" size="sm" dot>Buka untuk Pelamar</x-badge>
                    </div>
                    <h1 class="text-2xl sm:text-3xl font-extrabold text-[#0F172A] tracking-tight">
                        {{ $job['judul'] }}
                    </h1>
                    <div class="flex flex-wrap items-center gap-4 text-xs text-[#64748B]">
                        <span>🏢 PT Muria Logika Nusantara</span>
                        <span>📍 {{ $job['lokasi'] }}</span>
                        <span>⏱ {{ $job['tipe_pekerjaan'] }}</span>
                        <span>👥 Kuota: {{ $job['kuota'] }} orang</span>
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row lg:flex-col items-start lg:items-end justify-between gap-4">
                    <div class="text-left lg:text-right">
                        <span class="text-xs text-[#64748B]">Rentang Gaji Ditawarkan</span>
                        <p class="text-xl sm:text-2xl font-extrabold text-[#0F172A]">
                            Rp {{ number_format($job['gaji_min']/1000000, 0) }} - {{ number_format($job['gaji_max']/1000000, 0) }} <span class="text-sm font-semibold text-[#64748B]">Juta / bln</span>
                        </p>
                    </div>
                    <x-button type="button" @click="applyModal = true" variant="primary" size="lg" class="w-full sm:w-auto shadow-md">
                        Lamar Posisi Ini
                    </x-button>
                </div>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 text-xs text-[#64748B]">
                <div>
                    <span class="block text-slate-400">Tanggal Publikasi</span>
                    <strong class="text-[#0F172A]">{{ date('d M Y', strtotime($job['created_at'])) }}</strong>
                </div>
                <div>
                    <span class="block text-slate-400">Batas Pengiriman</span>
                    <strong class="text-[#0F172A]">{{ date('d M Y', strtotime($job['deadline'])) }}</strong>
                </div>
                <div>
                    <span class="block text-slate-400">Total Pelamar Terdata</span>
                    <strong class="text-[#0F172A]">{{ $job['jumlah_pelamar'] }} pelamar</strong>
                </div>
                <div>
                    <span class="block text-slate-400">Evaluasi Cerdas</span>
                    <strong class="text-[#2563EB]">S-BERT + Smart Grading</strong>
                </div>
            </div>
        </div>

        {{-- Content Grid: Job Details vs Company Sidebar --}}
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            
            {{-- Main Details --}}
            <div class="lg:col-span-8 space-y-8">
                {{-- Deskripsi Pekerjaan --}}
                <x-card title="Deskripsi Pekerjaan">
                    <p class="text-sm text-[#334155] leading-relaxed">
                        {{ $job['deskripsi'] }}
                    </p>
                </x-card>

                {{-- Kualifikasi Wajib --}}
                <x-card title="Kualifikasi & Keahlian Wajib">
                    <ul class="space-y-2.5 text-sm text-[#334155]">
                        @foreach($job['kualifikasi_wajib'] as $kw)
                            <li class="flex items-start gap-2.5">
                                <span class="w-5 h-5 rounded-full bg-emerald-100 text-emerald-700 flex items-center justify-center shrink-0 text-xs font-bold mt-0.5">✓</span>
                                <span>Menguasai dan berpengalaman dengan <strong class="text-[#0F172A]">{{ $kw }}</strong>.</span>
                            </li>
                        @endforeach
                    </ul>
                </x-card>

                {{-- Kualifikasi Opsional --}}
                <x-card title="Nilai Tambah (Kualifikasi Opsional)">
                    <ul class="space-y-2.5 text-sm text-[#334155]">
                        @foreach($job['kualifikasi_opsional'] as $ko)
                            <li class="flex items-start gap-2.5">
                                <span class="w-5 h-5 rounded-full bg-blue-100 text-[#2563EB] flex items-center justify-center shrink-0 text-xs font-bold mt-0.5">+</span>
                                <span>Pemahaman atau pengalaman proyek dengan <strong>{{ $ko }}</strong>.</span>
                            </li>
                        @endforeach
                    </ul>
                </x-card>

                {{-- Benefit & Fasilitas --}}
                <x-card title="Benefit & Fasilitas Kerja">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        @foreach($job['benefit'] as $b)
                            <div class="p-3 rounded-xl bg-slate-50 border border-[#E2E8F0] flex items-center gap-2.5 text-xs font-semibold text-[#0F172A]">
                                <span class="text-blue-600">✦</span>
                                <span>{{ $b }}</span>
                            </div>
                        @endforeach
                    </div>
                </x-card>
            </div>

            {{-- Sidebar Info --}}
            <div class="lg:col-span-4 space-y-6">
                {{-- Company Info Card --}}
                <div class="bg-white rounded-2xl border border-[#E2E8F0] p-6 shadow-2xs space-y-4">
                    <h3 class="text-sm font-bold text-[#0F172A] pb-3 border-b border-[#E2E8F0]">Tentang Perusahaan</h3>
                    <div>
                        <h4 class="text-sm font-bold text-[#0F172A]">{{ $company['name'] }}</h4>
                        <p class="text-xs text-[#64748B] mt-0.5">{{ $company['tagline'] }}</p>
                    </div>
                    <p class="text-xs text-[#64748B] leading-relaxed">
                        {{ $company['description'] }}
                    </p>
                    <div class="pt-2 border-t border-[#E2E8F0] text-xs space-y-2 text-[#64748B]">
                        <p>📍 {{ $company['location'] }}</p>
                        <p>🌐 <a href="{{ $company['website'] }}" class="text-[#2563EB] hover:underline" target="_blank">{{ $company['website'] }}</a></p>
                        <p>✉️ {{ $company['email'] }}</p>
                    </div>
                </div>

                {{-- Alur Seleksi Cepat --}}
                <div class="bg-[#EFF6FF] rounded-2xl border border-[#BFDBFE] p-6 space-y-3">
                    <h3 class="text-xs font-bold text-[#1E40AF] uppercase tracking-wider">Tahapan Rekrutmen</h3>
                    <ol class="space-y-2 text-xs text-[#1E3A8A]">
                        <li>1. Pengiriman CV & Portofolio</li>
                        <li>2. Seleksi Semantik & Review HR</li>
                        <li>3. Pengerjaan Tes Esai Operasional</li>
                        <li>4. Wawancara Pengguna & Penawaran</li>
                    </ol>
                    <div class="pt-2">
                        <x-button type="button" @click="applyModal = true" variant="primary" size="md" class="w-full">
                            Kirim Lamaran Sekarang
                        </x-button>
                    </div>
                </div>
            </div>

        </div>

    </div>

    {{-- Interactive Apply Modal --}}
    <div
        x-show="applyModal"
        x-transition
        style="display: none;"
        class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center p-4 bg-slate-900/50 backdrop-blur-xs"
    >
        <div class="bg-white rounded-3xl border border-[#E2E8F0] shadow-2xl max-w-lg w-full p-6 sm:p-8 space-y-5" @click.away="applyModal = false">
            <div class="flex items-center justify-between pb-3 border-b border-[#E2E8F0]">
                <div>
                    <h3 class="text-base font-bold text-[#0F172A]">Formulir Lamaran Pekerjaan</h3>
                    <p class="text-xs text-[#64748B]">{{ $job['judul'] }}</p>
                </div>
                <button type="button" @click="applyModal = false" class="text-slate-400 hover:text-slate-600">✕</button>
            </div>

            <div x-show="!applySuccess" class="space-y-4">
                <x-input label="Nama Lengkap" name="nama" value="Budi Santoso" required />
                <x-input label="Alamat Email" name="email" type="email" value="budi.santoso@email.com" required />
                <x-input label="Nomor Telepon / WhatsApp" name="telepon" value="+62 812-3456-7890" required />
                
                <div class="space-y-1.5">
                    <label class="block text-xs font-semibold text-[#0F172A]">Lampirkan Berkas CV (PDF)</label>
                    <div class="border-2 border-dashed border-[#CBD5E1] rounded-xl p-4 text-center bg-slate-50 hover:bg-slate-100 transition-smooth cursor-pointer">
                        <svg class="w-8 h-8 text-slate-400 mx-auto mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/></svg>
                        <p class="text-xs font-semibold text-[#0F172A]">CV_Budi_Santoso_Backend.pdf</p>
                        <p class="text-[11px] text-[#64748B]">Klik untuk mengganti berkas (Maks 5 MB)</p>
                    </div>
                </div>

                <x-textarea label="Pesan Pengantar Singkat (Opsional)" name="pesan" rows="2" placeholder="Tuliskan motivasi atau keunggulan singkat Anda..." />

                <div class="pt-3 flex items-center justify-end gap-3 border-t border-[#E2E8F0]">
                    <x-button type="button" @click="applyModal = false" variant="ghost" size="md">
                        Batal
                    </x-button>
                    <x-button type="button" @click="applySuccess = true; $dispatch('show-toast', { message: 'Lamaran Anda berhasil dikirim! Menunggu pemrosesan CV parser.', type: 'success' })" variant="primary" size="md">
                        Kirim Lamaran (Demo)
                    </x-button>
                </div>
            </div>

            <div x-show="applySuccess" class="text-center py-6 space-y-4" style="display: none;">
                <div class="w-14 h-14 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center mx-auto text-2xl font-bold">
                    ✓
                </div>
                <h4 class="text-lg font-bold text-[#0F172A]">Lamaran Berhasil Terkirim!</h4>
                <p class="text-xs text-[#64748B] max-w-sm mx-auto leading-relaxed">
                    Berkas CV Anda sedang diproses oleh Resume Parser AI untuk diekstraksi keahliannya. Anda dapat memantau status lamaran melalui portal kandidat.
                </p>
                <div class="pt-2 flex justify-center gap-3">
                    <x-button href="{{ route('kandidat.lamaran.index') }}" variant="primary" size="md">
                        Lihat Lamaran Saya
                    </x-button>
                    <x-button type="button" @click="applyModal = false; applySuccess = false" variant="secondary" size="md">
                        Tutup
                    </x-button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
