@props(['label'])

<label {{ $attributes->merge(['class' => 'inline-flex items-center gap-2 text-sm font-semibold text-[#4b3a32]']) }}>
    {{ $slot }}
    <span>{{ $label }}</span>
</label>
