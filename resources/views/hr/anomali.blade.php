@extends('layouts.hr', [
    'title' => 'Deteksi Anomali & Integritas',
    'subtitle' => 'Pemantauan pola janggal dan tinjauan manual HR dengan bahasa netral'
])

@section('content')
<div class="space-y-6" x-data="{
    filterStatus: 'semua',
    filterTingkat: 'semua',
    selectedItem: null,
    modalDetailOpen: false,
    catatanTindakLanjut: '',
    statusTindakLanjut: 'sedang_ditinjau',
    toastMessage: '',
    showToast: false,
    
    anomaliList: {{ Js::from($anomaliList) }},
    
    get filteredList() {
        return this.anomaliList.filter(item => {
            const matchStatus = this.filterStatus === 'semua' || item.status === this.filterStatus;
            const matchTingkat = this.filterTingkat === 'semua' || item.tingkat_risiko.toLowerCase() === this.filterTingkat.toLowerCase();
            return matchStatus && matchTingkat;
        });
    },

    openDetail(item) {
        this.selectedItem = item;
        this.catatanTindakLanjut = item.catatan_hr || '';
        this.statusTindakLanjut = item.status || 'sedang_ditinjau';
        this.modalDetailOpen = true;
    },

    simpanTindakLanjut() {
        if (this.selectedItem) {
            this.selectedItem.status = this.statusTindakLanjut;
            this.selectedItem.catatan_hr = this.catatanTindakLanjut;
            this.modalDetailOpen = false;
            this.triggerToast('Catatan tindak lanjut dan status peninjauan berhasil diperbarui (Simulasi Demo)');
        }
    },

    triggerToast(msg) {
        this.toastMessage = msg;
        this.showToast = true;
        setTimeout(() => this.showToast = false, 3500);
    }
}">

    <!-- Alert Edukasi & Prinsip Etika AI Netral -->
    <x-alert type="info">
        <div class="flex items-start gap-3">
            <svg class="w-5 h-5 text-blue-600 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <div>
                <strong class="font-semibold text-blue-900">Pedoman Etika Deteksi Anomali:</strong>
                <p class="mt-0.5 text-blue-800 text-xs sm:text-sm">
                    Modul ini bertujuan menyajikan <strong>sinyal pola inkonsistensi data</strong> untuk memandu verifikasi manusia (Human-in-the-loop). 
                    Temuan ini <em>bukan</em> tuduhan kecurangan otomatis terhadap kandidat. Seluruh keputusan seleksi tetap berada di bawah evaluasi objektif tim HR.
                </p>
            </div>
        </div>
    </x-alert>

    <!-- Ringkasan Statistik Status Anomali -->
    <div class="grid grid-cols-1 sm:grid-cols-4 gap-4">
        <div class="bg-white p-4 rounded-xl border border-slate-200">
            <div class="text-xs font-medium text-slate-500 uppercase tracking-wider">Total Temuan</div>
            <div class="text-2xl font-bold text-slate-900 mt-1" x-text="anomaliList.length"></div>
            <div class="text-xs text-slate-500 mt-1">Perlu perhatian HR</div>
        </div>
        <div class="bg-white p-4 rounded-xl border border-slate-200">
            <div class="text-xs font-medium text-slate-500 uppercase tracking-wider">Belum Ditinjau</div>
            <div class="text-2xl font-bold text-amber-600 mt-1" x-text="anomaliList.filter(i => i.status === 'belum_ditinjau').length"></div>
            <div class="text-xs text-amber-700 mt-1">Menunggu verifikasi awal</div>
        </div>
        <div class="bg-white p-4 rounded-xl border border-slate-200">
            <div class="text-xs font-medium text-slate-500 uppercase tracking-wider">Sedang Ditinjau</div>
            <div class="text-2xl font-bold text-blue-600 mt-1" x-text="anomaliList.filter(i => i.status === 'sedang_ditinjau').length"></div>
            <div class="text-xs text-blue-700 mt-1">Dalam proses cross-check</div>
        </div>
        <div class="bg-white p-4 rounded-xl border border-slate-200">
            <div class="text-xs font-medium text-slate-500 uppercase tracking-wider">Telah Diselesaikan</div>
            <div class="text-2xl font-bold text-emerald-600 mt-1" x-text="anomaliList.filter(i => i.status === 'selesai').length"></div>
            <div class="text-xs text-emerald-700 mt-1">Catatan HR terdokumentasi</div>
        </div>
    </div>

    <!-- Filter & Toolbar -->
    <div class="bg-white p-4 rounded-xl border border-slate-200 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div class="flex flex-wrap items-center gap-3">
            <div class="flex items-center gap-2">
                <span class="text-xs font-semibold text-slate-500">Status:</span>
                <select x-model="filterStatus" class="text-xs border border-slate-300 rounded-lg px-2.5 py-1.5 bg-white text-slate-700 focus:outline-none focus:ring-1 focus:ring-blue-500">
                    <option value="semua">Semua Status</option>
                    <option value="belum_ditinjau">Belum Ditinjau</option>
                    <option value="sedang_ditinjau">Sedang Ditinjau</option>
                    <option value="selesai">Selesai</option>
                </select>
            </div>
            <div class="flex items-center gap-2">
                <span class="text-xs font-semibold text-slate-500">Tingkat Perhatian:</span>
                <select x-model="filterTingkat" class="text-xs border border-slate-300 rounded-lg px-2.5 py-1.5 bg-white text-slate-700 focus:outline-none focus:ring-1 focus:ring-blue-500">
                    <option value="semua">Semua Tingkat</option>
                    <option value="Tinggi">Tinggi</option>
                    <option value="Sedang">Sedang</option>
                    <option value="Rendah">Rendah</option>
                </select>
            </div>
        </div>

        <div class="text-xs text-slate-500 flex items-center gap-2">
            <span>Menampilkan <strong class="text-slate-900" x-text="filteredList.length"></strong> temuan</span>
            <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-medium bg-slate-100 text-slate-700">Demo Model</span>
        </div>
    </div>

    <!-- Tabel Daftar Temuan Anomali -->
    <div class="bg-white rounded-xl border border-slate-200 overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs sm:text-sm">
                <thead class="bg-slate-50 text-slate-600 font-semibold uppercase text-[11px] tracking-wider border-b border-slate-200">
                    <tr>
                        <th class="py-3 px-4">Kandidat & Posisi</th>
                        <th class="py-3 px-4">Indikator & Pola</th>
                        <th class="py-3 px-4">Tingkat & Skor</th>
                        <th class="py-3 px-4">Status Peninjauan</th>
                        <th class="py-3 px-4">Catatan HR Terakhir</th>
                        <th class="py-3 px-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 text-slate-700">
                    <template x-for="item in filteredList" :key="item.id">
                        <tr class="hover:bg-slate-50 transition">
                            <td class="py-3 px-4">
                                <div class="font-bold text-slate-900" x-text="item.nama"></div>
                                <div class="text-xs text-slate-500" x-text="item.posisi"></div>
                            </td>
                            <td class="py-3 px-4 max-w-xs">
                                <div class="font-medium text-slate-800" x-text="item.indikator"></div>
                                <div class="text-xs text-slate-500 truncate mt-0.5" x-text="item.detail_pola"></div>
                            </td>
                            <td class="py-3 px-4">
                                <div class="flex items-center gap-2">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-semibold"
                                        :class="{
                                            'bg-rose-100 text-rose-800': item.tingkat_risiko === 'Tinggi',
                                            'bg-amber-100 text-amber-800': item.tingkat_risiko === 'Sedang',
                                            'bg-slate-100 text-slate-800': item.tingkat_risiko === 'Rendah'
                                        }"
                                        x-text="item.tingkat_risiko">
                                    </span>
                                    <span class="text-xs text-slate-500 font-mono" x-text="`Skor: ${item.skor}`"></span>
                                </div>
                            </td>
                            <td class="py-3 px-4">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
                                    :class="{
                                        'bg-amber-50 text-amber-700 border border-amber-200': item.status === 'belum_ditinjau',
                                        'bg-blue-50 text-blue-700 border border-blue-200': item.status === 'sedang_ditinjau',
                                        'bg-emerald-50 text-emerald-700 border border-emerald-200': item.status === 'selesai'
                                    }"
                                    x-text="item.status === 'belum_ditinjau' ? 'Belum Ditinjau' : (item.status === 'sedang_ditinjau' ? 'Sedang Ditinjau' : 'Selesai')">
                                </span>
                            </td>
                            <td class="py-3 px-4 text-xs max-w-xs">
                                <template x-if="item.catatan_hr">
                                    <span class="text-slate-600 line-clamp-2 italic" x-text="`“${item.catatan_hr}”`"></span>
                                </template>
                                <template x-if="!item.catatan_hr">
                                    <span class="text-slate-400 italic">Belum ada catatan</span>
                                </template>
                            </td>
                            <td class="py-3 px-4 text-right">
                                <button @click="openDetail(item)" class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium text-blue-600 bg-blue-50 hover:bg-blue-100 rounded-lg transition">
                                    <span>Tinjau</span>
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </button>
                            </td>
                        </tr>
                    </template>
                    <template x-if="filteredList.length === 0">
                        <tr>
                            <td colspan="6" class="py-8 text-center text-slate-400 text-xs">
                                Tidak ada temuan yang sesuai dengan filter yang dipilih.
                            </td>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal Detail & Tindak Lanjut HR -->
    <div x-show="modalDetailOpen" x-cloak class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-center justify-center min-h-screen p-4 text-center sm:p-0">
            <div x-show="modalDetailOpen" @click="modalDetailOpen = false" class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm transition-opacity"></div>

            <div x-show="modalDetailOpen" class="relative inline-block bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:max-w-xl sm:w-full border border-slate-200">
                <template x-if="selectedItem">
                    <div class="p-6">
                        <!-- Header Modal -->
                        <div class="flex items-start justify-between border-b border-slate-100 pb-4">
                            <div>
                                <div class="inline-flex items-center gap-2 mb-1">
                                    <span class="px-2 py-0.5 rounded text-[11px] font-semibold uppercase tracking-wider"
                                        :class="{
                                            'bg-rose-100 text-rose-800': selectedItem.tingkat_risiko === 'Tinggi',
                                            'bg-amber-100 text-amber-800': selectedItem.tingkat_risiko === 'Sedang',
                                            'bg-slate-100 text-slate-800': selectedItem.tingkat_risiko === 'Rendah'
                                        }"
                                        x-text="`Perhatian: ${selectedItem.tingkat_risiko}`">
                                    </span>
                                    <span class="text-xs font-mono text-slate-500" x-text="`Skor: ${selectedItem.skor}`"></span>
                                </div>
                                <h3 class="text-lg font-bold text-slate-900" x-text="selectedItem.nama"></h3>
                                <p class="text-xs text-slate-500" x-text="selectedItem.posisi"></p>
                            </div>
                            <button @click="modalDetailOpen = false" class="text-slate-400 hover:text-slate-600 p-1 rounded-lg">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        </div>

                        <!-- Konten Rincian Pola -->
                        <div class="mt-4 space-y-4">
                            <div class="bg-slate-50 p-4 rounded-xl border border-slate-200 space-y-2">
                                <div class="text-xs font-semibold text-slate-700">Indikator Terdeteksi:</div>
                                <div class="text-sm font-bold text-slate-900" x-text="selectedItem.indikator"></div>
                                <div class="text-xs text-slate-600 mt-1 leading-relaxed" x-text="selectedItem.detail_pola"></div>
                            </div>

                            <div class="bg-blue-50/60 p-3.5 rounded-xl border border-blue-200/60">
                                <div class="text-xs font-semibold text-blue-900">Rekomendasi Tindakan HR:</div>
                                <p class="text-xs text-blue-800 mt-1">
                                    Lakukan klarifikasi secara ramah dan profesional pada tahap wawancara teknis/HR. Mintalah penjelasan detail mengenai riwayat portofolio atau pengerjaan tugas tanpa melakukan konfrontasi langsung.
                                </p>
                            </div>

                            <!-- Formulir Tindak Lanjut -->
                            <div class="space-y-3 pt-2">
                                <div>
                                    <label class="block text-xs font-semibold text-slate-700 mb-1">Status Peninjauan:</label>
                                    <select x-model="statusTindakLanjut" class="w-full text-xs sm:text-sm border border-slate-300 rounded-lg px-3 py-2 bg-white text-slate-800 focus:outline-none focus:ring-1 focus:ring-blue-500">
                                        <option value="belum_ditinjau">Belum Ditinjau</option>
                                        <option value="sedang_ditinjau">Sedang Ditinjau (Verifikasi)</option>
                                        <option value="selesai">Selesai (Keputusan Diambil)</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-slate-700 mb-1">Catatan Evaluator HR:</label>
                                    <textarea x-model="catatanTindakLanjut" rows="3" placeholder="Masukkan catatan hasil verifikasi latar belakang, konfirmasi kandidat, atau keputusan tim..." class="w-full text-xs sm:text-sm border border-slate-300 rounded-lg p-2.5 bg-white text-slate-800 focus:outline-none focus:ring-1 focus:ring-blue-500"></textarea>
                                </div>
                            </div>
                        </div>

                        <!-- Footer Modal -->
                        <div class="mt-6 flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                            <button type="button" @click="modalDetailOpen = false" class="px-4 py-2 text-xs font-semibold text-slate-700 hover:bg-slate-100 rounded-lg transition">
                                Batal
                            </button>
                            <button type="button" @click="simpanTindakLanjut()" class="px-4 py-2 text-xs font-semibold text-white bg-blue-600 hover:bg-blue-700 rounded-lg shadow-sm transition">
                                Simpan Catatan Peninjauan
                            </button>
                        </div>
                    </div>
                </template>
            </div>
        </div>
    </div>

    <!-- Toast Notifikasi Interaksi Demo -->
    <div x-show="showToast" x-cloak class="fixed bottom-6 right-6 z-50 bg-slate-900 text-white px-4 py-3 rounded-xl shadow-lg text-xs flex items-center gap-3 transition">
        <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
        </svg>
        <span x-text="toastMessage"></span>
    </div>

</div>
@endsection
