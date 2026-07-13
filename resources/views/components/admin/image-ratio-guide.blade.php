@props([
    'ratio',
    'size',
    'usage',
    'shape' => 'square',
])

@php
    $frameClass = match ($shape) {
        'wide' => 'aspect-video w-28',
        'portrait' => 'aspect-[4/5] w-20',
        default => 'aspect-square w-20',
    };
@endphp

<div {{ $attributes->merge(['class' => 'mt-3 flex flex-wrap items-center gap-3 rounded-lg border border-[#eadcc3] bg-[#fffaf3] p-3 text-xs text-[#6f5a50]']) }}>
    <div class="{{ $frameClass }} shrink-0 overflow-hidden rounded-lg border border-dashed border-[#c9a24a] bg-white">
        <div class="grid h-full w-full place-items-center bg-[#fff7e8] font-bold text-[#8a155b]">
            {{ $ratio }}
        </div>
    </div>
    <div class="min-w-0">
        <p class="font-bold uppercase tracking-[0.14em] text-[#8a155b]">Recommended ratio: {{ $ratio }}</p>
        <p class="mt-1">Best size: {{ $size }}</p>
        <p class="mt-1 text-[#8d786d]">{{ $usage }}</p>
    </div>
</div>
