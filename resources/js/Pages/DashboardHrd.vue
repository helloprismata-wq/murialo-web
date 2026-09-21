<script setup>
defineProps({
    stats: Object,
    lowongan: Array,
    aiInsight: Object,
});
</script>

<template>
    <div class="min-h-screen bg-gray-50 p-8">
        <div class="max-w-4xl mx-auto space-y-5">

            <!-- AI Insight Hero -->
            <div class="bg-white rounded-xl p-6 shadow-sm">
                <div class="flex items-center gap-2 mb-3">
                    <span class="text-blue-600 text-sm font-medium">✨ Murialo AI menemukan</span>
                </div>
                <p class="text-xl mb-1">
                    {{ aiInsight.kandidat }} punya kecocokan tertinggi untuk {{ aiInsight.posisi }}
                    — skor {{ aiInsight.skor }}, {{ aiInsight.keterangan }}.
                </p>
                <p class="text-sm text-gray-500">
                    Dihitung menggunakan content-based + collaborative filtering
                </p>
            </div>

            <!-- Stats Grid -->
            <div class="grid grid-cols-4 gap-3">
                <div class="bg-gray-100 rounded-lg p-4">
                    <p class="text-sm text-gray-500 mb-1">Lowongan aktif</p>
                    <p class="text-2xl font-medium">{{ stats.lowonganAktif }}</p>
                </div>
                <div class="bg-gray-100 rounded-lg p-4">
                    <p class="text-sm text-gray-500 mb-1">Total pelamar</p>
                    <p class="text-2xl font-medium">{{ stats.totalPelamar }}</p>
                </div>
                <div class="bg-gray-100 rounded-lg p-4">
                    <p class="text-sm text-gray-500 mb-1">Pelamar minggu ini</p>
                    <p class="text-2xl font-medium">{{ stats.pelamarMingguIni }}</p>
                </div>
                <div class="bg-gray-100 rounded-lg p-4">
                    <p class="text-sm text-gray-500 mb-1">Anomali terdeteksi</p>
                    <p class="text-2xl font-medium">{{ stats.anomaliTerdeteksi }}</p>
                </div>
            </div>

            <!-- Lowongan List -->
            <div class="bg-white rounded-xl p-6 shadow-sm">
                <p class="font-medium mb-1">Lowongan berjalan</p>
                <p class="text-sm text-gray-500 mb-4">Diurutkan berdasarkan yang paling butuh perhatian</p>
                <div
                    v-for="job in lowongan"
                    :key="job.nama"
                    class="flex items-center justify-between py-3 border-t border-gray-100"
                >
                    <div>
                        <p>{{ job.nama }}</p>
                        <p class="text-xs text-gray-500">{{ job.keterangan }}</p>
                    </div>
                    <span class="text-xl font-medium">{{ job.pelamar }}</span>
                </div>
            </div>

        </div>
    </div>
</template>
