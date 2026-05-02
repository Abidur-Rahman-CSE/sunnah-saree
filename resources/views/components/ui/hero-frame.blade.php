@props(['class' => ''])

<img src="{{ asset('assets/decorations/hero-frame.svg') }}"
     alt=""
     aria-hidden="true"
     {{ $attributes->merge(['class' => 'absolute inset-4 pointer-events-none opacity-65 h-[calc(100%-2rem)] w-[calc(100%-2rem)] '.$class]) }}>
