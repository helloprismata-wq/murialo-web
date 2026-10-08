@extends('layouts.admin', [
    'title' => 'Hak Akses & Perizinan',
    'subtitle' => 'Konfigurasi matriks kewenangan modul untuk kandidat, HR, dan administrator'
])

@section('content')
<div class="space-y-6" x-data="{
    roles: [
        {
            id: 'admin',
            nama: 'Administrator',
            deskripsi: 'Akses penuh ke seluruh konfigurasi sistem, database, audit log, dan manajemen pengguna.',
            pengguna: 2,
            badge: 'bg-purple-100 text-purple-800'
        },
        {
            id: 'hr',
            nama: 'HR & Recruiter',
            deskripsi: 'Mengelola lowongan kerja, melihat CV, pipeline kandidat, evaluasi tes esai, dan analitik rekrutmen.',
            pengguna: 5,
            badge: 'bg-blue-100 text-blue-800'
        },
        {
            id: 'kandidat',
            nama: 'Kandidat',
            deskripsi: 'Menjelajah lowongan, melengkapi data profil, mengunggah CV, dan mengerjakan tugas tes esai.',
            pengguna: 15,
            badge: 'bg-emerald-100 text-emerald-800'
        }
    ],
    matriks: [
        { modul: 'Jelajah Lowongan & Melamar', kandidat: true, hr: true, admin: true },
        { modul: 'Unggah CV & Ekstraksi AI Pribadi', kandidat: true, hr: false, admin: false },
        { modul: 'Pengerjaan Ujian Esai (Layout Fokus)', kandidat: true, hr: false, admin: false },
        { modul: 'Pembuatan & Publikasi Lowongan Kerja', kandidat: false, hr: true, admin: true },
        { modul: 'Kanban Pipeline & Status Tahapan', kandidat: false, hr: true, admin: true },
        { modul: 'Resume Parser Masuk & Verifikasi Data', kandidat: false, hr: true, admin: true },
        { modul: 'Automated Skill Matching (S-BERT)', kandidat: false, hr: true, admin: true },
        { modul: 'Smart Grading Test & Bank Kunci Jawaban', kandidat: false, hr: true, admin: true },
        { modul: 'Rekomendasi Kandidat (CBF/CF)', kandidat: false, hr: true, admin: true },
        { modul: 'Deteksi Anomali & Integritas Data', kandidat: false, hr: true, admin: true },
        { modul: 'Recruitment Analytics & Time Series', kandidat: false, hr: true, admin: true },
        { modul: 'Manajemen Akun & Role Matrix', kandidat: false, hr: false, admin: true },
        { modul: 'Audit Log & Konfigurasi API Engine', kandidat: false, hr: false, admin: true }
    ],
    showNotice: false,
    triggerSave() {
        this.showNotice = true;
        setTimeout(() => this.showNotice = false, 3000);
    }
}">

    <!-- Alert Info Matriks -->
    <x-alert type="info">
        <div class="flex items-center justify-between">
            <span class="text-xs sm:text-sm">
                Matriks hak akses menjaga pemisahan wewenang. Kandidat tidak diizinkan mengakses kunci jawaban, rubrik, atau analitik internal HR.
            </span>
            <span class="text-[11px] font-mono uppercase bg-blue-100 text-blue-800 px-2 py-0.5 rounded font-semibold shrink-0">RBAC Security</span>
        </div>
    </x-alert>

    <!-- Cards Ringkasan Role -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <template x-for="r in roles" :key="r.id">
            <div class="bg-white p-5 rounded-xl border border-slate-200 space-y-3">
                <div class="flex items-center justify-between">
                    <span class="px-2.5 py-0.5 rounded text-xs font-bold uppercase tracking-wider" :class="r.badge" x-text="r.nama"></span>
                    <span class="text-xs text-slate-500 font-mono" x-text="`${r.pengguna} pengguna`"></span>
                </div>
                <p class="text-xs text-slate-600 leading-relaxed" x-text="r.deskripsi"></p>
                <div class="pt-2 border-t border-slate-100 flex items-center justify-between text-xs text-slate-500">
                    <span>Status: Aktif</span>
                    <span class="text-blue-600 font-medium">Bawaan Sistem</span>
                </div>
            </div>
        </template>
    </div>

    <!-- Matriks Perizinan Tabel -->
    <div class="bg-white rounded-xl border border-slate-200 overflow-hidden shadow-sm">
        <div class="p-4 border-b border-slate-200 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <div>
                <h3 class="text-sm font-bold text-slate-900">Matriks Izin Modul Sistem</h3>
                <p class="text-xs text-slate-500">Centang atau tinjau izin akses yang dialokasikan untuk setiap peran.</p>
            </div>
            <button @click="triggerSave()" class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold text-white bg-blue-600 hover:bg-blue-700 rounded-lg shadow-sm transition">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                <span>Simpan Matriks Izin</span>
            </button>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs sm:text-sm">
                <thead class="bg-slate-50 text-slate-600 font-semibold uppercase text-[11px] tracking-wider border-b border-slate-200">
                    <tr>
                        <th class="py-3 px-4">Nama Modul / Fitur</th>
                        <th class="py-3 px-4 text-center">Kandidat</th>
                        <th class="py-3 px-4 text-center">HR & Recruiter</th>
                        <th class="py-3 px-4 text-center">Administrator</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 text-slate-700">
                    <template x-for="(item, idx) in matriks" :key="idx">
                        <tr class="hover:bg-slate-50 transition">
                            <td class="py-3 px-4 font-medium text-slate-900" x-text="item.modul"></td>
                            <td class="py-3 px-4 text-center">
                                <input type="checkbox" x-model="item.kandidat" class="w-4 h-4 rounded text-blue-600 border-slate-300 focus:ring-blue-500">
                            </td>
                            <td class="py-3 px-4 text-center">
                                <input type="checkbox" x-model="item.hr" class="w-4 h-4 rounded text-blue-600 border-slate-300 focus:ring-blue-500">
                            </td>
                            <td class="py-3 px-4 text-center">
                                <input type="checkbox" x-model="item.admin" disabled checked class="w-4 h-4 rounded text-purple-600 border-slate-300 focus:ring-purple-500 bg-slate-100 cursor-not-allowed">
                            </td>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Toast Saved -->
    <div x-show="showNotice" x-cloak class="fixed bottom-6 right-6 z-50 bg-slate-900 text-white px-4 py-3 rounded-xl shadow-lg text-xs flex items-center gap-3">
        <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
        <span>Konfigurasi matriks izin disimpan (Simulasi Demo)</span>
    </div>

</div>
@endsection
