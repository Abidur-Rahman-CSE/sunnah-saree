@props(['class' => ''])

<div {{ $attributes->merge(['class' => 'absolute inset-0 pointer-events-none opacity-[0.04] '.$class]) }}
     style="background-image: url('{{ asset('assets/decorations/jali-pattern.svg') }}'); background-size: 240px 240px;">
</div>
