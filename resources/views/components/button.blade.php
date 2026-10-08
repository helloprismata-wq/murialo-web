@props([
    'variant' => 'primary', // primary, secondary, outline, ghost, danger, success
    'size' => 'md',        // sm, md, lg
    'type' => 'button',
    'href' => null,
    'disabled' => false,
    'icon' => null,
    'iconRight' => null,
])

@php
$baseClasses = 'inline-flex items-center justify-center font-medium rounded-lg transition-smooth focus:outline-none focus:ring-2 focus:ring-offset-1 select-none disabled:opacity-50 disabled:cursor-not-allowed disabled:pointer-events-none active:scale-[0.98]';

$sizeClasses = [
    'sm' => 'px-3 py-1.5 text-xs gap-1.5',
    'md' => 'px-4 py-2 text-sm gap-2',
    'lg' => 'px-5 py-2.5 text-base gap-2.5',
][$size] ?? 'px-4 py-2 text-sm gap-2';

$variantClasses = [
    'primary' => 'bg-[#2563EB] hover:bg-[#1D4ED8] text-white focus:ring-[#2563EB]/40 shadow-sm',
    'secondary' => 'bg-white hover:bg-slate-50 text-[#0F172A] border border-[#E2E8F0] focus:ring-slate-300 shadow-2xs',
    'outline' => 'bg-transparent hover:bg-[#EFF6FF] text-[#2563EB] border border-[#2563EB] focus:ring-[#2563EB]/30',
    'ghost' => 'bg-transparent hover:bg-slate-100 text-[#64748B] hover:text-[#0F172A] focus:ring-slate-300',
    'danger' => 'bg-[#EF4444] hover:bg-red-600 text-white focus:ring-red-500/40 shadow-sm',
    'success' => 'bg-[#10B981] hover:bg-emerald-600 text-white focus:ring-emerald-500/40 shadow-sm',
][$variant] ?? 'bg-[#2563EB] hover:bg-[#1D4ED8] text-white';

$classes = "{$baseClasses} {$sizeClasses} {$variantClasses}";
@endphp

@if($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>
        @if($icon)
            <span class="shrink-0">{!! $icon !!}</span>
        @endif
        <span>{{ $slot }}</span>
        @if($iconRight)
            <span class="shrink-0">{!! $iconRight !!}</span>
        @endif
    </a>
@else
    <button type="{{ $type }}" {{ $disabled ? 'disabled' : '' }} {{ $attributes->merge(['class' => $classes]) }}>
        @if($icon)
            <span class="shrink-0">{!! $icon !!}</span>
        @endif
        <span>{{ $slot }}</span>
        @if($iconRight)
            <span class="shrink-0">{!! $iconRight !!}</span>
        @endif
    </button>
@endif
