@extends('layouts.hr')

@section('title', 'Automated Skill Matching · HR Portal')
@section('page_title', 'Evaluasi / Skill Matching')
@section('header_title', 'Automated Semantic Skill Matching (Sentence-BERT)')

@section('content')
<div class="space-y-6" x-data="{ compareModal: false, candidate1: null, candidate2: null }">
    {{-- Header & Job Selector --}}
    <div class="p-6 rounded-3xl bg-white border border-[#E2E8F0] shadow-2xs space-y-4">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-4 border-b border-[#E2E8F0]">
            <div>
                <h2 class="text-base font-bold text-[#0F172A]">Pilih Posisi Lowongan untuk Evaluasi</h2>
                <p class="text-xs text-[#64748B]">Model S-BERT menghitung kemiripan kosinus (Cosine Similarity) antara kualifikasi lowongan dan bukti di CV</p>
            </div>
            <x-badge variant="primary" size="md">Sentence-BERT Core</x-badge>
        </div>

        <form action="{{ route('hr.skill_matching') }}" method="GET" class="flex flex-col sm:flex-row items-center gap-3">
            <div class="flex-1 w-full">
                <x-select name="lowongan_id">
                    @foreach($lowonganList as $job)
                        <option value="{{ $job['id'] }}" {{ $selectedJob['id'] == $job['id'] ? 'selected' : '' }}>
                            {{ $job['judul'] }} ({{ $job['departemen'] }})
                        </option>
                    @endforeach
                </x-select>
            </div>
            <x-button type="submit" variant="primary" size="md" class="w-full sm:w-auto">
                Tampilkan Kesesuaian
            </x-button>
        </form>
    </div>

    {{-- Selected Job Requirements Summary --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <x-card title="Kualifikasi Wajib Posisi Ini">
            <div class="flex flex-wrap gap-2">
                @foreach($selectedJob['kualifikasi_wajib'] as $kw)
                    <span class="px-3 py-1.5 rounded-lg text-xs font-semibold bg-emerald-50 text-emerald-800 border border-emerald-200 flex items-center gap-1.5">
                        <span class="text-emerald-600 font-bold">✓</span>
                        <span>{{ $kw }}</span>
                    </span>
                @endforeach
            </div>
        </x-card>

        <x-card title="Kualifikasi Tambahan (Opsional)">
            <div class="flex flex-wrap gap-2">
                @foreach($selectedJob['kualifikasi_opsional'] as $ko)
                    <span class="px-3 py-1.5 rounded-lg text-xs font-medium bg-blue-50 text-blue-800 border border-blue-200 flex items-center gap-1.5">
                        <span class="text-blue-500 font-bold">+</span>
                        <span>{{ $ko }}</span>
                    </span>
                @endforeach
            </div>
        </x-card>
    </div>

    {{-- Applicant Semantic Matching Ranked Table --}}
    <div class="bg-white rounded-3xl border border-[#E2E8F0] shadow-2xs overflow-hidden space-y-4 p-6">
        <div class="flex items-center justify-between pb-2 border-b border-[#E2E8F0]">
            <div>
                <h3 class="text-base font-bold text-[#0F172A]">Peringkat Kesesuaian Kandidat Terdaftar</h3>
                <p class="text-xs text-[#64748B]">Diurutkan berdasarkan skor kesesuaian semantik tertinggi</p>
            </div>
            <x-button type="button" @click="compareModal = true" variant="secondary" size="sm">
                Bandingkan 2 Kandidat (Side-by-side)
            </x-button>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-y border-[#E2E8F0] text-[#64748B] font-bold uppercase tracking-wider text-[11px]">
                        <th class="py-3.5 px-4">Peringkat & Kandidat</th>
                        <th class="py-3.5 px-4">Skor Kesesuaian</th>
                        <th class="py-3.5 px-4">Skill Wajib Ditemukan</th>
                        <th class="py-3.5 px-4">Skill Belum Ditemukan</th>
                        <th class="py-3.5 px-4">Bukti Konteks Dokumen</th>
                        <th class="py-3.5 px-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#E2E8F0]">
                    @php $rank = 1; @endphp
                    @forelse($candidates as $c)
                        <tr class="hover:bg-slate-50/70 transition-smooth">
                            <td class="py-4 px-4">
                                <div class="flex items-center gap-3">
                                    <span class="w-6 h-6 rounded-full bg-slate-100 text-[#0F172A] font-extrabold flex items-center justify-center text-xs">
                                        #{{ $rank++ }}
                                    </span>
                                    <div>
                                        <a href="{{ route('hr.pelamar.show', $c['id']) }}" class="font-bold text-sm text-[#0F172A] hover:text-[#2563EB]">
                                            {{ $c['nama'] }}
                                        </a>
                                        <span class="text-[11px] text-[#64748B] block">{{ $c['pengalaman_tahun'] }} Tahun Pengalaman</span>
                                    </div>
                                </div>
                            </td>
                            <td class="py-4 px-4">
                                <div class="flex items-center gap-2">
                                    <span class="text-base font-black text-[#2563EB]">{{ $c['skor_matching'] }}%</span>
                                    <div class="w-16 bg-slate-200 h-2 rounded-full overflow-hidden">
                                        <div class="bg-[#2563EB] h-full rounded-full" style="width: {{ $c['skor_matching'] }}%"></div>
                                    </div>
                                </div>
                                <span class="text-[10px] text-slate-400">Tingkat Kesesuaian</span>
                            </td>
                            <td class="py-4 px-4">
                                <div class="flex flex-wrap gap-1">
                                    @foreach(array_slice($c['skills'], 0, 4) as $sk)
                                        <span class="px-2 py-0.5 rounded text-[10px] font-semibold bg-emerald-100 text-emerald-800">
                                            {{ $sk }} ✓
                                        </span>
                                    @endforeach
                                </div>
                            </td>
                            <td class="py-4 px-4">
                                @if($c['skor_matching'] < 85)
                                    <span class="px-2 py-0.5 rounded text-[10px] bg-slate-100 text-slate-600 font-medium">PyTorch / NLP</span>
                                @else
                                    <span class="text-emerald-600 text-[11px] font-semibold">Semua Wajib Terpenuhi</span>
                                @endif
                            </td>
                            <td class="py-4 px-4 max-w-xs text-[#475569] truncate" title="Ditemukan pada riwayat proyek: FastAPI microservice architecture">
                                "FastAPI microservice, PostgreSQL, Docker cluster..."
                            </td>
                            <td class="py-4 px-4 text-right">
                                <x-button href="{{ route('hr.pelamar.show', $c['id']) }}" variant="outline" size="sm">
                                    Lihat Profil
                                </x-button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-8 text-center text-slate-400">
                                Tidak ada data pelamar pada posisi ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Side-by-Side Comparison Modal --}}
    <x-modal name="compare-modal" title="Perbandingan Kandidat Berdampingan (Side-by-Side)" maxWidth="3xl">
        <div class="space-y-6">
            <div class="grid grid-cols-2 gap-6 pb-6 border-b border-[#E2E8F0]">
                {{-- Candidate 1 --}}
                <div class="p-4 rounded-2xl bg-blue-50/70 border border-blue-200 space-y-3">
                    <x-badge variant="primary" size="sm">Kandidat A: Budi Santoso</x-badge>
                    <p class="text-2xl font-black text-[#2563EB]">92% Kesesuaian</p>
                    <ul class="text-xs space-y-1 text-slate-700">
                        <li>• 5 Tahun Pengalaman Kerja</li>
                        <li>• Skill Terpenuhi: 6 dari 6 Wajib</li>
                        <li>• Nilai Smart Grading: 92.0 / 100</li>
                        <li>• Rekomendasi: Sangat Cocok</li>
                    </ul>
                </div>

                {{-- Candidate 2 --}}
                <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200 space-y-3">
                    <x-badge variant="neutral" size="sm">Kandidat B: Hendra Setiawan</x-badge>
                    <p class="text-2xl font-black text-[#0F172A]">89% Kesesuaian</p>
                    <ul class="text-xs space-y-1 text-slate-700">
                        <li>• 5 Tahun Pengalaman Kerja</li>
                        <li>• Skill Terpenuhi: 5 dari 6 Wajib</li>
                        <li>• Nilai Smart Grading: 85.0 / 100</li>
                        <li>• Rekomendasi: Direkomendasikan</li>
                    </ul>
                </div>
            </div>

            <div class="text-right">
                <x-button type="button" @click="$dispatch('close-modal', 'compare-modal')" variant="secondary" size="md">
                    Tutup Perbandingan
                </x-button>
            </div>
        </div>
    </x-modal>
</div>
@endsection
