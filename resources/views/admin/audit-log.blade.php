@extends('layouts.admin', [
    'title' => 'Audit Log & Jejak Aktivitas',
    'subtitle' => 'Pencatatan riwayat interaksi pengguna, modifikasi data, dan proses AI'
])

@section('content')
<div class="space-y-6" x-data="{
    search: '',
    roleFilter: 'semua',
    logs: {{ Js::from($logs) }},

    get filteredLogs() {
        return this.logs.filter(l => {
            const matchSearch = l.user.toLowerCase().includes(this.search.toLowerCase()) || 
                                l.aktivitas.toLowerCase().includes(this.search.toLowerCase()) ||
                                l.modul.toLowerCase().includes(this.search.toLowerCase());
            const matchRole = this.roleFilter === 'semua' || l.role.toLowerCase() === this.roleFilter.toLowerCase();
            return matchSearch && matchRole;
        });
    }
}">

    <!-- Filter & Search Toolbar -->
    <div class="bg-white p-4 rounded-xl border border-slate-200 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div class="flex flex-wrap items-center gap-3">
            <div class="relative w-full sm:w-72">
                <input type="text" x-model="search" placeholder="Cari aktivitas, modul, atau aktor..." class="w-full text-xs pl-8 pr-3 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-blue-500">
                <svg class="w-4 h-4 text-slate-400 absolute left-2.5 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
            </div>
            <select x-model="roleFilter" class="text-xs border border-slate-300 rounded-lg px-3 py-2 bg-white text-slate-700 focus:outline-none focus:ring-1 focus:ring-blue-500">
                <option value="semua">Semua Role Aktor</option>
                <option value="Admin">Admin</option>
                <option value="Recruiter HR">Recruiter HR</option>
                <option value="Kandidat">Kandidat</option>
            </select>
        </div>

        <div class="text-xs text-slate-500 flex items-center gap-2">
            <span>Menampilkan <strong class="text-slate-900" x-text="filteredLogs.length"></strong> rekaman audit</span>
            <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-medium bg-emerald-50 text-emerald-700 border border-emerald-200">
                Immutability On
            </span>
        </div>
    </div>

    <!-- Tabel Audit Log -->
    <div class="bg-white rounded-xl border border-slate-200 overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs sm:text-sm">
                <thead class="bg-slate-50 text-slate-600 font-semibold uppercase text-[11px] tracking-wider border-b border-slate-200">
                    <tr>
                        <th class="py-3 px-4">Waktu (WIB)</th>
                        <th class="py-3 px-4">Aktor Pengguna</th>
                        <th class="py-3 px-4">Role</th>
                        <th class="py-3 px-4">Modul Sistem</th>
                        <th class="py-3 px-4">Deskripsi Aktivitas</th>
                        <th class="py-3 px-4 text-right">Alamat IP</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 text-slate-700">
                    <template x-for="item in filteredLogs" :key="item.id">
                        <tr class="hover:bg-slate-50 transition">
                            <td class="py-3 px-4 text-slate-500 text-xs font-mono whitespace-nowrap" x-text="item.waktu"></td>
                            <td class="py-3 px-4 font-bold text-slate-900 whitespace-nowrap" x-text="item.user"></td>
                            <td class="py-3 px-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-semibold"
                                    :class="{
                                        'bg-purple-100 text-purple-800': item.role === 'Admin',
                                        'bg-blue-100 text-blue-800': item.role === 'Recruiter HR',
                                        'bg-emerald-100 text-emerald-800': item.role === 'Kandidat'
                                    }"
                                    x-text="item.role">
                                </span>
                            </td>
                            <td class="py-3 px-4 font-medium text-slate-800 whitespace-nowrap" x-text="item.modul"></td>
                            <td class="py-3 px-4 text-slate-600" x-text="item.aktivitas"></td>
                            <td class="py-3 px-4 text-right font-mono text-xs text-slate-400 whitespace-nowrap" x-text="item.ip"></td>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
