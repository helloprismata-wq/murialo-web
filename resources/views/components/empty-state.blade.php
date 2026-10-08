@props([
    'title' => 'Belum Ada Data',
    'description' => 'Data yang Anda cari tidak ditemukan atau belum ditambahkan ke dalam sistem.',
    'action' => null,
])

<div {{ $attributes->merge(['class' => 'p-10 text-center flex flex-col items-center justify-center bg-white rounded-2xl border border-[#E2E8F0] shadow-2xs']) }}>
    <div class="w-14 h-14 rounded-2xl bg-[#EFF6FF] border border-[#BFDBFE]/60 flex items-center justify-center text-[#2563EB] mb-4">
        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
        </svg>
    </div>

    <h3 class="text-base font-semibold text-[#0F172A] mb-1.5">{{ $title }}</h3>
    <p class="text-xs text-[#64748B] max-w-sm mx-auto leading-relaxed mb-5">
        {{ $description }}
    </p>

    @if($action || $slot->isNotEmpty())
        <div class="flex items-center gap-3">
            {{ $action ?? $slot }}
        </div>
    @endif
</div>
