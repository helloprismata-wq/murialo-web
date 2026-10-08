@extends('layouts.hr', [
    'title' => 'Analitik Rekrutmen & Proyeksi',
    'subtitle' => 'Time Series Forecasting, Segmentasi K-Means, dan Ringkasan NLG Otomatis'
])

@section('content')
<div class="space-y-6" x-data="{
    periode: '12_minggu',
    selectedLowongan: 'semua',
    isRefreshing: false,
    refreshDemo() {
        this.isRefreshing = true;
        setTimeout(() => this.isRefreshing = false, 600);
    }
}">

    <!-- Top Filter Bar -->
    <div class="bg-white p-4 rounded-xl border border-slate-200 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div class="flex flex-wrap items-center gap-3">
            <div class="flex items-center gap-2">
                <span class="text-xs font-semibold text-slate-500">Rentang Waktu:</span>
                <select x-model="periode" @change="refreshDemo()" class="text-xs border border-slate-300 rounded-lg px-3 py-1.5 bg-white text-slate-700 focus:outline-none focus:ring-1 focus:ring-blue-500">
                    <option value="12_minggu">12 Minggu Terakhir + Proyeksi 4 Minggu</option>
                    <option value="6_bulan">Semester 1 (Jan - Jun)</option>
                    <option value="1_tahun">Tahun 2026 Berjalan</option>
                </select>
            </div>
            <div class="flex items-center gap-2">
                <span class="text-xs font-semibold text-slate-500">Filter Lowongan:</span>
                <select x-model="selectedLowongan" @change="refreshDemo()" class="text-xs border border-slate-300 rounded-lg px-3 py-1.5 bg-white text-slate-700 focus:outline-none focus:ring-1 focus:ring-blue-500">
                    <option value="semua">Semua Posisi Rekrutmen</option>
                    <option value="fullstack">Senior Fullstack Developer</option>
                    <option value="uiux">UI/UX Product Designer</option>
                    <option value="ml">Machine Learning Engineer</option>
                </select>
            </div>
        </div>

        <div class="flex items-center gap-2">
            <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-medium bg-blue-50 text-blue-700 border border-blue-200">
                Model: ARIMA + K-Means (Demo)
            </span>
            <button @click="refreshDemo()" class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium text-slate-700 bg-slate-100 hover:bg-slate-200 rounded-lg transition">
                <svg class="w-3.5 h-3.5" :class="{'animate-spin': isRefreshing}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                </svg>
                <span>Perbarui Data</span>
            </button>
        </div>
    </div>

    <!-- Modul Ringkasan NLG (Natural Language Generation) -->
    <div class="bg-gradient-to-r from-blue-900 to-indigo-900 text-white rounded-2xl p-6 shadow-md relative overflow-hidden">
        <div class="absolute -right-10 -bottom-10 w-48 h-48 bg-blue-500/20 rounded-full blur-2xl pointer-events-none"></div>
        <div class="flex items-start gap-4">
            <div class="w-10 h-10 rounded-xl bg-white/10 flex items-center justify-center shrink-0 border border-white/20">
                <svg class="w-6 h-6 text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                </svg>
            </div>
            <div class="space-y-2">
                <div class="flex items-center gap-2">
                    <span class="text-xs font-semibold uppercase tracking-wider text-blue-300">Natural Language Generation</span>
                    <span class="text-[10px] bg-white/20 px-2 py-0.5 rounded text-white font-mono">AI Summary Generator</span>
                </div>
                <h3 class="text-lg font-bold">Ringkasan Eksekutif Analitik & Rekomendasi Alokasi</h3>
                <p class="text-sm text-blue-100 leading-relaxed max-w-4xl">
                    {{ $analitikData['nlg_summary'] }}
                </p>
            </div>
        </div>
    </div>

    <!-- Row 1: Time Series Forecasting -->
    <div class="bg-white rounded-xl border border-slate-200 p-6 shadow-sm space-y-4">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-b border-slate-100 pb-4">
            <div>
                <h3 class="text-base font-bold text-slate-900">Tren Volume Pelamar & Proyeksi 4 Minggu ke Depan</h3>
                <p class="text-xs text-slate-500 mt-0.5">Membandingkan realisasi pelamar aktual dengan hasil peramalan deret waktu (Forecasting Model)</p>
            </div>
            <div class="flex items-center gap-4 text-xs">
                <div class="flex items-center gap-1.5">
                    <span class="w-3 h-3 rounded-full bg-blue-600 inline-block"></span>
                    <span class="text-slate-600 font-medium">Data Historis Aktual (W1-W12)</span>
                </div>
                <div class="flex items-center gap-1.5">
                    <span class="w-3 h-3 rounded-full bg-amber-500 inline-block"></span>
                    <span class="text-slate-600 font-medium">Proyeksi Peramalan (W13-W16)</span>
                </div>
            </div>
        </div>

        <!-- Canvas Chart -->
        <div class="h-72 w-full">
            <canvas id="forecastingChart"></canvas>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 pt-4 border-t border-slate-100 text-xs">
            <div class="bg-slate-50 p-3 rounded-lg border border-slate-200">
                <span class="text-slate-500">Rata-rata Lamaran Mingguan:</span>
                <div class="text-base font-bold text-slate-900 mt-0.5">17.2 Pelamar/Minggu</div>
            </div>
            <div class="bg-slate-50 p-3 rounded-lg border border-slate-200">
                <span class="text-slate-500">Puncak Lamaran Terbesar:</span>
                <div class="text-base font-bold text-blue-600 mt-0.5">Minggu ke-10 (28 Pelamar)</div>
            </div>
            <div class="bg-slate-50 p-3 rounded-lg border border-slate-200">
                <span class="text-slate-500">Estimasi Kapasitas Review W13-W16:</span>
                <div class="text-base font-bold text-amber-600 mt-0.5">± 128 Total Pelamar Masuk</div>
            </div>
        </div>
    </div>

    <!-- Row 2: K-Means Clustering Talenta -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Visual Segmen Cluster Cards -->
        <div class="lg:col-span-2 bg-white rounded-xl border border-slate-200 p-6 shadow-sm space-y-4">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                <div>
                    <h3 class="text-base font-bold text-slate-900">Segmentasi Kandidat (K-Means Clustering)</h3>
                    <p class="text-xs text-slate-500 mt-0.5">Pengelompokan talenta tanpa supervisi berdasarkan skor tes dan kesesuaian skill</p>
                </div>
                <span class="text-xs font-mono bg-slate-100 text-slate-700 px-2.5 py-1 rounded-md">k = 3 Klaster</span>
            </div>

            <!-- Canvas Klaster Scatter -->
            <div class="h-64 w-full">
                <canvas id="clusteringChart"></canvas>
            </div>

            <!-- Keterangan Klaster -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 pt-3">
                @foreach($analitikData['clusters'] as $cluster)
                <div class="p-3.5 rounded-xl border {{ $loop->first ? 'border-emerald-200 bg-emerald-50/40' : ($loop->iteration === 2 ? 'border-blue-200 bg-blue-50/40' : 'border-slate-200 bg-slate-50/40') }}">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold {{ $loop->first ? 'text-emerald-800' : ($loop->iteration === 2 ? 'text-blue-800' : 'text-slate-800') }}">
                            {{ $cluster['nama'] }}
                        </span>
                        <span class="text-[11px] font-mono px-1.5 py-0.5 rounded bg-white font-semibold text-slate-700 border border-slate-200">
                            {{ $cluster['jumlah'] }} orang
                        </span>
                    </div>
                    <p class="text-[11px] text-slate-600 mt-1.5 leading-relaxed">
                        {{ $cluster['karakteristik'] }}
                    </p>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Kolom Analisis Kompetensi & Distribusi Tahapan -->
        <div class="bg-white rounded-xl border border-slate-200 p-6 shadow-sm space-y-5 flex flex-col justify-between">
            <div>
                <h3 class="text-base font-bold text-slate-900">Rasio Pipeline Rekrutmen</h3>
                <p class="text-xs text-slate-500 mt-0.5">Konversi kandidat dari masuk hingga offering</p>

                <div class="space-y-3.5 mt-5">
                    <div>
                        <div class="flex justify-between text-xs font-medium text-slate-700 mb-1">
                            <span>Tahap Seleksi CV</span>
                            <span>100% (25)</span>
                        </div>
                        <div class="w-full bg-slate-100 h-2 rounded-full overflow-hidden">
                            <div class="bg-blue-600 h-2 rounded-full" style="width: 100%"></div>
                        </div>
                    </div>
                    <div>
                        <div class="flex justify-between text-xs font-medium text-slate-700 mb-1">
                            <span>Lolos Tes Esai</span>
                            <span>64% (16)</span>
                        </div>
                        <div class="w-full bg-slate-100 h-2 rounded-full overflow-hidden">
                            <div class="bg-blue-500 h-2 rounded-full" style="width: 64%"></div>
                        </div>
                    </div>
                    <div>
                        <div class="flex justify-between text-xs font-medium text-slate-700 mb-1">
                            <span>Wawancara User</span>
                            <span>36% (9)</span>
                        </div>
                        <div class="w-full bg-slate-100 h-2 rounded-full overflow-hidden">
                            <div class="bg-indigo-500 h-2 rounded-full" style="width: 36%"></div>
                        </div>
                    </div>
                    <div>
                        <div class="flex justify-between text-xs font-medium text-slate-700 mb-1">
                            <span>Offering / Diterima</span>
                            <span>16% (4)</span>
                        </div>
                        <div class="w-full bg-slate-100 h-2 rounded-full overflow-hidden">
                            <div class="bg-emerald-500 h-2 rounded-full" style="width: 16%"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="p-4 bg-slate-50 rounded-xl border border-slate-200 text-xs space-y-1">
                <span class="font-semibold text-slate-800">Catatan Efisiensi Seleksi:</span>
                <p class="text-slate-600 leading-relaxed">
                    Sistem automated screening berhasil memangkas waktu peninjauan awal berkas dari rata-rata 4.2 hari menjadi 1.1 hari kerja per pelamar.
                </p>
            </div>
        </div>
    </div>

