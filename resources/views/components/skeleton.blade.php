@props([
    'type' => 'card', // card, table, text, avatar
    'rows' => 3,
])

<div {{ $attributes->merge(['class' => 'animate-pulse space-y-3']) }}>
    @if($type === 'card')
        <div class="h-44 bg-slate-200/80 rounded-2xl w-full"></div>
    @elseif($type === 'avatar')
        <div class="w-11 h-11 bg-slate-200/80 rounded-full"></div>
    @elseif($type === 'table')
        <div class="space-y-2.5">
            <div class="h-10 bg-slate-200/80 rounded-lg w-full"></div>
            @for($i = 0; $i < $rows; $i++)
                <div class="h-12 bg-slate-100 rounded-lg w-full"></div>
            @endfor
        </div>
    @else
        <div class="h-4 bg-slate-200/80 rounded-md w-3/4"></div>
        <div class="h-4 bg-slate-200/80 rounded-md w-1/2"></div>
        <div class="h-4 bg-slate-200/80 rounded-md w-5/6"></div>
    @endif
</div>
