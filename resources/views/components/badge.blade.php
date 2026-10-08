@props([
    'variant' => 'neutral', // primary, success, warning, danger, neutral, info, purple
    'size' => 'md',        // sm, md, lg
    'dot' => false,
])

@php
$baseClasses = 'inline-flex items-center font-medium rounded-full select-none';

$sizeClasses = [
    'sm' => 'px-2 py-0.5 text-[11px] gap-1',
    'md' => 'px-2.5 py-1 text-xs gap-1.5',
    'lg' => 'px-3 py-1.5 text-sm gap-2',
][$size] ?? 'px-2.5 py-1 text-xs gap-1.5';

$variantClasses = [
    'primary' => 'bg-[#EFF6FF] text-[#1D4ED8] border border-[#BFDBFE]',
    'success' => 'bg-[#ECFDF5] text-[#047857] border border-[#A7F3D0]',
    'warning' => 'bg-[#FFFBEB] text-[#B45309] border border-[#FDE68A]',
    'danger'  => 'bg-[#FEF2F2] text-[#B91C1C] border border-[#FECACA]',
    'neutral' => 'bg-[#F1F5F9] text-[#475569] border border-[#E2E8F0]',
    'info'    => 'bg-[#F0F9FF] text-[#0369A1] border border-[#BAE6FD]',
    'purple'  => 'bg-[#FAF5FF] text-[#6B21A8] border border-[#E9D5FF]',
][$variant] ?? 'bg-[#F1F5F9] text-[#475569] border border-[#E2E8F0]';

$dotColor = [
    'primary' => 'bg-[#2563EB]',
    'success' => 'bg-[#10B981]',
    'warning' => 'bg-[#F59E0B]',
    'danger'  => 'bg-[#EF4444]',
    'neutral' => 'bg-[#64748B]',
    'info'    => 'bg-[#0284C7]',
    'purple'  => 'bg-[#9333EA]',
][$variant] ?? 'bg-[#64748B]';
@endphp

<span {{ $attributes->merge(['class' => "{$baseClasses} {$sizeClasses} {$variantClasses}"]) }}>
    @if($dot)
        <span class="w-1.5 h-1.5 rounded-full shrink-0 {{ $dotColor }}"></span>
    @endif
    <span>{{ $slot }}</span>
</span>
