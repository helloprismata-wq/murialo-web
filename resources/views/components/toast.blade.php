@props([
    'message' => 'Tindakan berhasil disimpan (Simulasi Demo)',
    'type' => 'success', // success, info, warning, danger
])

<div
    x-data="{ show: false, message: '{{ $message }}', type: '{{ $type }}' }"
    x-on:show-toast.window="message = $event.detail.message || message; type = $event.detail.type || type; show = true; setTimeout(() => show = false, 3500)"
    x-show="show"
    x-transition:enter="transform ease-out duration-200 transition"
    x-transition:enter-start="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-2"
    x-transition:enter-end="translate-y-0 opacity-100 sm:translate-x-0"
    x-transition:leave="transition ease-in duration-150"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
    style="display: none;"
    class="fixed bottom-5 right-5 z-50 max-w-sm w-full bg-slate-900 text-white rounded-xl shadow-2xl p-4 flex items-center justify-between gap-3 border border-slate-700/60"
    role="status"
>
    <div class="flex items-center gap-3">
        <span class="w-2.5 h-2.5 rounded-full bg-[#10B981] shrink-0"></span>
        <p class="text-xs font-medium leading-relaxed" x-text="message"></p>
    </div>
    <button
        type="button"
        @click="show = false"
        class="text-slate-400 hover:text-white p-1 rounded-md"
        aria-label="Tutup pemberitahuan"
    >
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
        </svg>
    </button>
</div>
