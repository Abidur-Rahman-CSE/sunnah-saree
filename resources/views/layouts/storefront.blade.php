<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'Sunnah Sharee Ghar' }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700|playfair-display:600,700" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="overflow-x-hidden bg-[#fffaf4] text-[#2f241f] antialiased">
    <div class="bg-[#8a155b] px-4 py-2 text-center text-xs font-semibold leading-5 tracking-wide text-white md:text-sm">
        Free delivery over ৳5,000 <span class="mx-2 text-[#f1d88a]">•</span> Cash on delivery available <span class="mx-2 text-[#f1d88a]">•</span> Easy return support
    </div>

    <header class="sticky top-0 z-40 border-b border-[#ead8ba] bg-[#fffaf4]/95 shadow-[0_10px_30px_rgba(122,31,85,0.06)] backdrop-blur">
        <div class="mx-auto grid max-w-7xl min-w-0 grid-cols-[auto_1fr_auto] items-center gap-3 px-4 py-3 lg:grid-cols-[220px_minmax(0,1fr)_320px] lg:gap-4 lg:py-4 xl:grid-cols-[260px_minmax(0,1fr)_360px]">
            <a href="{{ route('home') }}" class="flex shrink-0 items-center gap-3">
                <img src="{{ asset('images/Logo/sunnah_logo_bgr.png') }}" alt="Sunnah Sharee Ghar" class="h-11 w-auto object-contain sm:h-14 lg:h-20">
            </a>
            <form action="{{ route('products.index') }}" class="flex min-w-0 overflow-hidden rounded-lg border border-[#d8b879] bg-white shadow-inner">
                <input name="search" value="{{ request('search') }}" class="min-w-0 flex-1 px-3 py-2 text-xs outline-none sm:px-4 sm:py-3 sm:text-sm" placeholder="Search sharee...">
                <button class="shrink-0 bg-[#8a155b] px-3 text-xs font-semibold text-white transition hover:bg-[#6f1047] sm:px-5 sm:text-sm">Search</button>
            </form>
            <button type="button" class="grid h-10 w-10 place-items-center rounded-lg border border-[#dfcda9] bg-white text-[#7a1f55] shadow-sm lg:hidden" data-mobile-menu-toggle aria-expanded="false" aria-controls="mobile-storefront-menu">
                <span class="sr-only">Open menu</span>
                <span class="text-xl leading-none">☰</span>
            </button>
            <nav class="hidden min-w-0 grid-cols-5 gap-1 text-center text-[11px] font-semibold text-[#4f3d35] sm:gap-2 sm:text-xs lg:grid">
                <a href="{{ route('offers.index') }}" class="rounded-lg px-2 py-2 transition hover:bg-white hover:text-[#8a155b]"><span class="block text-lg">✧</span>Offers</a>
                <a href="{{ route('combos.index') }}" class="rounded-lg px-2 py-2 transition hover:bg-white hover:text-[#8a155b]"><span class="block text-lg">◇</span>Combos</a>
                <a href="{{ auth()->check() ? route('account.wishlist.index') : route('login') }}" class="rounded-lg px-2 py-2 transition hover:bg-white hover:text-[#8a155b]"><span class="block text-lg">♡</span>Wishlist</a>
                <a href="{{ auth()->check() ? route('account.dashboard') : route('login') }}" class="rounded-lg px-2 py-2 transition hover:bg-white hover:text-[#8a155b]"><span class="block text-lg">♙</span>Account</a>
                <a href="{{ route('cart.index') }}" class="rounded-lg px-2 py-2 text-[#8a155b] transition hover:bg-white"><span class="block text-lg">▱</span>Cart</a>
            </nav>
        </div>
        <nav class="mx-auto hidden max-w-7xl snap-x gap-3 overflow-x-auto px-4 pb-4 text-xs font-semibold uppercase tracking-wide text-[#5a463c] sm:gap-6 lg:flex">
            <a class="shrink-0 snap-start" href="{{ route('products.index') }}">All Products</a>
            <a class="shrink-0 snap-start" href="{{ route('products.index', ['category' => 'sharee']) }}">Sharee</a>
            <a class="shrink-0 snap-start" href="{{ route('products.index', ['sharee_type' => 'Katan Sharee']) }}">Katan</a>
            <a class="shrink-0 snap-start" href="{{ route('products.index', ['sharee_type' => 'Banarasi Sharee']) }}">Banarasi</a>
            <a class="shrink-0 snap-start" href="{{ route('products.index', ['sharee_type' => 'Bridal Sharee']) }}">Bridal</a>
            <a class="shrink-0 snap-start" href="{{ route('products.index', ['sharee_type' => 'Party Wear Sharee']) }}">Party Wear</a>
            <a class="shrink-0 snap-start" href="{{ route('products.index', ['sharee_type' => 'Daily Wear Sharee']) }}">Daily Wear</a>
            <a class="shrink-0 snap-start" href="{{ route('products.index', ['sort' => 'latest']) }}">New Arrivals</a>
            <a class="shrink-0 snap-start" href="{{ route('products.index') }}">Collections</a>
            <a class="shrink-0 snap-start" href="{{ route('pages.show', 'contact-us') }}">Contact</a>
        </nav>
        <div id="mobile-storefront-menu" class="hidden border-t border-[#ead8ba] bg-[#fffaf4] px-4 pb-4 lg:hidden" data-mobile-menu>
            <div class="grid grid-cols-2 gap-2 pt-4 text-sm font-semibold text-[#4f3d35]">
                <a href="{{ route('offers.index') }}" class="rounded-lg border border-[#ead8ba] bg-white px-3 py-3 text-center">Offers</a>
                <a href="{{ route('combos.index') }}" class="rounded-lg border border-[#ead8ba] bg-white px-3 py-3 text-center">Combos</a>
                <a href="{{ auth()->check() ? route('account.wishlist.index') : route('login') }}" class="rounded-lg border border-[#ead8ba] bg-white px-3 py-3 text-center">Wishlist</a>
                <a href="{{ auth()->check() ? route('account.dashboard') : route('login') }}" class="rounded-lg border border-[#ead8ba] bg-white px-3 py-3 text-center">Account</a>
                <a href="{{ route('cart.index') }}" class="rounded-lg border border-[#ead8ba] bg-white px-3 py-3 text-center text-[#8a155b]">Cart</a>
                <a href="{{ route('pages.show', 'contact-us') }}" class="rounded-lg border border-[#ead8ba] bg-white px-3 py-3 text-center">Contact</a>
            </div>
            <div class="mt-3 grid grid-cols-2 gap-2 text-xs font-bold uppercase tracking-wide text-[#5a463c]">
                <a href="{{ route('products.index') }}" class="rounded-lg bg-[#fff6e8] px-3 py-2 text-center">All Products</a>
                <a href="{{ route('products.index', ['category' => 'sharee']) }}" class="rounded-lg bg-[#fff6e8] px-3 py-2 text-center">Sharee</a>
                <a href="{{ route('products.index', ['sharee_type' => 'Katan Sharee']) }}" class="rounded-lg bg-[#fff6e8] px-3 py-2 text-center">Katan</a>
                <a href="{{ route('products.index', ['sharee_type' => 'Banarasi Sharee']) }}" class="rounded-lg bg-[#fff6e8] px-3 py-2 text-center">Banarasi</a>
                <a href="{{ route('products.index', ['sharee_type' => 'Bridal Sharee']) }}" class="rounded-lg bg-[#fff6e8] px-3 py-2 text-center">Bridal</a>
                <a href="{{ route('products.index', ['sharee_type' => 'Party Wear Sharee']) }}" class="rounded-lg bg-[#fff6e8] px-3 py-2 text-center">Party Wear</a>
                <a href="{{ route('products.index', ['sharee_type' => 'Daily Wear Sharee']) }}" class="rounded-lg bg-[#fff6e8] px-3 py-2 text-center">Daily Wear</a>
                <a href="{{ route('products.index', ['sort' => 'latest']) }}" class="rounded-lg bg-[#fff6e8] px-3 py-2 text-center">New Arrivals</a>
            </div>
        </div>
    </header>

    @if (session('status'))
        <div class="mx-auto mt-4 max-w-7xl px-4">
            <div class="rounded-lg border border-[#dfcda9] bg-white px-4 py-3 text-sm font-medium text-[#7a1f55]">{{ session('status') }}</div>
        </div>
    @endif

    <main>
        @yield('content')
    </main>

    <footer class="mt-16 border-t border-[#eadcc3] bg-white">
        <div class="mx-auto grid max-w-7xl gap-8 px-4 py-12 md:grid-cols-4">
            <div>
                <img src="{{ asset('images/Logo/sunnah_logo_bgr.png') }}" alt="Sunnah Sharee Ghar" class="h-16 w-auto object-contain">
                <p class="mt-3 text-sm text-[#6f5a50]">Premium sharee, modest gifts, ornaments, oils, cosmetics, and baby essentials for graceful homes.</p>
            </div>
            <div>
                <h3 class="font-semibold">Quick Links</h3>
                <div class="mt-3 grid gap-2 text-sm text-[#6f5a50]">
                    <a href="{{ route('products.index') }}">Shop</a>
                    <a href="{{ route('offers.index') }}">Offer Zone</a>
                    <a href="{{ route('combos.index') }}">Combo Deals</a>
                </div>
            </div>
            <div>
                <h3 class="font-semibold">Customer Care</h3>
                <div class="mt-3 grid gap-2 text-sm text-[#6f5a50]">
                    <a href="{{ route('pages.show', 'return-policy') }}">Return Policy</a>
                    <a href="{{ route('pages.show', 'shipping-policy') }}">Shipping Policy</a>
                    <a href="{{ route('pages.show', 'privacy-policy') }}">Privacy Policy</a>
                    <a href="{{ route('pages.show', 'terms-conditions') }}">Terms & Conditions</a>
                </div>
            </div>
            <form class="space-y-3">
                <h3 class="font-semibold">Newsletter</h3>
                <input class="w-full rounded-lg border border-[#dfcda9] px-4 py-3" placeholder="Email address">
                <button class="w-full rounded-lg bg-[#7a1f55] px-4 py-3 font-semibold text-white">Subscribe</button>
                <p class="text-xs text-[#6f5a50]">Payment: Cash on Delivery · Online gateway ready</p>
            </form>
        </div>
    </footer>
    <script>
        document.querySelectorAll('[data-mobile-menu-toggle]').forEach((button) => {
            button.addEventListener('click', () => {
                const menu = document.querySelector('[data-mobile-menu]');
                const isOpen = menu && !menu.classList.contains('hidden');

                menu?.classList.toggle('hidden', isOpen);
                button.setAttribute('aria-expanded', String(!isOpen));
            });
        });
    </script>
</body>
</html>