</div>

<!-- Inisialisasi Chart.js -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // 1. Chart Time Series Forecasting
    const ctxForecast = document.getElementById('forecastingChart');
    if (ctxForecast && window.Chart) {
        const timeSeries = {{ Js::from($analitikData['time_series']) }};
        const labels = timeSeries.map(item => item.minggu);
        const aktualData = timeSeries.map(item => item.aktual);
        const forecastData = timeSeries.map(item => item.forecast);

        new Chart(ctxForecast, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [
                    {
                        label: 'Lamaran Aktual (Historis)',
                        data: aktualData,
                        borderColor: '#2563EB',
                        backgroundColor: 'rgba(37, 99, 235, 0.08)',
                        fill: true,
                        tension: 0.3,
                        pointRadius: 4,
                        pointBackgroundColor: '#2563EB',
                        borderWidth: 2.5
                    },
                    {
                        label: 'Proyeksi Peramalan (Forecasting)',
                        data: forecastData,
                        borderColor: '#F59E0B',
                        backgroundColor: 'rgba(245, 158, 11, 0.08)',
                        borderDash: [6, 4],
                        fill: false,
                        tension: 0.3,
                        pointRadius: 4,
                        pointBackgroundColor: '#F59E0B',
                        borderWidth: 2.5
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top',
                        labels: {
                            font: { family: 'Plus Jakarta Sans', size: 11 }
                        }
                    },
                    tooltip: {
                        mode: 'index',
                        intersect: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { color: '#F1F5F9' },
                        ticks: { font: { family: 'Plus Jakarta Sans', size: 10 } }
                    },
                    x: {
                        grid: { display: false },
                        ticks: { font: { family: 'Plus Jakarta Sans', size: 10 } }
                    }
                }
            }
        });
    }

    // 2. Chart K-Means Scatter
    const ctxCluster = document.getElementById('clusteringChart');
    if (ctxCluster && window.Chart) {
        new Chart(ctxCluster, {
            type: 'scatter',
            data: {
                datasets: [
                    {
                        label: 'Klaster 1: High Potential',
                        data: [
                            {x: 88, y: 92}, {x: 91, y: 95}, {x: 85, y: 89}, {x: 94, y: 90}, {x: 87, y: 86}
                        ],
                        backgroundColor: '#10B981',
                        pointRadius: 6
                    },
                    {
                        label: 'Klaster 2: Solid Contender',
                        data: [
                            {x: 75, y: 78}, {x: 80, y: 72}, {x: 73, y: 81}, {x: 78, y: 76}, {x: 82, y: 70}, {x: 74, y: 74}
                        ],
                        backgroundColor: '#2563EB',
                        pointRadius: 6
                    },
                    {
                        label: 'Klaster 3: Baseline & Growing',
                        data: [
                            {x: 60, y: 65}, {x: 66, y: 58}, {x: 62, y: 64}, {x: 58, y: 60}
                        ],
                        backgroundColor: '#94A3B8',
                        pointRadius: 6
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top',
                        labels: { font: { family: 'Plus Jakarta Sans', size: 11 } }
                    }
                },
                scales: {
                    x: {
                        title: { display: true, text: 'Kesesuaian Skill S-BERT (%)', font: { size: 11 } },
                        min: 50,
                        max: 100,
                        grid: { color: '#F1F5F9' }
                    },
                    y: {
                        title: { display: true, text: 'Nilai Tes Esai (%)', font: { size: 11 } },
                        min: 50,
                        max: 100,
                        grid: { color: '#F1F5F9' }
                    }
                }
            }
        });
    }
});
</script>
@endsection
