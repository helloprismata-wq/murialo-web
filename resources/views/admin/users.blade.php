@extends('layouts.admin', [
    'title' => 'Pengelolaan Pengguna',
    'subtitle' => 'Kelola akun kandidat, staf HR/Recruiter, dan administrator sistem'
])

@section('content')
<div class="space-y-6" x-data="{
    search: '',
    roleFilter: 'semua',
    users: {{ Js::from($users) }},
    modalOpen: false,
    selectedUser: null,
    toastMsg: '',
    showToast: false,

    get filteredUsers() {
        return this.users.filter(u => {
            const matchSearch = u.nama.toLowerCase().includes(this.search.toLowerCase()) || 
                                u.email.toLowerCase().includes(this.search.toLowerCase());
            const matchRole = this.roleFilter === 'semua' || u.role === this.roleFilter;
            return matchSearch && matchRole;
        });
    },

    editUser(u) {
        this.selectedUser = JSON.parse(JSON.stringify(u));
        this.modalOpen = true;
    },

    saveUser() {
        const idx = this.users.findIndex(item => item.id === this.selectedUser.id);
        if (idx !== -1) {
            this.users[idx] = this.selectedUser;
        }
        this.modalOpen = false;
        this.triggerToast('Perubahan pengguna berhasil disimpan (Simulasi Demo)');
    },

    triggerToast(msg) {
        this.toastMsg = msg;
        this.showToast = true;
        setTimeout(() => this.showToast = false, 3000);
    }
}">

    <!-- Header Actions & Filter -->
    <div class="bg-white p-4 rounded-xl border border-slate-200 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div class="flex flex-wrap items-center gap-3">
            <div class="relative w-full sm:w-64">
                <input type="text" x-model="search" placeholder="Cari nama atau email..." class="w-full text-xs pl-8 pr-3 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-blue-500">
                <svg class="w-4 h-4 text-slate-400 absolute left-2.5 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
            </div>
            <select x-model="roleFilter" class="text-xs border border-slate-300 rounded-lg px-3 py-2 bg-white text-slate-700 focus:outline-none focus:ring-1 focus:ring-blue-500">
                <option value="semua">Semua Role</option>
                <option value="admin">Administrator</option>
                <option value="hr">HR / Recruiter</option>
                <option value="kandidat">Kandidat</option>
            </select>
        </div>

        <button @click="triggerToast('Fitur penambahan pengguna baru akan terhubung ke backend produksi')" class="inline-flex items-center gap-2 px-3.5 py-2 text-xs font-semibold text-white bg-blue-600 hover:bg-blue-700 rounded-lg shadow-sm transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            <span>Tambah Pengguna</span>
        </button>
    </div>

    <!-- Tabel Pengguna -->
    <div class="bg-white rounded-xl border border-slate-200 overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs sm:text-sm">
                <thead class="bg-slate-50 text-slate-600 font-semibold uppercase text-[11px] tracking-wider border-b border-slate-200">
                    <tr>
                        <th class="py-3 px-4">Nama Lengkap</th>
                        <th class="py-3 px-4">Email</th>
                        <th class="py-3 px-4">Role / Peran</th>
                        <th class="py-3 px-4">Status Akun</th>
                        <th class="py-3 px-4">Terdaftar Sejak</th>
                        <th class="py-3 px-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 text-slate-700">
                    <template x-for="u in filteredUsers" :key="u.id">
                        <tr class="hover:bg-slate-50 transition">
                            <td class="py-3 px-4 font-bold text-slate-900" x-text="u.nama"></td>
                            <td class="py-3 px-4 text-slate-600" x-text="u.email"></td>
                            <td class="py-3 px-4">
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-semibold uppercase"
                                    :class="{
                                        'bg-purple-100 text-purple-800': u.role === 'admin',
                                        'bg-blue-100 text-blue-800': u.role === 'hr',
                                        'bg-emerald-100 text-emerald-800': u.role === 'kandidat'
                                    }"
                                    x-text="u.role">
                                </span>
                            </td>
                            <td class="py-3 px-4">
                                <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-full text-xs font-medium"
                                    :class="u.status === 'Aktif' ? 'bg-emerald-50 text-emerald-700' : 'bg-slate-100 text-slate-600'">
                                    <span class="w-1.5 h-1.5 rounded-full" :class="u.status === 'Aktif' ? 'bg-emerald-500' : 'bg-slate-400'"></span>
                                    <span x-text="u.status"></span>
                                </span>
                            </td>
                            <td class="py-3 px-4 text-slate-500 text-xs" x-text="u.dibuat_pada"></td>
                            <td class="py-3 px-4 text-right">
                                <button @click="editUser(u)" class="px-2.5 py-1 text-xs font-medium text-slate-700 bg-slate-100 hover:bg-slate-200 rounded-lg transition">
                                    Edit Role
                                </button>
                            </td>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal Edit User -->
    <div x-show="modalOpen" x-cloak class="fixed inset-0 z-50 overflow-y-auto" role="dialog" aria-modal="true">
        <div class="flex items-center justify-center min-h-screen p-4 text-center sm:p-0">
            <div x-show="modalOpen" @click="modalOpen = false" class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm"></div>

            <div x-show="modalOpen" class="relative inline-block bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:max-w-md sm:w-full border border-slate-200 p-6">
                <template x-if="selectedUser">
                    <div class="space-y-4">
                        <h3 class="text-base font-bold text-slate-900">Ubah Peran & Status Pengguna</h3>
                        
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">Nama Pengguna</label>
                            <input type="text" x-model="selectedUser.nama" class="w-full text-xs sm:text-sm border border-slate-300 rounded-lg px-3 py-2 bg-slate-50" readonly>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">Email</label>
                            <input type="email" x-model="selectedUser.email" class="w-full text-xs sm:text-sm border border-slate-300 rounded-lg px-3 py-2 bg-slate-50" readonly>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">Role / Peran</label>
                            <select x-model="selectedUser.role" class="w-full text-xs sm:text-sm border border-slate-300 rounded-lg px-3 py-2 bg-white">
                                <option value="admin">Administrator</option>
                                <option value="hr">HR / Recruiter</option>
                                <option value="kandidat">Kandidat</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">Status</label>
                            <select x-model="selectedUser.status" class="w-full text-xs sm:text-sm border border-slate-300 rounded-lg px-3 py-2 bg-white">
                                <option value="Aktif">Aktif</option>
                                <option value="Nonaktif">Nonaktif</option>
                            </select>
                        </div>

                        <div class="flex justify-end gap-2 pt-4 border-t border-slate-100">
                            <button type="button" @click="modalOpen = false" class="px-3.5 py-2 text-xs font-semibold text-slate-600 hover:bg-slate-100 rounded-lg">Batal</button>
                            <button type="button" @click="saveUser()" class="px-3.5 py-2 text-xs font-semibold text-white bg-blue-600 hover:bg-blue-700 rounded-lg shadow-sm">Simpan</button>
                        </div>
                    </div>
                </template>
            </div>
        </div>
    </div>

    <!-- Toast -->
    <div x-show="showToast" x-cloak class="fixed bottom-6 right-6 z-50 bg-slate-900 text-white px-4 py-3 rounded-xl shadow-lg text-xs flex items-center gap-3">
        <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
        <span x-text="toastMsg"></span>
    </div>

</div>
@endsection
