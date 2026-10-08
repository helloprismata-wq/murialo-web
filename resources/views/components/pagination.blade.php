@props([
    'current' => 1,
    'total' => 3,
    'from' => 1,
    'to' => 10,
    'totalItems' => 25,
])

<div class="flex flex-col sm:flex-row items-center justify-between gap-3 px-6 py-4 bg-white border-t border-[#E2E8F0] text-xs text-[#64748B]">
    <div>
        Menampilkan <span class="font-semibold text-[#0F172A]">{{ $from }}</span> hingga <span class="font-semibold text-[#0F172A]">{{ $to }}</span> dari <span class="font-semibold text-[#0F172A]">{{ $totalItems }}</span> hasil
    </div>

    <div class="flex items-center gap-1.5">
        <button
            type="button"
            {{ $current <= 1 ? 'disabled' : '' }}
            class="px-2.5 py-1.5 rounded-lg border border-[#E2E8F0] hover:bg-slate-50 disabled:opacity-40 disabled:cursor-not-allowed font-medium text-[#0F172A] transition-smooth flex items-center gap-1"
        >
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
            <span>Sebelumnya</span>
        </button>

        @for($p = 1; $p <= $total; $p++)
            <button
                type="button"
                class="w-8 h-8 rounded-lg flex items-center justify-center font-medium transition-smooth {{ $p == $current ? 'bg-[#2563EB] text-white font-semibold' : 'border border-[#E2E8F0] hover:bg-slate-50 text-[#0F172A]' }}"
            >
                {{ $p }}
            </button>
        @endfor

        <button
            type="button"
            {{ $current >= $total ? 'disabled' : '' }}
            class="px-2.5 py-1.5 rounded-lg border border-[#E2E8F0] hover:bg-slate-50 disabled:opacity-40 disabled:cursor-not-allowed font-medium text-[#0F172A] transition-smooth flex items-center gap-1"
        >
            <span>Selanjutnya</span>
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg>
        </button>
    </div>
</div>
