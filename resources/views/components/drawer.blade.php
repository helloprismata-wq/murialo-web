@props([
    'name',
    'title' => null,
    'side' => 'right', // left, right
])

<div
    x-data="{ show: false }"
    x-show="show"
    x-on:open-drawer.window="$event.detail == '{{ $name }}' ? show = true : null"
    x-on:close-drawer.window="$event.detail == '{{ $name }}' ? show = false : null"
    x-on:keydown.escape.window="show = false"
    style="display: none;"
    class="fixed inset-0 z-50 overflow-hidden"
>
    {{-- Backdrop --}}
    <div
        x-show="show"
        x-transition:enter="ease-out duration-200"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="ease-in duration-150"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 bg-slate-900/40 backdrop-blur-xs transition-opacity"
        @click="show = false"
    ></div>

    <div class="fixed inset-y-0 {{ $side === 'left' ? 'left-0' : 'right-0' }} max-w-full flex">
        <div
            x-show="show"
            x-transition:enter="transform transition ease-in-out duration-250 sm:duration-300"
            x-transition:enter-start="{{ $side === 'left' ? '-translate-x-full' : 'translate-x-full' }}"
            x-transition:enter-end="translate-x-0"
            x-transition:leave="transform transition ease-in-out duration-200"
            x-transition:leave-start="translate-x-0"
            x-transition:leave-end="{{ $side === 'left' ? '-translate-x-full' : 'translate-x-full' }}"
            class="w-screen max-w-md bg-white border-{{ $side === 'left' ? 'r' : 'l' }} border-[#E2E8F0] shadow-2xl flex flex-col"
            @click.away="show = false"
        >
            <div class="px-6 py-4.5 border-b border-[#E2E8F0] flex items-center justify-between">
                <h3 class="text-base font-semibold text-[#0F172A]">{{ $title ?? 'Menu Navigasi' }}</h3>
                <button
                    type="button"
                    @click="show = false"
                    class="p-1 rounded-lg text-slate-400 hover:text-slate-600 hover:bg-slate-100 transition-smooth"
                    aria-label="Tutup drawer"
                >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <div class="flex-1 overflow-y-auto p-6">
                {{ $slot }}
            </div>
        </div>
    </div>
</div>
