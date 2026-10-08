@extends('layouts.hr')

@section('title', 'Smart Grading Test · HR Portal')
@section('page_title', 'Evaluasi / Smart Grading')
@section('header_title', 'Smart Grading Test & Bank Soal Esai')

@section('content')
<div class="space-y-8" x-data="{ activeTab: 'bank-soal' }">
    {{-- Header Metrics Bar --}}
    <div class="grid grid-cols-1 sm:grid-cols-4 gap-4">
        <div class="p-5 rounded-2xl bg-white border border-[#E2E8F0] shadow-2xs space-y-1">
            <span class="text-xs text-[#64748B]">Bank Soal Aktif</span>
            <p class="text-2xl font-black text-[#0F172A]">10 Soal</p>
            <p class="text-[11px] text-[#64748B]">3 Kategori Evaluasi</p>
        </div>
        <div class="p-5 rounded-2xl bg-white border border-[#E2E8F0] shadow-2xs space-y-1">
            <span class="text-xs text-[#64748B]">Paket Tes Tersedia</span>
            <p class="text-2xl font-black text-[#2563EB]">2 Paket</p>
            <p class="text-[11px] text-[#64748B]">Paket A & Paket B</p>
        </div>
        <div class="p-5 rounded-2xl bg-white border border-[#E2E8F0] shadow-2xs space-y-1">
            <span class="text-xs text-[#64748B]">Tes Menunggu Review HR</span>
            <p class="text-2xl font-black text-amber-600">4 Pengerjaan</p>
            <p class="text-[11px] text-amber-700">Perlu Verifikasi Rubrik</p>
        </div>
        <div class="p-5 rounded-2xl bg-white border border-[#E2E8F0] shadow-2xs space-y-1">
            <span class="text-xs text-[#64748B]">Hasil Terpublikasi</span>
            <p class="text-2xl font-black text-emerald-600">3 Selesai</p>
            <p class="text-[11px] text-emerald-700">Nilai Diteruskan ke Pelamar</p>
        </div>
    </div>

    {{-- Sub-tab Navigation --}}
    <div class="border-b border-[#E2E8F0]">
        <nav class="flex space-x-8 text-sm font-medium">
            <button
                type="button"
                @click="activeTab = 'bank-soal'"
                :class="activeTab === 'bank-soal' ? 'border-[#2563EB] text-[#2563EB] font-bold border-b-2' : 'border-transparent text-[#64748B] hover:text-[#0F172A]'"
                class="pb-3.5 pt-1 transition-smooth cursor-pointer"
            >
                Bank Soal Esai (10 Butir)
            </button>
            <button
                type="button"
                @click="activeTab = 'paket-tes'"
                :class="activeTab === 'paket-tes' ? 'border-[#2563EB] text-[#2563EB] font-bold border-b-2' : 'border-transparent text-[#64748B] hover:text-[#0F172A]'"
                class="pb-3.5 pt-1 transition-smooth cursor-pointer"
            >
                Paket Tes & Penugasan
            </button>
            <button
                type="button"
                @click="activeTab = 'penilaian'"
                :class="activeTab === 'penilaian' ? 'border-[#2563EB] text-[#2563EB] font-bold border-b-2' : 'border-transparent text-[#64748B] hover:text-[#0F172A]'"
                class="pb-3.5 pt-1 transition-smooth cursor-pointer"
            >
                Daftar Pengerjaan Kandidat
            </button>
        </nav>
    </div>

    {{-- TAB 1: BANK SOAL ESAI --}}
    <div x-show="activeTab === 'bank-soal'" class="space-y-6">
        <div class="flex items-center justify-between">
            <p class="text-xs text-[#64748B]">Soal jawaban singkat dengan bacaan skenario operasional dan rubrik penilaian terstandar</p>
            <x-button type="button" @click="$dispatch('show-toast', { message: 'Form tambah soal dibuka (Simulasi)', type: 'info' })" variant="primary" size="sm">
                + Tambah Soal Baru
            </x-button>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            {{-- Soal 1 --}}
            <div class="p-6 rounded-2xl bg-white border border-[#E2E8F0] shadow-2xs space-y-4">
                <div class="flex items-center justify-between pb-2 border-b border-[#E2E8F0]">
                    <span class="text-[11px] font-bold text-[#2563EB] uppercase">DEMO-INF-01 · Pemahaman Informasi</span>
                    <x-badge variant="neutral" size="sm">Maks: 10 Poin</x-badge>
                </div>
                <div>
                    <h3 class="text-sm font-bold text-[#0F172A]">Jadwal dan Syarat Pergantian Shift Gudang</h3>
                    <p class="text-xs text-[#64748B] mt-1 line-clamp-2">
                        "Pergantian shift kerja di gudang logistik berlangsung tepat pada pukul 07.00, 15.00, dan 23.00 WIB..."
                    </p>
                </div>
                <div class="p-3 rounded-xl bg-slate-50 border border-[#E2E8F0] text-xs space-y-1">
                    <strong class="text-[#0F172A] block">Pertanyaan:</strong>
                    <p class="text-[#475569]">Kapan batas waktu paling lambat staf harus memberitahukan keterlambatan agar tidak dialihkan ke tugas administratif?</p>
                </div>
                <div class="p-3 rounded-xl bg-blue-50 border border-blue-100 text-xs space-y-1">
                    <strong class="text-[#1E40AF] block">Kunci Acuan Jawaban (Internal HR):</strong>
                    <p class="text-[#1E3A8A]">Minimal 1 jam sebelum jam pergantian shift.</p>
                </div>
            </div>

            {{-- Soal 2 --}}
            <div class="p-6 rounded-2xl bg-white border border-[#E2E8F0] shadow-2xs space-y-4">
                <div class="flex items-center justify-between pb-2 border-b border-[#E2E8F0]">
                    <span class="text-[11px] font-bold text-[#2563EB] uppercase">DEMO-INF-02 · Pemahaman Informasi</span>
                    <x-badge variant="neutral" size="sm">Maks: 10 Poin</x-badge>
                </div>
                <div>
                    <h3 class="text-sm font-bold text-[#0F172A]">Kebijakan Pengembalian Dana Pembelian Perlengkapan</h3>
                    <p class="text-xs text-[#64748B] mt-1 line-clamp-2">
                        "Karyawan lapangan berhak mengajukan reimbursement untuk pembelian perlengkapan darurat maksimal Rp 300.000..."
                    </p>
                </div>
                <div class="p-3 rounded-xl bg-slate-50 border border-[#E2E8F0] text-xs space-y-1">
                    <strong class="text-[#0F172A] block">Pertanyaan:</strong>
                    <p class="text-[#475569]">Dokumen apa saja yang wajib dilampirkan agar pengajuan reimbursement tidak ditolak?</p>
                </div>
                <div class="p-3 rounded-xl bg-blue-50 border border-blue-100 text-xs space-y-1">
                    <strong class="text-[#1E40AF] block">Kunci Acuan Jawaban (Internal HR):</strong>
                    <p class="text-[#1E3A8A]">Struk resmi fisik asli dan foto barang yang dibeli.</p>
                </div>
            </div>
        </div>
    </div>

    {{-- TAB 2: PAKET TES & PENUGASAN --}}
    <div x-show="activeTab === 'paket-tes'" style="display: none;" class="space-y-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            {{-- Paket A --}}
            <div class="p-6 rounded-2xl bg-white border border-[#E2E8F0] shadow-2xs space-y-4">
                <div class="flex items-center justify-between pb-2 border-b border-[#E2E8F0]">
                    <span class="text-xs font-bold text-[#2563EB]">Paket Tes A</span>
                    <x-badge variant="success" size="sm" dot>Aktif</x-badge>
                </div>
                <h3 class="text-base font-bold text-[#0F172A]">Pemahaman Informasi & Instruksi Operasional</h3>
                <p class="text-xs text-[#64748B] leading-relaxed">
                    Evaluasi kemampuan memahami instruksi kerja, SOP logistik, dan batas toleransi operasional.
                </p>
                <div class="grid grid-cols-2 gap-2 text-xs text-[#475569] pt-2">
                    <div class="p-2.5 rounded-lg bg-slate-50 border border-slate-200">
                        <span>Durasi: <strong>25 Menit</strong></span>
                    </div>
                    <div class="p-2.5 rounded-lg bg-slate-50 border border-slate-200">
                        <span>Jumlah: <strong>5 Soal Esai</strong></span>
                    </div>
                </div>
                <div class="pt-3 border-t border-[#E2E8F0]">
                    <x-button type="button" @click="$dispatch('show-toast', { message: 'Paket A ditugaskan ke kandidat', type: 'success' })" variant="outline" size="sm" class="w-full">
                        Tugaskan ke Kandidat Lain
                    </x-button>
                </div>
            </div>

            {{-- Paket B --}}
            <div class="p-6 rounded-2xl bg-white border border-[#E2E8F0] shadow-2xs space-y-4">
                <div class="flex items-center justify-between pb-2 border-b border-[#E2E8F0]">
                    <span class="text-xs font-bold text-[#2563EB]">Paket Tes B</span>
                    <x-badge variant="success" size="sm" dot>Aktif</x-badge>
                </div>
                <h3 class="text-base font-bold text-[#0F172A]">Penalaran & Logika Analitis Kerja</h3>
                <p class="text-xs text-[#64748B] leading-relaxed">
                    Evaluasi daya nalar logis, kalkulasi parameter armada, dan pengambilan keputusan berdasarkan informasi tertulis.
                </p>
                <div class="grid grid-cols-2 gap-2 text-xs text-[#475569] pt-2">
                    <div class="p-2.5 rounded-lg bg-slate-50 border border-slate-200">
                        <span>Durasi: <strong>30 Menit</strong></span>
                    </div>
                    <div class="p-2.5 rounded-lg bg-slate-50 border border-slate-200">
                        <span>Jumlah: <strong>5 Soal Esai</strong></span>
                    </div>
                </div>
                <div class="pt-3 border-t border-[#E2E8F0]">
                    <x-button type="button" @click="$dispatch('show-toast', { message: 'Paket B ditugaskan ke kandidat', type: 'success' })" variant="outline" size="sm" class="w-full">
                        Tugaskan ke Kandidat Lain
                    </x-button>
                </div>
            </div>
        </div>
    </div>

    {{-- TAB 3: DAFTAR PENGERJAAN KANDIDAT --}}
    <div x-show="activeTab === 'penilaian'" style="display: none;" class="space-y-6">
        <div class="bg-white rounded-3xl border border-[#E2E8F0] shadow-2xs overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs border-collapse">
                    <thead>
                        <tr class="bg-slate-50 border-b border-[#E2E8F0] text-[#64748B] font-bold uppercase tracking-wider text-[11px]">
                            <th class="py-4 px-6">Kandidat</th>
                            <th class="py-4 px-6">Paket Tes</th>
                            <th class="py-4 px-6">Status Pengerjaan</th>
                            <th class="py-4 px-6">Nilai Smart Grading</th>
                            <th class="py-4 px-6 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#E2E8F0] text-[#0F172A]">
                        @foreach($candidates as $c)
                            <tr class="hover:bg-slate-50/70 transition-smooth">
                                <td class="py-4 px-6">
                                    <span class="font-bold text-sm text-[#0F172A] block">{{ $c['nama'] }}</span>
                                    <span class="text-[11px] text-[#64748B]">{{ $c['posisi_dilamar'] }}</span>
                                </td>
                                <td class="py-4 px-6 font-medium text-[#334155]">
                                    Paket A (Operasional)
                                </td>
                                <td class="py-4 px-6">
                                    @if($c['status_tes'] === 'Selesai Dinilai')
                                        <x-badge variant="success" size="sm" dot>Selesai Dinilai</x-badge>
                                    @elseif($c['status_tes'] === 'Menunggu Review HR')
                                        <x-badge variant="warning" size="sm" dot>Menunggu Review</x-badge>
                                    @else
                                        <x-badge variant="neutral" size="sm">{{ $c['status_tes'] }}</x-badge>
                                    @endif
                                </td>
                                <td class="py-4 px-6">
                                    @if($c['skor_tes'])
                                        <span class="text-sm font-black text-[#2563EB]">{{ $c['skor_tes'] }}</span>
                                        <span class="text-[10px] text-slate-400">/ 100</span>
                                    @else
                                        <span class="text-slate-400">Belum dinilai</span>
                                    @endif
                                </td>
                                <td class="py-4 px-6 text-right">
                                    <x-button href="{{ route('hr.smart_grading.penilaian', $c['id']) }}" variant="secondary" size="sm">
                                        Lembar Penilaian →
                                    </x-button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
