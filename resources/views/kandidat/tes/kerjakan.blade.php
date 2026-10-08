@extends('layouts.focus')

@section('title', 'Pengerjaan: Paket A Pemahaman Informasi & Prosedur')
@section('exam_name', 'Paket A: Pemahaman Informasi & Instruksi Operasional')

@section('header_timer')
<div x-data="{ 
    timeLeft: 24 * 60 + 18, 
    formatTime() {
        let m = Math.floor(this.timeLeft / 60);
        let s = this.timeLeft % 60;
        return (m < 10 ? '0' : '') + m + ':' + (s < 10 ? '0' : '') + s;
    },
    init() {
        setInterval(() => {
            if (this.timeLeft > 0) this.timeLeft--;
        }, 1000);
    }
}" class="flex items-center gap-2 px-3 py-1.5 rounded-xl bg-slate-900 text-white font-mono text-sm font-bold shadow-xs">
    <svg class="w-4 h-4 text-amber-400 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
    </svg>
    <span x-text="formatTime()">24:18</span>
</div>
@endsection

@section('content')
<div
    x-data="{ 
        currentQuestion: 0,
        questions: {{ json_encode($soalList) }},
        confirmModal: false,
        submitted: false,
        autosaveLabel: 'Tersimpan otomatis',
        triggerAutosave() {
            this.autosaveLabel = 'Menyimpan...';
            setTimeout(() => {
                this.autosaveLabel = 'Tersimpan otomatis barusan';
            }, 600);
        }
    }"
    class="flex-1 flex flex-col lg:flex-row min-h-0 bg-[#F8FAFC]"
