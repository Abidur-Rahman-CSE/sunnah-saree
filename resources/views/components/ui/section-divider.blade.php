@props(['class' => ''])

<img src="{{ asset('assets/decorations/section-divider.svg') }}"
     alt=""
     aria-hidden="true"
     {{ $attributes->merge(['class' => 'mx-auto mt-3 h-6 w-64 object-contain '.$class]) }}>
