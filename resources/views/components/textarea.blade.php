@props([
    'label' => null,
    'id' => null,
    'name' => null,
    'value' => null,
    'placeholder' => '',
    'rows' => 4,
    'helper' => null,
    'error' => null,
    'required' => false,
    'disabled' => false,
])

@php
$id = $id ?? ($name ?? 'textarea-' . uniqid());
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

    <textarea
        id="{{ $id }}"
        name="{{ $name }}"
        rows="{{ $rows }}"
        placeholder="{{ $placeholder }}"
        {{ $required ? 'required' : '' }}
        {{ $disabled ? 'disabled' : '' }}
        {{ $attributes->merge([
            'class' => 'w-full rounded-lg border bg-white px-3.5 py-2.5 text-sm text-[#0F172A] transition-smooth ' .
                       ($error ? 'border-red-400 focus:border-red-500 focus:ring-2 focus:ring-red-200 ' : 'border-[#E2E8F0] focus:border-[#2563EB] focus:ring-2 focus:ring-[#2563EB]/20 ') .
                       ($disabled ? 'bg-slate-50 text-slate-400 cursor-not-allowed ' : '') .
                       'placeholder:text-slate-400 outline-none leading-relaxed'
        ]) }}
    >{{ old($name, $value ?? $slot) }}</textarea>

    @if($helper && !$error)
        <p class="text-xs text-[#64748B]">{{ $helper }}</p>
    @endif

    @if($error)
        <p class="text-xs font-medium text-red-600">{{ $error }}</p>
    @endif
</div>
