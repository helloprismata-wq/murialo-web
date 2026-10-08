@props([
    'label' => null,
    'id' => null,
    'name' => null,
    'helper' => null,
    'error' => null,
    'required' => false,
    'disabled' => false,
])

@php
$id = $id ?? ($name ?? 'select-' . uniqid());
@endphp

<div class="space-y-1.5 w-full">
    @if($label)
        <label for="{{ $id }}" class="block text-xs font-semibold text-[#0F172A] tracking-wide">
            {{ $label }}
            @if($required)
                <span class="text-red-500 font-bold">*</span>
            @endif
        </label>
    @endif

    <div class="relative rounded-lg shadow-2xs">
        <select
            id="{{ $id }}"
            name="{{ $name }}"
            {{ $required ? 'required' : '' }}
            {{ $disabled ? 'disabled' : '' }}
            {{ $attributes->merge([
                'class' => 'w-full appearance-none rounded-lg border bg-white px-3.5 py-2 pr-9 text-sm text-[#0F172A] transition-smooth ' .
                           ($error ? 'border-red-400 focus:border-red-500 focus:ring-2 focus:ring-red-200 ' : 'border-[#E2E8F0] focus:border-[#2563EB] focus:ring-2 focus:ring-[#2563EB]/20 ') .
                           ($disabled ? 'bg-slate-50 text-slate-400 cursor-not-allowed ' : '') .
                           'outline-none'
            ]) }}
        >
            {{ $slot }}
        </select>
        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2.5 text-slate-400">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
            </svg>
        </div>
    </div>

    @if($helper && !$error)
        <p class="text-xs text-[#64748B]">{{ $helper }}</p>
    @endif

    @if($error)
        <p class="text-xs font-medium text-red-600">{{ $error }}</p>
    @endif
</div>
