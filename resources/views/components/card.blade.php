@props([
    'title' => null,
    'subtitle' => null,
    'action' => null,
    'footer' => null,
    'padding' => true,
])

<div {{ $attributes->merge(['class' => 'bg-white rounded-2xl border border-[#E2E8F0] shadow-2xs overflow-hidden']) }}>
    @if($title || $subtitle || $action)
        <div class="px-6 py-4.5 border-b border-[#E2E8F0] flex items-center justify-between gap-4">
            <div>
                @if($title)
                    <h3 class="text-base font-semibold text-[#0F172A] tracking-tight">{{ $title }}</h3>
                @endif
                @if($subtitle)
                    <p class="text-xs text-[#64748B] mt-0.5">{{ $subtitle }}</p>
                @endif
            </div>
            @if($action)
                <div class="shrink-0 flex items-center gap-2">
                    {{ $action }}
                </div>
            @endif
        </div>
    @endif

    <div class="{{ $padding ? 'p-6' : '' }}">
        {{ $slot }}
    </div>

    @if($footer)
        <div class="px-6 py-3.5 bg-slate-50/70 border-t border-[#E2E8F0] flex items-center justify-between text-xs text-[#64748B]">
            {{ $footer }}
        </div>
    @endif
</div>
