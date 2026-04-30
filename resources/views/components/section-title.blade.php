@props(['title', 'subtitle' => null])

<div class="grid gap-4 md:grid-cols-[auto_1fr_auto] md:items-end">
    <div>
        <p class="text-xs font-bold uppercase tracking-[0.18em] text-[#b78a34]">Sunnah Sharee Ghar</p>
        <h2 class="mt-1 font-serif text-3xl font-bold leading-tight text-[#2f241f] md:text-4xl">{{ $title }}</h2>
    </div>
    <div class="hidden h-px bg-gradient-to-r from-[#d8b879] via-[#b78a34] to-transparent md:block"></div>
    @if ($subtitle)
        <p class="max-w-xs text-sm leading-6 text-[#6f5a50] md:text-right">{{ $subtitle }}</p>
    @endif
</div>
