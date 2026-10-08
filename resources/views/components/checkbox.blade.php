@props([
    'label' => null,
    'id' => null,
    'name' => null,
    'checked' => false,
    'disabled' => false,
    'description' => null,
])

@php
$id = $id ?? ($name ?? 'checkbox-' . uniqid());
@endphp

<div class="relative flex items-start gap-2.5">
    <div class="flex h-5 items-center">
        <input
            id="{{ $id }}"
            name="{{ $name }}"
            type="checkbox"
            {{ $checked ? 'checked' : '' }}
            {{ $disabled ? 'disabled' : '' }}
            {{ $attributes->merge([
                'class' => 'h-4 w-4 rounded border-[#CBD5E1] text-[#2563EB] focus:ring-[#2563EB] transition-smooth cursor-pointer disabled:cursor-not-allowed'
            ]) }}
        />
    </div>
    @if($label || $slot->isNotEmpty())
        <div class="text-xs">
            <label for="{{ $id }}" class="font-medium text-[#0F172A] cursor-pointer select-none">
                {{ $label ?? $slot }}
            </label>
            @if($description)
                <p class="text-[#64748B] text-[11px] mt-0.5">{{ $description }}</p>
            @endif
        </div>
    @endif
</div>
