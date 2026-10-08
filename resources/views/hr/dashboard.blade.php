@extends('layouts.hr')

@section('title', 'Dashboard HR · MURIALO Enterprise')
@section('page_title', 'Ringkasan Eksekutif')
@section('header_title', 'Dashboard Rekrutmen Perusahaan')

@section('content')
<div class="space-y-8">
    {{-- Top Stats / KPI Cards (Konsisten Dataset) --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
        {{-- KPI 1: Lowongan Aktif --}}
        <div class="p-5 rounded-2xl bg-white border border-[#E2E8F0] shadow-2xs space-y-2">
            <span class="text-xs font-semibold text-[#64748B]">Lowongan Aktif</span>
            <div class="flex items-baseline justify-between">
                <span class="text-3xl font-extrabold text-[#0F172A]">{{ $kpis['lowongan_aktif'] }}</span>
                <x-badge variant="primary" size="sm">5 Terbuka</x-badge>
            </div>
            <p class="text-[11px] text-[#64748B]">1 draf dalam persiapan</p>
        </div>

        {{-- KPI 2: Total Lamaran --}}
        <div class="p-5 rounded-2xl bg-white border border-[#E2E8F0] shadow-2xs space-y-2">
            <span class="text-xs font-semibold text-[#64748B]">Total Lamaran Masuk</span>
            <div class="flex items-baseline justify-between">
                <span class="text-3xl font-extrabold text-[#0F172A]">{{ $kpis['total_lamaran'] }}</span>
                <span class="text-xs font-bold text-emerald-600">+24% bln ini</span>
            </div>
            <p class="text-[11px] text-[#64748B]">Seluruh posisi lowongan</p>
        </div>

        {{-- KPI 3: Dalam Proses --}}
        <div class="p-5 rounded-2xl bg-white border border-[#E2E8F0] shadow-2xs space-y-2">
            <span class="text-xs font-semibold text-[#64748B]">Kandidat Dalam Proses</span>
            <div class="flex items-baseline justify-between">
                <span class="text-3xl font-extrabold text-[#2563EB]">{{ $kpis['dalam_proses'] }}</span>
                <x-badge variant="neutral" size="sm">Pipeline</x-badge>
            </div>
            <p class="text-[11px] text-[#64748B]">Screening s/d Wawancara</p>
        </div>

        {{-- KPI 4: Menunggu Review --}}
        <div class="p-5 rounded-2xl bg-white border border-[#E2E8F0] shadow-2xs space-y-2">
            <span class="text-xs font-semibold text-[#64748B]">Tes Menunggu Review</span>
            <div class="flex items-baseline justify-between">
                <span class="text-3xl font-extrabold text-amber-600">{{ $kpis['menunggu_review_tes'] }}</span>
                <x-badge variant="warning" size="sm" dot>Perlu Review</x-badge>
            </div>
            <p class="text-[11px] text-[#64748B]">Paket A & Paket B</p>
        </div>

        {{-- KPI 5: Diterima --}}
        <div class="p-5 rounded-2xl bg-white border border-[#E2E8F0] shadow-2xs space-y-2">
            <span class="text-xs font-semibold text-[#64748B]">Kandidat Diterima</span>
            <div class="flex items-baseline justify-between">
                <span class="text-3xl font-extrabold text-emerald-600">{{ $kpis['kandidat_diterima'] }}</span>
                <x-badge variant="success" size="sm">Hired</x-badge>
            </div>
            <p class="text-[11px] text-[#64748B]">Onboarding terjadwal</p>
        </div>
    </div>

    {{-- Main Row: Application Trend Chart & To-do List --}}
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        {{-- Chart Container (8 cols) --}}
        <div class="lg:col-span-8 bg-white rounded-3xl border border-[#E2E8F0] p-6 shadow-2xs space-y-4">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 pb-4 border-b border-[#E2E8F0]">
                <div>
                    <h2 class="text-base font-bold text-[#0F172A]">Tren Masuk Lamaran & Proyeksi 4 Minggu</h2>
                    <p class="text-xs text-[#64748B]">12 Minggu data historis aktual + 4 Minggu estimasi Time Series Forecasting (AI)</p>
                </div>
                <div class="flex items-center gap-3 text-xs">
                    <span class="flex items-center gap-1.5"><span class="w-3 h-0.5 bg-[#2563EB]"></span> Historis</span>
                    <span class="flex items-center gap-1.5"><span class="w-3 h-0.5 bg-purple-500 border-dashed"></span> Proyeksi AI</span>
                </div>
            </div>

            {{-- Canvas for Chart.js --}}
            <div class="h-64 sm:h-72 w-full relative">
                <canvas id="recruitmentTrendChart"></canvas>
            </div>
        </div>

        {{-- To-Do List HR (4 cols) --}}
        <div class="lg:col-span-4 bg-white rounded-3xl border border-[#E2E8F0] p-6 shadow-2xs space-y-4 flex flex-col justify-between">
            <div>
                <h3 class="text-sm font-bold text-[#0F172A] pb-3 border-b border-[#E2E8F0] flex items-center justify-between">
                    <span>Tugas Menunggu Tindak Lanjut</span>
                    <x-badge variant="danger" size="sm">4 Tertunda</x-badge>
                </h3>
                <div class="space-y-3 pt-3">
                    <div class="p-3 rounded-xl bg-amber-50/80 border border-amber-200 text-xs space-y-1">
                        <div class="flex justify-between font-bold text-[#0F172A]">
                            <span>Review Tes Esai Budi Santoso</span>
                            <span class="text-amber-700">Skor: 92.0</span>
                        </div>
                        <p class="text-[11px] text-[#64748B]">Jawaban Paket A siap diverifikasi rubrik.</p>
                        <a href="{{ route('hr.smart_grading.penilaian', 101) }}" class="text-[11px] font-bold text-[#2563EB] hover:underline block pt-1">
                            Buka Lembar Penilaian →
                        </a>
                    </div>

                    <div class="p-3 rounded-xl bg-slate-50 border border-[#E2E8F0] text-xs space-y-1">
                        <div class="flex justify-between font-bold text-[#0F172A]">
                            <span>Temuan Anomali Waktu Tes</span>
                            <span class="text-red-600 font-bold">Risiko 74%</span>
                        </div>
                        <p class="text-[11px] text-[#64748B]">Pola submit abnormal pada kandidat Arif W.</p>
                        <a href="{{ route('hr.anomali') }}" class="text-[11px] font-bold text-[#2563EB] hover:underline block pt-1">
                            Tinjau Pola Anomali →
                        </a>
                    </div>

                    <div class="p-3 rounded-xl bg-slate-50 border border-[#E2E8F0] text-xs space-y-1">
                        <div class="flex justify-between font-bold text-[#0F172A]">
                            <span>Rekomendasi Kandidat Baru</span>
                            <span class="text-blue-600 font-bold">CBF</span>
                        </div>
                        <p class="text-[11px] text-[#64748B]">4 kandidat cocok untuk Senior Backend.</p>
                        <a href="{{ route('hr.rekomendasi') }}" class="text-[11px] font-bold text-[#2563EB] hover:underline block pt-1">
                            Lihat Rekomendasi →
                        </a>
                    </div>
                </div>
            </div>

            <div class="pt-3 border-t border-[#E2E8F0]">
                <a href="{{ route('hr.pipeline') }}" class="text-xs font-bold text-[#2563EB] hover:underline flex items-center justify-between">
                    <span>Lihat Seluruh Pipeline Seleksi</span>
                    <span>→</span>
                </a>
            </div>
        </div>
    </div>

    {{-- Natural Language Generation (NLG) Insight Bar --}}
    <div class="p-5 rounded-2xl bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-200 flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
        <div class="flex items-start gap-3">
            <span class="w-8 h-8 rounded-xl bg-[#2563EB] text-white flex items-center justify-center font-bold text-sm shrink-0">✦</span>
            <div class="space-y-0.5">
                <div class="flex items-center gap-2">
                    <h4 class="text-xs font-bold text-[#1E40AF] uppercase tracking-wider">Ringkasan Analitik NLG Otomatis:</h4>
                    <span class="text-[10px] text-blue-600">Terbit: {{ $nlg['generated_at'] }}</span>
                </div>
                <p class="text-xs font-semibold text-[#0F172A]">{{ $nlg['headline'] }}</p>
                <p class="text-xs text-[#334155] leading-relaxed max-w-3xl">
                    {{ $nlg['insights'][0] }}
                </p>
            </div>
        </div>
        <x-button href="{{ route('hr.analitik') }}" variant="secondary" size="sm" class="shrink-0 bg-white">
            Detail Analitik Lengkap
        </x-button>
    </div>

    {{-- Pelamar Terbaru Table --}}
    <div class="bg-white rounded-3xl border border-[#E2E8F0] shadow-2xs overflow-hidden space-y-4 p-6">
        <div class="flex items-center justify-between pb-2">
            <div>
                <h3 class="text-base font-bold text-[#0F172A]">Pelamar Masuk Terbaru</h3>
                <p class="text-xs text-[#64748B]">Kandidat teratas dengan kalkulasi kesesuaian semantik S-BERT</p>
            </div>
            <x-button href="{{ route('hr.pelamar.index') }}" variant="secondary" size="sm">
                Lihat Semua Pelamar ({{ $kpis['total_lamaran'] }})
            </x-button>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-y border-[#E2E8F0] text-[#64748B] font-bold uppercase tracking-wider text-[11px]">
                        <th class="py-3 px-4">Kandidat</th>
                        <th class="py-3 px-4">Posisi Dilamar</th>
                        <th class="py-3 px-4">Skor Kesesuaian</th>
                        <th class="py-3 px-4">Tahapan Seleksi</th>
                        <th class="py-3 px-4">Status Tes</th>
                        <th class="py-3 px-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#E2E8F0]">
                    @foreach($candidates as $c)
                        <tr class="hover:bg-slate-50/60 transition-smooth">
                            <td class="py-3.5 px-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-lg bg-slate-100 text-[#0F172A] font-bold text-xs flex items-center justify-center">
                                        {{ substr($c['nama'], 0, 1) }}
                                    </div>
                                    <div>
                                        <a href="{{ route('hr.pelamar.show', $c['id']) }}" class="font-bold text-xs text-[#0F172A] hover:text-[#2563EB]">{{ $c['nama'] }}</a>
                                        <p class="text-[11px] text-[#64748B]">{{ $c['email'] }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="py-3.5 px-4 text-[#334155] font-medium">
                                {{ $c['posisi_dilamar'] }}
                            </td>
                            <td class="py-3.5 px-4">
                                <div class="flex items-center gap-2">
                                    <span class="font-extrabold text-xs text-[#2563EB]">{{ $c['skor_matching'] }}%</span>
                                    <div class="w-16 bg-slate-200 h-1.5 rounded-full overflow-hidden">
                                        <div class="bg-[#2563EB] h-full rounded-full" style="width: {{ $c['skor_matching'] }}%"></div>
                                    </div>
                                </div>
                            </td>
                            <td class="py-3.5 px-4">
                                <x-badge variant="primary" size="sm">{{ $c['status_pipeline'] }}</x-badge>
                            </td>
                            <td class="py-3.5 px-4">
                                @if($c['status_tes'] === 'Selesai Dinilai')
                                    <span class="text-emerald-600 font-semibold text-xs">✓ Nilai: {{ $c['skor_tes'] }}</span>
                                @elseif($c['status_tes'] === 'Menunggu Review HR')
                                    <x-badge variant="warning" size="sm">Menunggu Review</x-badge>
                                @else
                                    <span class="text-[#64748B] text-xs">{{ $c['status_tes'] }}</span>
                                @endif
                            </td>
                            <td class="py-3.5 px-4 text-right">
                                <a href="{{ route('hr.pelamar.show', $c['id']) }}" class="text-xs font-semibold text-[#2563EB] hover:underline">
                                    Detail Pelamar →
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('recruitmentTrendChart');
    if (!ctx) return;

    const labels = @json($timeSeries['labels']);
    const historical = @json($timeSeries['historical']);
    const projected = @json($timeSeries['projected']);

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [
                {
                    label: 'Lamaran Historis Aktual',
                    data: historical,
                    borderColor: '#2563EB',
                    backgroundColor: 'rgba(37, 99, 235, 0.08)',
                    borderWidth: 2.5,
                    fill: true,
                    tension: 0.3,
                    pointRadius: 3,
                    pointBackgroundColor: '#2563EB',
                },
                {
                    label: 'Proyeksi Forecasting Time Series',
                    data: projected,
                    borderColor: '#9333EA',
                    backgroundColor: 'transparent',
                    borderWidth: 2.5,
                    borderDash: [5, 5],
                    tension: 0.3,
                    pointRadius: 4,
                    pointBackgroundColor: '#9333EA',
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return context.dataset.label + ': ' + context.parsed.y + ' Lamaran';
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        color: '#F1F5F9'
                    },
                    ticks: {
                        font: { size: 10 }
                    }
                },
                x: {
                    grid: {
                        display: false
                    },
                    ticks: {
                        font: { size: 9 },
                        maxRotation: 45
                    }
                }
            }
        }
    });
});
</script>
@endpush
