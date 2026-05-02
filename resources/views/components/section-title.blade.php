@props(['title', 'subtitle' => null, 'eyebrow' => 'Sunnah Sharee Ghar'])

<div class="grid gap-3 md:grid-cols-[minmax(0,auto)_1fr_minmax(220px,320px)] md:items-center">
    <div class="min-w-0">
        <p class="text-[11px] font-bold uppercase tracking-[0.2em] text-[#b78a34]">{{ $eyebrow }}</p>
        <h2 class="mt-1 font-serif text-3xl font-bold leading-[1.12] text-[#2f241f] md:text-[2.15rem]">{{ $title }}</h2>
    </div>
    <div class="hidden items-center gap-3 md:flex">
        <span class="h-px flex-1 bg-gradient-to-r from-[#d8b879] via-[#b78a34] to-transparent"></span>
        <x-ui.section-divider class="mt-0 h-4 w-28 opacity-80" />
        <span class="h-px flex-1 bg-gradient-to-l from-[#d8b879] via-[#b78a34] to-transparent"></span>
    </div>
    @if ($subtitle)
        <p class="max-w-xs text-sm leading-6 text-[#6f5a50] md:text-right">{{ $subtitle }}</p>
    @endif
</div>
<x-ui.section-divider class="mx-0 md:hidden" />
