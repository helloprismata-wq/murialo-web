@props([
    'tabs' => [], // array of ['id' => 'tab1', 'label' => 'Label', 'count' => null]
    'default' => null,
])

@php
$defaultTab = $default ?? ($tabs[0]['id'] ?? '');
@endphp

<div x-data="{ currentTab: '{{ $defaultTab }}' }" class="w-full">
    <div class="border-b border-[#E2E8F0] mb-6">
        <nav class="-mb-px flex space-x-6 overflow-x-auto" aria-label="Tabs">
            @foreach($tabs as $tab)
                <button
                    type="button"
                    @click="currentTab = '{{ $tab['id'] }}'"
                    :class="currentTab === '{{ $tab['id'] }}' 
                        ? 'border-[#2563EB] text-[#2563EB] font-semibold' 
                        : 'border-transparent text-[#64748B] hover:text-[#0F172A] hover:border-slate-300 font-medium'"
                    class="whitespace-nowrap py-3 px-1 border-b-2 text-sm transition-smooth flex items-center gap-2 cursor-pointer focus:outline-none"
                >
                    <span>{{ $tab['label'] }}</span>
                    @if(isset($tab['count']) && $tab['count'] !== null)
                        <span
                            :class="currentTab === '{{ $tab['id'] }}' ? 'bg-[#EFF6FF] text-[#2563EB]' : 'bg-slate-100 text-[#64748B]'"
                            class="rounded-full px-2 py-0.5 text-xs font-semibold"
                        >
                            {{ $tab['count'] }}
                        </span>
                    @endif
                </button>
            @endforeach
        </nav>
    </div>

    <div>
        {{ $slot }}
    </div>
</div>
