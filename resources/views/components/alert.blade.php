@props([
    'variant' => 'info', // success, warning, danger, info
    'title' => null,
    'dismissible' => false,
])

@php
$config = [
    'success' => [
        'bg' => 'bg-[#ECFDF5]',
        'border' => 'border-[#A7F3D0]',
        'text' => 'text-[#065F46]',
        'icon' => '<svg class="w-5 h-5 text-[#059669] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>',
    ],
    'warning' => [
        'bg' => 'bg-[#FFFBEB]',
        'border' => 'border-[#FDE68A]',
        'text' => 'text-[#92400E]',
        'icon' => '<svg class="w-5 h-5 text-[#D97706] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>',
    ],
    'danger' => [
        'bg' => 'bg-[#FEF2F2]',
        'border' => 'border-[#FECACA]',
        'text' => 'text-[#991B1B]',
        'icon' => '<svg class="w-5 h-5 text-[#DC2626] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>',
    ],
    'info' => [
        'bg' => 'bg-[#EFF6FF]',
        'border' => 'border-[#BFDBFE]',
        'text' => 'text-[#1E40AF]',
        'icon' => '<svg class="w-5 h-5 text-[#2563EB] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>',
    ],
][$variant] ?? [
    'bg' => 'bg-[#EFF6FF]',
    'border' => 'border-[#BFDBFE]',
    'text' => 'text-[#1E40AF]',
    'icon' => '<svg class="w-5 h-5 text-[#2563EB] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>',
];
@endphp

<div
    x-data="{ show: true }"
    x-show="show"
    x-transition:leave="transition ease-in duration-150"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
    role="alert"
    {{ $attributes->merge(['class' => "p-4 rounded-xl border {$config['bg']} {$config['border']} {$config['text']} flex items-start gap-3 text-sm"]) }}
>
    {!! $config['icon'] !!}

    <div class="flex-1 space-y-0.5">
        @if($title)
            <h4 class="font-semibold text-sm leading-tight">{{ $title }}</h4>
        @endif
        <div class="text-xs leading-relaxed opacity-95">
            {{ $slot }}
        </div>
    </div>

    @if($dismissible)
        <button
            type="button"
            @click="show = false"
            class="shrink-0 p-1 rounded-md opacity-70 hover:opacity-100 hover:bg-black/5 transition-smooth"
            aria-label="Tutup"
        >
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    @endif
</div>
