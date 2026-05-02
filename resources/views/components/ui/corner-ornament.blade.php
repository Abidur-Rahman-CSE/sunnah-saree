@props(['position' => 'top-left', 'class' => ''])

@php
    $positions = [
        'top-left' => 'left-4 top-4',
        'top-right' => 'right-4 top-4 rotate-90',
        'bottom-right' => 'bottom-4 right-4 rotate-180',
        'bottom-left' => 'bottom-4 left-4 -rotate-90',
    ];
@endphp

<img src="{{ asset('assets/decorations/corner-ornament.svg') }}"
     alt=""
     aria-hidden="true"
     {{ $attributes->merge(['class' => 'absolute pointer-events-none opacity-50 h-24 w-24 '.$positions[$position].' '.$class]) }}>
