@props([
    'label' => null,
    'id' => null,
    'name' => null,
    'type' => 'text',
    'value' => null,
    'placeholder' => '',
    'helper' => null,
    'error' => null,
    'required' => false,
    'disabled' => false,
    'readonly' => false,
    'icon' => null,
])

@php
$id = $id ?? ($name ?? 'input-' . uniqid());
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
        @if($icon)
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                {!! $icon !!}
            </div>
        @endif

        <input
            id="{{ $id }}"
            name="{{ $name }}"
            type="{{ $type }}"
            value="{{ old($name, $value) }}"
            placeholder="{{ $placeholder }}"
            {{ $required ? 'required' : '' }}
            {{ $disabled ? 'disabled' : '' }}
            {{ $readonly ? 'readonly' : '' }}
            {{ $attributes->merge([
                'class' => 'w-full rounded-lg border bg-white px-3.5 py-2 text-sm text-[#0F172A] transition-smooth ' .
                           ($icon ? 'pl-9 ' : '') .
                           ($error ? 'border-red-400 focus:border-red-500 focus:ring-2 focus:ring-red-200 ' : 'border-[#E2E8F0] focus:border-[#2563EB] focus:ring-2 focus:ring-[#2563EB]/20 ') .
                           ($disabled ? 'bg-slate-50 text-slate-400 cursor-not-allowed ' : '') .
                           'placeholder:text-slate-400 outline-none'
            ]) }}
        />
    </div>

    @if($helper && !$error)
        <p class="text-xs text-[#64748B]">{{ $helper }}</p>
    @endif

    @if($error)
        <p class="text-xs font-medium text-red-600 flex items-center gap-1">
            <svg class="w-3.5 h-3.5 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
            </svg>
            <span>{{ $error }}</span>
        </p>
    @endif
</div>