>
    {{-- Left / Center: Reading Text & Question Area --}}
    <div class="flex-1 flex flex-col overflow-y-auto p-4 sm:p-8 space-y-6">
        
        {{-- Question Progress & Header --}}
        <div class="flex items-center justify-between pb-4 border-b border-[#E2E8F0]">
            <div class="flex items-center gap-3">
                <span class="w-8 h-8 rounded-lg bg-[#2563EB] text-white font-bold flex items-center justify-center text-sm shadow-xs" x-text="currentQuestion + 1"></span>
                <h2 class="text-base sm:text-lg font-bold text-[#0F172A]" x-text="questions[currentQuestion].judul"></h2>
            </div>
            <span class="text-xs font-semibold text-[#64748B]" x-text="'Soal ' + (currentQuestion + 1) + ' dari ' + questions.length"></span>
        </div>

        {{-- Reading Passage Card --}}
        <div class="p-6 rounded-2xl bg-white border border-[#E2E8F0] shadow-2xs space-y-3">
            <span class="text-xs font-bold text-[#2563EB] uppercase tracking-wider block">Teks Bacaan & Skenario:</span>
            <div class="text-sm text-[#334155] leading-relaxed p-4 rounded-xl bg-slate-50 border border-slate-200/80 font-normal select-none" x-text="questions[currentQuestion].teks">
            </div>
        </div>

        {{-- Essay Prompt & Answer Input --}}
        <div class="p-6 rounded-2xl bg-white border border-[#E2E8F0] shadow-2xs space-y-4">
            <div>
                <span class="text-xs font-bold text-[#0F172A] uppercase tracking-wider block mb-1">Pertanyaan Esai:</span>
                <p class="text-sm font-semibold text-[#0F172A]" x-text="questions[currentQuestion].pertanyaan"></p>
            </div>

            <div class="space-y-2">
                <div class="flex items-center justify-between text-xs text-[#64748B]">
                    <span>Tuliskan jawaban Anda secara singkat, lugas, dan terarah:</span>
                    <span class="text-emerald-600 font-medium" x-text="autosaveLabel"></span>
                </div>
                <textarea
                    rows="4"
                    x-model="questions[currentQuestion].jawaban_tersimpan"
                    @input="triggerAutosave()"
                    placeholder="Ketikkan jawaban Anda di sini..."
                    class="w-full rounded-xl border border-[#CBD5E1] p-4 text-sm text-[#0F172A] focus:border-[#2563EB] focus:ring-2 focus:ring-blue-100 outline-none leading-relaxed transition-smooth"
                ></textarea>
                <p class="text-[11px] text-slate-400">
                    Sistem mendeteksi substansi inti jawaban secara semantik. Gunakan bahasa Indonesia yang baku dan jelas.
                </p>
            </div>
        </div>

        {{-- Navigation Footer Buttons --}}
        <div class="pt-4 border-t border-[#E2E8F0] flex items-center justify-between">
            <x-button
                type="button"
                @click="if(currentQuestion > 0) currentQuestion--"
                ::disabled="currentQuestion === 0"
                variant="secondary"
                size="md"
            >
                ← Soal Sebelumnya
            </x-button>

            <div class="flex items-center gap-3">
                <x-button
                    type="button"
                    x-show="currentQuestion < questions.length - 1"
                    @click="currentQuestion++"
                    variant="primary"
                    size="md"
                >
                    Soal Berikutnya →
                </x-button>

                <x-button
                    type="button"
                    x-show="currentQuestion === questions.length - 1"
                    @click="confirmModal = true"
                    variant="success"
                    size="md"
                    class="shadow-sm"
                >
                    Selesai & Kumpulkan Jawaban ✓
                </x-button>
            </div>
        </div>

    </div>

    {{-- Right Sidebar: Question Palette & Instructions (Desktop: 300px) --}}
    <aside class="w-full lg:w-76 bg-white border-t lg:border-t-0 lg:border-l border-[#E2E8F0] p-6 space-y-6 flex flex-col justify-between">
        <div class="space-y-6">
            {{-- Question Palette --}}
            <div>
                <h3 class="text-xs font-bold text-[#0F172A] uppercase tracking-wider mb-3">Daftar Nomor Soal</h3>
                <div class="grid grid-cols-5 gap-2.5">
                    <template x-for="(q, idx) in questions" :key="idx">
                        <button
                            type="button"
                            @click="currentQuestion = idx"
                            :class="{
                                'bg-[#2563EB] text-white font-bold ring-2 ring-[#2563EB]/30': currentQuestion === idx,
                                'bg-emerald-50 text-emerald-800 border border-emerald-300 font-semibold': currentQuestion !== idx && q.jawaban_tersimpan.trim().length > 0,
                                'bg-slate-100 text-slate-600 hover:bg-slate-200': currentQuestion !== idx && q.jawaban_tersimpan.trim().length === 0
                            }"
                            class="h-10 rounded-xl text-xs flex items-center justify-center transition-smooth"
                            x-text="idx + 1"
                        ></button>
                    </template>
                </div>
                <div class="flex items-center gap-4 text-[11px] text-[#64748B] mt-4 pt-3 border-t border-slate-100">
                    <span class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded bg-emerald-500"></span> Terjawab</span>
                    <span class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded bg-[#2563EB]"></span> Aktif</span>
                    <span class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded bg-slate-200"></span> Belum</span>
                </div>
            </div>

            {{-- Exam Instructions Box --}}
            <div class="p-4 rounded-xl bg-slate-50 border border-[#E2E8F0] space-y-2 text-xs text-[#64748B]">
                <h4 class="font-bold text-[#0F172A] text-xs">Petunjuk Pengerjaan:</h4>
                <ul class="space-y-1.5 text-[11px] leading-relaxed list-disc list-inside">
                    <li>Bacalah seluruh teks bacaan sebelum merumuskan jawaban.</li>
                    <li>Fokus pada jawaban inti yang ditanyakan.</li>
                    <li>Jawaban disimpan otomatis secara berkala.</li>
                    <li>Pastikan semua butir telah terjawab sebelum mengumpulkan.</li>
                </ul>
            </div>
        </div>

        {{-- Submit Exam Action --}}
        <div class="pt-4 border-t border-[#E2E8F0]">
            <x-button
                type="button"
                @click="confirmModal = true"
                variant="outline"
                size="md"
                class="w-full"
            >
                Kumpulkan Tes
            </x-button>
        </div>
    </aside>

    {{-- Confirmation Modal --}}
    <div
        x-show="confirmModal"
        x-transition
        style="display: none;"
        class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs"
    >
        <div class="bg-white rounded-3xl border border-[#E2E8F0] shadow-2xl max-w-md w-full p-6 sm:p-8 space-y-5 text-center" @click.away="confirmModal = false">
            <div class="w-14 h-14 rounded-full bg-blue-100 text-[#2563EB] flex items-center justify-center mx-auto text-2xl font-bold">
                ?
            </div>
            <div>
                <h3 class="text-lg font-bold text-[#0F172A]">Konfirmasi Pengumpulan Jawaban</h3>
                <p class="text-xs text-[#64748B] mt-1.5 leading-relaxed">
                    Seluruh 5 butir soal esai telah memiliki draf jawaban tersimpan. Apakah Anda yakin ingin menyelesaikan dan mengirimkan sesi tes ini sekarang?
                </p>
            </div>

            <div class="p-3 rounded-xl bg-slate-50 border border-[#E2E8F0] text-xs text-[#64748B]">
                Waktu tersisa tidak dapat digunakan kembali setelah pengumpulan dikonfirmasi.
            </div>

            <div class="flex items-center justify-center gap-3 pt-2">
                <x-button type="button" @click="confirmModal = false" variant="ghost" size="md">
                    Periksa Kembali
                </x-button>
                <x-button href="{{ route('kandidat.tes.hasil', 'TES-A01') }}" variant="primary" size="md" class="shadow-sm">
                    Ya, Kumpulkan Jawaban
                </x-button>
            </div>
        </div>
    </div>
</div>
@endsection
