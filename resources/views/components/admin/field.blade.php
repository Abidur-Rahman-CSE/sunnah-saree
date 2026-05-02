@props(['label', 'span' => false])

<label {{ $attributes->merge(['class' => 'grid gap-1.5 text-sm font-semibold text-[#4b3a32]'.($span ? ' md:col-span-2' : '')]) }}>
    <span>{{ $label }}</span>
    {{ $slot }}
</label>
