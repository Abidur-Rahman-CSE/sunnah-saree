@props([
    'name',
    'class' => 'h-5 w-5',
])

@switch($name)
    @case('bars')
        <svg {{ $attributes->merge(['class' => $class]) }} aria-hidden="true" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
            <path d="M4 6h16" />
            <path d="M4 12h16" />
            <path d="M4 18h16" />
        </svg>
        @break

    @case('gift')
        <svg {{ $attributes->merge(['class' => $class]) }} aria-hidden="true" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
            <path d="M20 12v8a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2v-8" />
            <path d="M2 7h20v5H2z" />
            <path d="M12 22V7" />
            <path d="M12 7H7.5a2.5 2.5 0 1 1 2.1-3.86C10.5 4.53 12 7 12 7z" />
            <path d="M12 7h4.5a2.5 2.5 0 1 0-2.1-3.86C13.5 4.53 12 7 12 7z" />
        </svg>
        @break

    @case('sparkles')
        <svg {{ $attributes->merge(['class' => $class]) }} aria-hidden="true" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
            <path d="M12 3l1.7 5.3L19 10l-5.3 1.7L12 17l-1.7-5.3L5 10l5.3-1.7L12 3z" />
            <path d="M19 16l.8 2.2L22 19l-2.2.8L19 22l-.8-2.2L16 19l2.2-.8L19 16z" />
            <path d="M5 2l.7 1.8L7.5 4.5l-1.8.7L5 7l-.7-1.8-1.8-.7 1.8-.7L5 2z" />
        </svg>
        @break

    @case('heart')
        <svg {{ $attributes->merge(['class' => $class]) }} aria-hidden="true" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
            <path d="M20.8 4.6a5.4 5.4 0 0 0-7.6 0L12 5.8l-1.2-1.2a5.4 5.4 0 0 0-7.6 7.6L12 21l8.8-8.8a5.4 5.4 0 0 0 0-7.6z" />
        </svg>
        @break

    @case('user')
        <svg {{ $attributes->merge(['class' => $class]) }} aria-hidden="true" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
            <path d="M20 21a8 8 0 0 0-16 0" />
            <circle cx="12" cy="7" r="4" />
        </svg>
        @break

    @case('shopping-bag')
        <svg {{ $attributes->merge(['class' => $class]) }} aria-hidden="true" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
            <path d="M6 8h12l-1 13H7L6 8z" />
            <path d="M9 8a3 3 0 0 1 6 0" />
        </svg>
        @break

    @case('phone')
        <svg {{ $attributes->merge(['class' => $class]) }} aria-hidden="true" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
            <path d="M22 16.9v3a2 2 0 0 1-2.2 2 19.8 19.8 0 0 1-8.6-3.1 19.5 19.5 0 0 1-6-6A19.8 19.8 0 0 1 2.1 4.2 2 2 0 0 1 4.1 2h3a2 2 0 0 1 2 1.7c.1 1 .4 2 .7 2.9a2 2 0 0 1-.4 2.1L8.1 10a16 16 0 0 0 6 6l1.3-1.3a2 2 0 0 1 2.1-.4c.9.3 1.9.6 2.9.7a2 2 0 0 1 1.6 1.9z" />
        </svg>
        @break

    @case('thread')
        <svg {{ $attributes->merge(['class' => $class]) }} aria-hidden="true" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
            <path d="M7 3h10l-2 18H9L7 3z" />
            <path d="M8 7h8" />
            <path d="M8 11h8" />
            <path d="M8 15h8" />
            <path d="M5 21h14" />
        </svg>
        @break

    @case('wallet')
        <svg {{ $attributes->merge(['class' => $class]) }} aria-hidden="true" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
            <path d="M3 7a2 2 0 0 1 2-2h13a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V7z" />
            <path d="M16 12h5" />
            <path d="M17.5 12h.01" />
            <path d="M6 5l9-2 2 2" />
        </svg>
        @break

    @case('rotate-left')
        <svg {{ $attributes->merge(['class' => $class]) }} aria-hidden="true" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
            <path d="M3 7v6h6" />
            <path d="M4.5 17A8 8 0 1 0 5 7.6L3 13" />
        </svg>
        @break

    @case('headphones')
        <svg {{ $attributes->merge(['class' => $class]) }} aria-hidden="true" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
            <path d="M4 14v-2a8 8 0 0 1 16 0v2" />
            <path d="M4 14a2 2 0 0 1 2-2h1v7H6a2 2 0 0 1-2-2v-3z" />
            <path d="M20 14a2 2 0 0 0-2-2h-1v7h1a2 2 0 0 0 2-2v-3z" />
            <path d="M15 19a3 3 0 0 1-3 2h-1" />
        </svg>
        @break

    @case('search')
        <svg {{ $attributes->merge(['class' => $class]) }} aria-hidden="true" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
            <circle cx="11" cy="11" r="7" />
            <path d="m20 20-3.5-3.5" />
        </svg>
        @break
@endswitch
