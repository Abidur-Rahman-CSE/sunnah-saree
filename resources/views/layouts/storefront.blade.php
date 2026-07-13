<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'Sunnah Sharee Ghar' }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700|playfair-display:600,700" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        @keyframes cart-reminder-shake {
            0%, 88%, 100% { transform: rotate(0deg) scale(1); }
            90% { transform: rotate(-8deg) scale(1.04); }
            92% { transform: rotate(7deg) scale(1.04); }
            94% { transform: rotate(-5deg) scale(1.03); }
            96% { transform: rotate(4deg) scale(1.02); }
        }

        .cart-has-items {
            animation: cart-reminder-shake 4.5s ease-in-out infinite;
            transform-origin: center;
        }

        @media (prefers-reduced-motion: reduce) {
            .cart-has-items {
                animation: none;
            }
        }
    </style>
</head>
<body class="overflow-x-hidden bg-[#fffaf4] text-[#2f241f] antialiased">
    @php
        $cartCount = 0;
        $cartSessionId = session('cart_session_id');
        $cartQuery = \App\Models\Cart::query()->withSum('items', 'quantity');

        if (auth()->check()) {
            $cartQuery->where('user_id', auth()->id());

            if ($cartSessionId) {
                $cartQuery->where('session_id', $cartSessionId);
            }
        } elseif ($cartSessionId) {
            $cartQuery->where('session_id', $cartSessionId);
        } else {
            $cartQuery = null;
        }

        $cartCount = $cartQuery ? (int) ($cartQuery->first()?->items_sum_quantity ?? 0) : 0;
        $shareeMenuItems = [
            'All Saree' => route('products.index', ['category' => 'sharee']),
            'Katan Saree' => route('products.index', ['sharee_type' => 'Katan Sharee']),
            'Chumki Saree' => route('products.index', ['sharee_type' => 'Chumki Sharee']),
            'Banarasi Saree' => route('products.index', ['sharee_type' => 'Banarasi Sharee']),
            'Cotton Saree' => route('products.index', ['sharee_type' => 'Cotton Sharee']),
            'Silk Saree' => route('products.index', ['sharee_type' => 'Silk Sharee']),
            'Bridal Saree' => route('products.index', ['sharee_type' => 'Bridal Sharee']),
            'Party Wear' => route('products.index', ['sharee_type' => 'Party Wear Sharee']),
            'Daily Wear' => route('products.index', ['sharee_type' => 'Daily Wear Sharee']),
        ];
        $navCategories = \App\Models\Category::query()
            ->where('is_active', true)
            ->whereNull('parent_id')
            ->with(['children' => fn ($query) => $query->where('is_active', true)->orderBy('name')])
            ->orderBy('name')
            ->get()
            ->reject(fn ($category) => $category->slug === 'sharee' || strtolower($category->name) === 'sharee');
        $isShareeMenuActive = request()->routeIs('products.index') && (request('category') === 'sharee' || request()->filled('sharee_type'));
        $isCategoriesMenuActive = request()->routeIs('categories.show') || (request()->routeIs('products.index') && request()->filled('category') && request('category') !== 'sharee');
        $isAllProductsActive = request()->routeIs('products.index') && ! request()->filled('category') && ! request()->filled('sharee_type') && request('sort') !== 'latest';
        $isNewArrivalsActive = request()->routeIs('products.index') && request('sort') === 'latest';
        $announcementBarText = \App\Models\Setting::valueFor('announcement_bar_text', 'Free delivery over ৳5,000 • Cash on delivery available • Easy return support');
        $announcementBarItems = collect(explode('•', $announcementBarText))
            ->map(fn (string $item): string => trim($item))
            ->filter()
            ->values();
        $mainMenuClass = 'relative rounded-full px-4 py-2.5 transition hover:bg-white hover:text-[#8a155b] hover:shadow-sm';
        $activeMainMenuClass = 'bg-white text-[#8a155b] shadow-sm ring-1 ring-[#ead8ba]';
        $dropdownPanelClass = 'invisible absolute left-1/2 top-full z-50 w-72 -translate-x-1/2 translate-y-2 rounded-lg border border-[#ead8ba] bg-white p-2 text-sm normal-case tracking-normal text-[#4f3d35] opacity-0 shadow-xl shadow-[#7a1f55]/10 transition group-hover:visible group-hover:translate-y-0 group-hover:opacity-100 group-focus-within:visible group-focus-within:translate-y-0 group-focus-within:opacity-100';
        $dropdownLinkClass = 'block rounded-lg px-3 py-2.5 transition hover:bg-[#fff6e8] hover:text-[#8a155b]';
        $storePhone = \App\Models\Setting::valueFor('phone', '01985902350');
        $storeWhatsapp = \App\Models\Setting::valueFor('whatsapp', $storePhone);
        $storeFacebookUrl = \App\Models\Setting::valueFor('facebook_page_link', 'https://www.facebook.com/sunnah.saree') ?: 'https://www.facebook.com/sunnah.saree';
        $storeMessengerUrl = 'https://m.me/sunnah.saree';
        $storeWhatsappDigits = preg_replace('/\D+/', '', (string) $storeWhatsapp);

        if (str_starts_with($storeWhatsappDigits, '0')) {
            $storeWhatsappDigits = '88'.$storeWhatsappDigits;
        }
    @endphp

    @if ($announcementBarItems->isNotEmpty())
        <div class="bg-[#8a155b] px-4 py-2 text-center text-xs font-semibold leading-5 tracking-wide text-white md:text-sm">
            @foreach ($announcementBarItems as $announcementBarItem)
                @if (! $loop->first)<span class="mx-2 text-[#f1d88a]">•</span>@endif
                <span>{{ $announcementBarItem }}</span>
            @endforeach
        </div>
    @endif

    <header class="sticky top-0 z-40 border-b border-[#ead8ba] bg-[#fffaf4]/95 shadow-[0_10px_30px_rgba(122,31,85,0.06)] backdrop-blur">
        <div class="mx-auto grid max-w-7xl min-w-0 grid-cols-[auto_minmax(0,1fr)_auto_auto] items-center gap-2 px-4 py-3 sm:gap-3 lg:grid-cols-[220px_minmax(0,1fr)_320px] lg:gap-4 lg:py-4 xl:grid-cols-[260px_minmax(0,1fr)_360px]">
            <a href="{{ route('home') }}" class="flex shrink-0 items-center gap-3">
                <img src="{{ asset('images/Logo/sunnah_logo_bgr.png') }}" alt="Sunnah Sharee Ghar" class="h-11 w-auto object-contain sm:h-14 lg:h-20">
            </a>
            <form action="{{ route('products.index') }}" class="flex min-w-0 overflow-hidden rounded-lg border border-[#d8b879] bg-white shadow-inner">
                <input name="search" value="{{ request('search') }}" class="min-w-0 flex-1 px-3 py-2 text-xs outline-none sm:px-4 sm:py-3 sm:text-sm" placeholder="Search sharee...">
                <button class="inline-flex shrink-0 items-center gap-2 bg-[#8a155b] px-3 text-xs font-semibold text-white transition hover:bg-[#6f1047] sm:px-5 sm:text-sm">
                    <x-storefront.icon name="search" class="h-4 w-4" />
                    <span class="hidden sm:inline">Search</span>
                </button>
            </form>
            <a href="{{ route('cart.index') }}" class="{{ $cartCount > 0 ? 'cart-has-items' : '' }} relative grid h-10 w-10 place-items-center rounded-lg border border-[#dfcda9] bg-white text-[#7a1f55] shadow-sm lg:hidden" data-cart-target>
                <span class="sr-only">Cart</span>
                <x-storefront.icon name="shopping-bag" class="h-5 w-5" />
                <span class="{{ $cartCount > 0 ? '' : 'hidden' }} absolute -right-2 -top-2 min-w-5 rounded-full bg-[#c9a24a] px-1.5 py-0.5 text-center text-[10px] font-bold leading-none text-white shadow" data-cart-count>{{ $cartCount }}</span>
            </a>
            <button type="button" class="grid h-10 w-10 place-items-center rounded-lg border border-[#dfcda9] bg-white text-[#7a1f55] shadow-sm lg:hidden" data-mobile-menu-toggle aria-expanded="false" aria-controls="mobile-storefront-menu">
                <span class="sr-only">Open menu</span>
                <x-storefront.icon name="bars" class="h-5 w-5" />
            </button>
            <nav class="hidden min-w-0 grid-cols-5 gap-1 text-center text-[11px] font-semibold text-[#4f3d35] sm:gap-2 sm:text-xs lg:grid">
                <a href="{{ route('offers.index') }}" class="grid justify-items-center gap-1 rounded-lg px-2 py-2 transition hover:bg-white hover:text-[#8a155b]"><x-storefront.icon name="sparkles" class="h-5 w-5" />Offers</a>
                <a href="{{ route('combos.index') }}" class="grid justify-items-center gap-1 rounded-lg px-2 py-2 transition hover:bg-white hover:text-[#8a155b]"><x-storefront.icon name="gift" class="h-5 w-5" />Combos</a>
                <a href="{{ auth()->check() ? route('account.wishlist.index') : route('login') }}" class="grid justify-items-center gap-1 rounded-lg px-2 py-2 transition hover:bg-white hover:text-[#8a155b]"><x-storefront.icon name="heart" class="h-5 w-5" />Wishlist</a>
                <a href="{{ auth()->check() ? route('account.dashboard') : route('login') }}" class="grid justify-items-center gap-1 rounded-lg px-2 py-2 transition hover:bg-white hover:text-[#8a155b]"><x-storefront.icon name="user" class="h-5 w-5" />Account</a>
                <a href="{{ route('cart.index') }}" class="{{ $cartCount > 0 ? 'cart-has-items' : '' }} relative grid justify-items-center gap-1 rounded-lg px-2 py-2 text-[#8a155b] transition hover:bg-white" data-cart-target>
                    <span class="relative">
                        <x-storefront.icon name="shopping-bag" class="h-5 w-5" />
                        <span class="{{ $cartCount > 0 ? '' : 'hidden' }} absolute -right-3 -top-2 min-w-5 rounded-full bg-[#c9a24a] px-1.5 py-0.5 text-center text-[10px] font-bold leading-none text-white shadow" data-cart-count>{{ $cartCount }}</span>
                    </span>
                    Cart
                </a>
            </nav>
        </div>
        <nav class="mx-auto hidden max-w-7xl items-center justify-center gap-3 px-4 pb-4 text-xs font-semibold uppercase tracking-wide text-[#5a463c] lg:flex xl:gap-5">
            <a class="{{ $mainMenuClass }} {{ $isAllProductsActive ? $activeMainMenuClass : '' }}" href="{{ route('products.index') }}">All Products</a>
            <div class="group relative">
                <a class="{{ $mainMenuClass }} {{ $isShareeMenuActive ? $activeMainMenuClass : '' }} inline-flex items-center gap-1.5" href="{{ route('products.index', ['category' => 'sharee']) }}">
                    Saree <span class="text-[10px]">▾</span>
                </a>
                <div class="{{ $dropdownPanelClass }} grid grid-cols-2 gap-1">
                    @foreach ($shareeMenuItems as $label => $url)
                        <a class="{{ $dropdownLinkClass }} {{ url()->current() === strtok($url, '?') && request()->fullUrl() === $url ? 'bg-[#fff6e8] font-bold text-[#8a155b]' : '' }}" href="{{ $url }}">{{ $label }}</a>
                    @endforeach
                </div>
            </div>
            <div class="group relative">
                <a class="{{ $mainMenuClass }} {{ $isCategoriesMenuActive ? $activeMainMenuClass : '' }} inline-flex items-center gap-1.5" href="{{ route('products.index') }}">
                    Categories <span class="text-[10px]">▾</span>
                </a>
                <div class="{{ $dropdownPanelClass }}">
                    @forelse ($navCategories as $category)
                        <a class="{{ $dropdownLinkClass }} font-semibold {{ request()->routeIs('categories.show') && request()->route('category')?->is($category) ? 'bg-[#fff6e8] text-[#8a155b]' : '' }}" href="{{ route('categories.show', $category) }}">{{ $category->name }}</a>
                        @foreach ($category->children as $childCategory)
                            <a class="{{ $dropdownLinkClass }} px-6 text-[#7a6a60] {{ request()->routeIs('categories.show') && request()->route('category')?->is($childCategory) ? 'bg-[#fff6e8] text-[#8a155b]' : '' }}" href="{{ route('categories.show', $childCategory) }}">{{ $childCategory->name }}</a>
                        @endforeach
                    @empty
                        <span class="block rounded-lg px-3 py-2 text-[#8d786d]">No categories available</span>
                    @endforelse
                </div>
            </div>
            <a class="{{ $mainMenuClass }} {{ $isNewArrivalsActive ? $activeMainMenuClass : '' }}" href="{{ route('products.index', ['sort' => 'latest']) }}">New Arrivals</a>
            <a class="{{ $mainMenuClass }} {{ request()->routeIs('offers.*') ? $activeMainMenuClass : '' }}" href="{{ route('offers.index') }}">Offers</a>
            <a class="{{ $mainMenuClass }} {{ request()->routeIs('combos.index') ? $activeMainMenuClass : '' }}" href="{{ route('combos.index') }}">Combos</a>
            <a class="{{ $mainMenuClass }} {{ request()->routeIs('pages.show') && request()->route('page') === 'contact-us' ? $activeMainMenuClass : '' }}" href="{{ route('pages.show', 'contact-us') }}">Contact</a>
        </nav>
        <div id="mobile-storefront-menu" class="hidden border-t border-[#ead8ba] bg-[#fffaf4] px-4 pb-4 lg:hidden" data-mobile-menu>
            <div class="grid grid-cols-2 gap-2 pt-4 text-sm font-semibold text-[#4f3d35]">
                <a href="{{ route('offers.index') }}" class="inline-flex items-center justify-center gap-2 rounded-lg border border-[#ead8ba] bg-white px-3 py-3 text-center"><x-storefront.icon name="sparkles" class="h-4 w-4" />Offers</a>
                <a href="{{ route('combos.index') }}" class="inline-flex items-center justify-center gap-2 rounded-lg border border-[#ead8ba] bg-white px-3 py-3 text-center"><x-storefront.icon name="gift" class="h-4 w-4" />Combos</a>
                <a href="{{ auth()->check() ? route('account.wishlist.index') : route('login') }}" class="inline-flex items-center justify-center gap-2 rounded-lg border border-[#ead8ba] bg-white px-3 py-3 text-center"><x-storefront.icon name="heart" class="h-4 w-4" />Wishlist</a>
                <a href="{{ auth()->check() ? route('account.dashboard') : route('login') }}" class="inline-flex items-center justify-center gap-2 rounded-lg border border-[#ead8ba] bg-white px-3 py-3 text-center"><x-storefront.icon name="user" class="h-4 w-4" />Account</a>
                <a href="{{ route('cart.index') }}" class="inline-flex items-center justify-center gap-2 rounded-lg border border-[#ead8ba] bg-white px-3 py-3 text-center text-[#8a155b]">
                    <span class="relative">
                        <x-storefront.icon name="shopping-bag" class="h-4 w-4" />
                        <span class="{{ $cartCount > 0 ? '' : 'hidden' }} absolute -right-3 -top-2 min-w-5 rounded-full bg-[#c9a24a] px-1.5 py-0.5 text-center text-[10px] font-bold leading-none text-white shadow" data-cart-count>{{ $cartCount }}</span>
                    </span>
                    Cart
                </a>
                <a href="{{ route('pages.show', 'contact-us') }}" class="inline-flex items-center justify-center gap-2 rounded-lg border border-[#ead8ba] bg-white px-3 py-3 text-center"><x-storefront.icon name="phone" class="h-4 w-4" />Contact</a>
            </div>
            <div class="mt-3 grid gap-2 text-xs font-bold uppercase tracking-wide text-[#5a463c]">
                <div class="grid grid-cols-2 gap-2">
                    <a href="{{ route('products.index') }}" class="rounded-lg px-3 py-2 text-center {{ $isAllProductsActive ? 'bg-[#8a155b] text-white' : 'bg-[#fff6e8]' }}">All Products</a>
                    <a href="{{ route('products.index', ['sort' => 'latest']) }}" class="rounded-lg px-3 py-2 text-center {{ $isNewArrivalsActive ? 'bg-[#8a155b] text-white' : 'bg-[#fff6e8]' }}">New Arrivals</a>
                </div>
                <details class="rounded-lg px-3 py-2 {{ $isShareeMenuActive ? 'bg-[#8a155b] text-white' : 'bg-[#fff6e8]' }}" @if($isShareeMenuActive) open @endif>
                    <summary class="cursor-pointer list-none text-center">Saree ▾</summary>
                    <div class="mt-2 grid grid-cols-2 gap-2 normal-case tracking-normal">
                        @foreach ($shareeMenuItems as $label => $url)
                            <a href="{{ $url }}" class="rounded-lg bg-white px-3 py-2 text-center text-[#5a463c]">{{ $label }}</a>
                        @endforeach
                    </div>
                </details>
                <details class="rounded-lg px-3 py-2 {{ $isCategoriesMenuActive ? 'bg-[#8a155b] text-white' : 'bg-[#fff6e8]' }}" @if($isCategoriesMenuActive) open @endif>
                    <summary class="cursor-pointer list-none text-center">Categories ▾</summary>
                    <div class="mt-2 grid gap-2 normal-case tracking-normal">
                        @forelse ($navCategories as $category)
                            <a href="{{ route('categories.show', $category) }}" class="rounded-lg bg-white px-3 py-2 text-center font-semibold text-[#5a463c]">{{ $category->name }}</a>
                            @foreach ($category->children as $childCategory)
                                <a href="{{ route('categories.show', $childCategory) }}" class="rounded-lg bg-white px-3 py-2 text-center text-[#7a6a60]">{{ $childCategory->name }}</a>
                            @endforeach
                        @empty
                            <span class="rounded-lg bg-white px-3 py-2 text-center text-[#8d786d]">No categories available</span>
                        @endforelse
                    </div>
                </details>
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
                <div class="mt-4 grid gap-2 text-sm font-semibold text-[#4f3d35]">
                    <a class="inline-flex items-center gap-2 transition hover:text-[#8a155b]" href="tel:{{ $storePhone }}">
                        <x-storefront.icon name="phone" class="h-4 w-4 text-[#8a155b]" />
                        {{ $storePhone }}
                    </a>
                    <a class="inline-flex items-center gap-2 transition hover:text-[#8a155b]" href="https://wa.me/{{ $storeWhatsappDigits }}" target="_blank" rel="noopener">
                        <x-storefront.icon name="whatsapp" class="h-4 w-4 text-[#18a957]" />
                        {{ $storeWhatsapp }}
                    </a>
                    <a class="inline-flex items-center gap-2 transition hover:text-[#8a155b]" href="{{ $storeFacebookUrl }}" target="_blank" rel="noopener">
                        <x-storefront.icon name="facebook" class="h-4 w-4 text-[#1877f2]" />
                        Facebook
                    </a>
                </div>
            </div>
            <div>
                <h3 class="font-semibold">Quick Links</h3>
                <div class="mt-3 grid gap-2 text-sm text-[#6f5a50]">
                    <a href="{{ route('products.index') }}">Shop</a>
                    <a href="{{ route('offers.index') }}">Offer Zone</a>
                    <a href="{{ route('combos.index') }}">Combo Deals</a>
                    <a href="{{ route('pages.show', 'about-us') }}">About Us</a>
                    <a href="{{ route('pages.show', 'contact-us') }}">Contact Us</a>
                </div>
            </div>
            <div>
                <h3 class="font-semibold">Customer Care</h3>
                <div class="mt-3 grid gap-2 text-sm text-[#6f5a50]">
                    <a href="{{ route('pages.show', 'return-policy') }}">Return Policy</a>
                    <a href="{{ route('pages.show', 'shipping-policy') }}">Shipping Policy</a>
                    <a href="{{ route('pages.show', 'terms-conditions') }}">Terms and Conditions</a>
                    <a href="{{ route('pages.show', 'privacy-policy') }}">Privacy Policy</a>
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

    <div class="fixed bottom-4 right-4 z-50 flex flex-col items-end sm:bottom-6 sm:right-6" data-chat-widget>
        <div class="mb-3 hidden w-[calc(100vw-2rem)] max-w-xs rounded-lg border border-[#ead8ba] bg-[#fffaf4] p-3 shadow-2xl shadow-[#7a1f55]/20 sm:w-80" data-chat-panel>
            <button type="button" class="absolute -top-3 right-2 grid h-8 w-8 place-items-center rounded-full border border-[#ead8ba] bg-white text-[#8d786d] shadow-lg transition hover:text-[#8a155b]" data-chat-close aria-label="Close chat">
                <span class="text-2xl leading-none">&times;</span>
            </button>
            <div class="text-center">
                <div class="mx-auto grid h-10 w-10 place-items-center rounded-full bg-white text-2xl shadow-sm ring-1 ring-[#ead8ba]">👋</div>
                <h2 class="mt-2 font-serif text-lg font-bold leading-snug text-[#2f241f]">যেকোনো জিজ্ঞাসায় সরাসরি আমাদের সাথে</h2>
            </div>
            <div class="mt-3 grid gap-2">
                <a href="{{ $storeMessengerUrl }}" target="_blank" rel="noopener" class="flex items-center gap-2.5 rounded-lg border border-[#ead8ba] bg-white p-2.5 text-left text-[#4f3d35] shadow-[0_2px_0_#ead8ba] transition hover:-translate-y-0.5 hover:border-[#8a155b]">
                    <span class="grid h-9 w-9 shrink-0 place-items-center rounded-full bg-[#fff6e8] text-[#8a155b]">
                        <x-storefront.icon name="messenger" class="h-5 w-5" />
                    </span>
                    <span class="text-sm font-extrabold leading-tight">Messenger-এ মেসেজ করুন</span>
                </a>
                <a href="https://wa.me/{{ $storeWhatsappDigits }}" target="_blank" rel="noopener" class="flex items-center gap-2.5 rounded-lg border border-[#ead8ba] bg-white p-2.5 text-left text-[#4f3d35] shadow-[0_2px_0_#ead8ba] transition hover:-translate-y-0.5 hover:border-[#8a155b]">
                    <span class="grid h-9 w-9 shrink-0 place-items-center rounded-full bg-[#effaf3] text-[#18a957]">
                        <x-storefront.icon name="whatsapp" class="h-5 w-5" />
                    </span>
                    <span class="text-sm font-extrabold leading-tight">WhatsApp-এ মেসেজ করুন</span>
                </a>
                <a href="tel:{{ $storePhone }}" class="flex items-center gap-2.5 rounded-lg border border-[#ead8ba] bg-white p-2.5 text-left text-[#4f3d35] shadow-[0_2px_0_#ead8ba] transition hover:-translate-y-0.5 hover:border-[#8a155b]">
                    <span class="grid h-9 w-9 shrink-0 place-items-center rounded-full bg-[#fff6e8] text-[#8a155b]">
                        <x-storefront.icon name="phone" class="h-5 w-5" />
                    </span>
                    <span class="text-sm font-extrabold leading-tight">কল করুন {{ $storePhone }}</span>
                </a>
                <a href="{{ $storeFacebookUrl }}" target="_blank" rel="noopener" class="inline-flex items-center justify-center gap-2 rounded-lg border border-[#ead8ba] bg-[#2f241f] px-3 py-2 text-sm font-bold text-white shadow-lg transition hover:bg-[#8a155b]">
                    <x-storefront.icon name="facebook" class="h-4 w-4" />
                    Facebook Page
                </a>
            </div>
        </div>
        <button type="button" class="inline-flex min-w-[11rem] items-center justify-center gap-2 rounded-l-full rounded-br-none rounded-tr-full bg-gradient-to-r from-[#7a1f55] to-[#a3166b] px-3 py-2 text-sm font-extrabold text-white shadow-xl shadow-[#7a1f55]/25 ring-1 ring-[#d8b879]/40 transition hover:-translate-y-0.5 hover:shadow-2xl sm:min-w-[14rem] sm:px-5 sm:py-3 sm:text-base" data-chat-toggle aria-expanded="false">
            <span class="grid h-7 w-7 place-items-center rounded-full bg-white/15 sm:h-8 sm:w-8" data-chat-phone-icon>
                <x-storefront.icon name="phone" class="h-4 w-4 sm:h-5 sm:w-5" />
            </span>
            <span data-chat-toggle-text>সরাসরি কথা বলুন</span>
            <span class="hidden text-xl leading-none" data-chat-toggle-arrow>∨</span>
        </button>
    </div>

    <script>
        document.querySelectorAll('[data-mobile-menu-toggle]').forEach((button) => {
            button.addEventListener('click', () => {
                const menu = document.querySelector('[data-mobile-menu]');
                const isOpen = menu && !menu.classList.contains('hidden');

                menu?.classList.toggle('hidden', isOpen);
                button.setAttribute('aria-expanded', String(!isOpen));
            });
        });

        const updateCartCount = (count) => {
            const hasItems = Number(count) > 0;

            document.querySelectorAll('[data-cart-count]').forEach((badge) => {
                badge.textContent = count;
                badge.classList.toggle('hidden', ! hasItems);
            });

            document.querySelectorAll('[data-cart-target]').forEach((target) => {
                target.classList.toggle('cart-has-items', hasItems);
            });
        };

        const visibleCartTarget = () => [...document.querySelectorAll('[data-cart-target]')]
            .find((target) => {
                const rect = target.getBoundingClientRect();

                return rect.width > 0 && rect.height > 0;
            });

        const animateCartPill = (source, quantity) => {
            const target = visibleCartTarget();

            if (! source || ! target) {
                return;
            }

            const sourceRect = source.getBoundingClientRect();
            const targetRect = target.getBoundingClientRect();
            const pill = document.createElement('span');

            pill.textContent = `+${quantity}`;
            pill.className = 'pointer-events-none fixed z-[100] grid h-7 min-w-7 place-items-center rounded-full bg-[#c9a24a] px-2 text-xs font-bold text-white shadow-lg transition-all duration-700 ease-out';
            pill.style.left = `${sourceRect.left + sourceRect.width / 2 - 14}px`;
            pill.style.top = `${sourceRect.top + sourceRect.height / 2 - 14}px`;

            document.body.appendChild(pill);

            requestAnimationFrame(() => {
                pill.style.transform = `translate(${targetRect.left + targetRect.width / 2 - sourceRect.left - sourceRect.width / 2}px, ${targetRect.top + targetRect.height / 2 - sourceRect.top - sourceRect.height / 2}px) scale(0.45)`;
                pill.style.opacity = '0.25';
            });

            window.setTimeout(() => {
                pill.remove();
                target.classList.add('scale-110');
                window.setTimeout(() => target.classList.remove('scale-110'), 180);
            }, 720);
        };

        document.querySelectorAll('[data-add-to-cart-form]').forEach((form) => {
            form.addEventListener('submit', async (event) => {
                if (! event.submitter?.hasAttribute('data-add-to-cart-submit')) {
                    return;
                }

                event.preventDefault();

                const submitter = event.submitter;
                const originalText = submitter.textContent;
                const formData = new FormData(form);

                submitter.disabled = true;
                submitter.textContent = 'Adding...';

                try {
                    const response = await fetch(form.action, {
                        method: 'POST',
                        headers: {
                            Accept: 'application/json',
                            'X-Requested-With': 'XMLHttpRequest',
                        },
                        body: formData,
                    });

                    if (! response.ok) {
                        throw new Error('Cart request failed.');
                    }

                    const payload = await response.json();

                    updateCartCount(payload.cart_count);
                    animateCartPill(submitter, payload.added_quantity || formData.get('quantity') || 1);
                    submitter.textContent = 'Added';
                    window.setTimeout(() => {
                        submitter.textContent = originalText;
                        submitter.disabled = false;
                    }, 900);
                } catch (error) {
                    form.submit();
                }
            });
        });

        document.querySelectorAll('[data-chat-widget]').forEach((widget) => {
            const panel = widget.querySelector('[data-chat-panel]');
            const toggle = widget.querySelector('[data-chat-toggle]');
            const close = widget.querySelector('[data-chat-close]');
            const phoneIcon = widget.querySelector('[data-chat-phone-icon]');
            const toggleText = widget.querySelector('[data-chat-toggle-text]');
            const toggleArrow = widget.querySelector('[data-chat-toggle-arrow]');

            const setChatOpen = (isOpen) => {
                panel?.classList.toggle('hidden', ! isOpen);
                toggle?.setAttribute('aria-expanded', String(isOpen));
                toggle?.classList.toggle('min-w-[11rem]', ! isOpen);
                toggle?.classList.toggle('sm:min-w-[14rem]', ! isOpen);
                toggle?.classList.toggle('w-11', isOpen);
                toggle?.classList.toggle('h-11', isOpen);
                toggle?.classList.toggle('sm:w-12', isOpen);
                toggle?.classList.toggle('sm:h-12', isOpen);
                toggle?.classList.toggle('px-3', ! isOpen);
                toggle?.classList.toggle('py-2', ! isOpen);
                toggle?.classList.toggle('sm:px-5', ! isOpen);
                toggle?.classList.toggle('sm:py-3', ! isOpen);
                toggle?.classList.toggle('p-0', isOpen);
                phoneIcon?.classList.toggle('hidden', isOpen);
                toggleText?.classList.toggle('hidden', isOpen);
                toggleArrow?.classList.toggle('hidden', ! isOpen);
            };

            toggle?.addEventListener('click', () => {
                setChatOpen(panel?.classList.contains('hidden') ?? true);
            });

            close?.addEventListener('click', () => setChatOpen(false));

            document.addEventListener('click', (event) => {
                if (panel?.classList.contains('hidden') || widget.contains(event.target)) {
                    return;
                }

                setChatOpen(false);
            });

            document.addEventListener('keydown', (event) => {
                if (event.key === 'Escape') {
                    setChatOpen(false);
                }
            });
        });
    </script>
</body>
</html>
