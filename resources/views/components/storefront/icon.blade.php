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

    @case('message-circle')
        <svg {{ $attributes->merge(['class' => $class]) }} aria-hidden="true" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
            <path d="M21 11.5a8.4 8.4 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.4 8.4 0 0 1-3.8-.9L3 21l1.9-5.7a8.4 8.4 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.4 8.4 0 0 1 3.8-.9h.5A8.5 8.5 0 0 1 21 11v.5z" />
        </svg>
        @break

    @case('whatsapp')
        <svg {{ $attributes->merge(['class' => $class]) }} aria-hidden="true" viewBox="0 0 24 24" fill="currentColor">
            <path d="M12.04 2a9.9 9.9 0 0 0-8.45 15.08L2.4 21.5l4.55-1.16A9.96 9.96 0 1 0 12.04 2Zm0 1.8a8.14 8.14 0 0 1 6.9 12.46l-.22.35.7 2.58-2.66-.68-.34.2A8.14 8.14 0 0 1 4.6 8.18a8.08 8.08 0 0 1 7.44-4.38Zm-3.5 4.28c-.18 0-.46.07-.7.34-.24.26-.92.9-.92 2.19s.94 2.54 1.07 2.71c.13.18 1.82 2.92 4.5 3.98 2.22.87 2.68.7 3.16.66.48-.04 1.55-.63 1.77-1.24.22-.6.22-1.12.15-1.23-.06-.1-.24-.17-.5-.3s-1.55-.77-1.8-.85c-.24-.09-.42-.13-.6.13-.17.26-.68.85-.84 1.03-.15.17-.31.2-.57.06-.26-.13-1.1-.4-2.1-1.3-.78-.69-1.3-1.54-1.45-1.8-.16-.26-.02-.4.11-.53.12-.12.26-.31.4-.46.13-.15.17-.26.26-.44.09-.17.04-.32-.02-.46-.07-.13-.6-1.45-.82-1.98-.22-.52-.44-.45-.6-.46h-.5Z" />
        </svg>
        @break

    @case('messenger')
        <svg {{ $attributes->merge(['class' => $class]) }} aria-hidden="true" viewBox="0 0 24 24" fill="currentColor">
            <path d="M12 2C6.49 2 2 6.14 2 11.23c0 2.9 1.45 5.49 3.72 7.18V22l3.4-1.87c.91.25 1.88.38 2.88.38 5.51 0 10-4.14 10-9.28S17.51 2 12 2Zm1 12.45-2.55-2.72-4.98 2.72 5.47-5.8 2.61 2.72 4.92-2.72L13 14.45Z" />
        </svg>
        @break

    @case('facebook')
        <svg {{ $attributes->merge(['class' => $class]) }} aria-hidden="true" viewBox="0 0 24 24" fill="currentColor">
            <path d="M22 12.06C22 6.5 17.52 2 12 2S2 6.5 2 12.06c0 5.02 3.66 9.18 8.44 9.94v-7.03H7.9v-2.9h2.54V9.84c0-2.52 1.49-3.91 3.77-3.91 1.09 0 2.23.2 2.23.2v2.46h-1.25c-1.23 0-1.62.77-1.62 1.56v1.87h2.76l-.44 2.9h-2.32V22C18.34 21.24 22 17.08 22 12.06Z" />
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
