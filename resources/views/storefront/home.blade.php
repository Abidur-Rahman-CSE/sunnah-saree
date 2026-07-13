@extends('layouts.storefront', ['title' => 'Sunnah Sharee Ghar'])

@section('content')
    @php
        $shareeTypes = [
            'Katan Sharee',
            'Chumki Sharee',
            'Banarasi Sharee',
            'Silk Sharee',
            'Cotton Sharee',
            'Bridal Sharee',
            'Party Wear Sharee',
            'Daily Wear Sharee',
        ];

        $essentials = [
            [
                'name' => 'Organic Oil',
                'slug' => 'organic-oil',
                'subtitle' => 'Natural care for everyday wellness.',
                'image' => 'https://images.unsplash.com/photo-1608571423902-eed4a5ad8108?auto=format&fit=crop&w=900&q=80',
            ],
            [
                'name' => 'Ornaments',
                'slug' => 'ornaments',
                'subtitle' => 'Elegant pieces to complete your look.',
                'image' => 'https://images.unsplash.com/photo-1515562141207-7a88fb7ce338?auto=format&fit=crop&w=900&q=80',
            ],
            [
                'name' => 'Cosmetics',
                'slug' => 'cosmetics',
                'subtitle' => 'Beauty essentials for graceful moments.',
                'image' => 'https://images.unsplash.com/photo-1522335789203-aabd1fc54bc9?auto=format&fit=crop&w=900&q=80',
            ],
            [
                'name' => 'Baby Products',
                'slug' => 'baby-products',
                'subtitle' => 'Soft, safe picks for little ones.',
                'image' => 'https://images.unsplash.com/photo-1515488042361-ee00e0ddd4e4?auto=format&fit=crop&w=900&q=80',
            ],
        ];
        $activeCategorySlugs = $categories->pluck('slug')->all();
        $essentials = collect($essentials)
            ->filter(fn (array $essential): bool => in_array($essential['slug'], $activeCategorySlugs, true))
            ->values();

        $collectionImages = [
            'Eid Collection' => 'https://images.unsplash.com/photo-1607860108855-64acf2078ed9?auto=format&fit=crop&w=900&q=80',
            'Wedding Collection' => 'https://images.unsplash.com/photo-1594736797933-d0501ba2fe65?auto=format&fit=crop&w=900&q=80',
            'Bridal Collection' => 'https://images.unsplash.com/photo-1601121141461-9d6647bca1ed?auto=format&fit=crop&w=900&q=80',
            'Gift Collection' => 'https://images.unsplash.com/photo-1513201099705-a9746e1e201f?auto=format&fit=crop&w=900&q=80',
            'Budget Collection' => 'https://images.unsplash.com/photo-1595341595379-cf1cd0fb7fb3?auto=format&fit=crop&w=900&q=80',
            'Premium Collection' => 'https://images.unsplash.com/photo-1610030469983-98e550d6193c?auto=format&fit=crop&w=900&q=80',
        ];

        $trustCards = [
            ['title' => 'Premium Fabrics', 'copy' => 'Sourced with care', 'image' => asset('images/icons/pattern.png')],
            ['title' => 'Authentic Weaves', 'copy' => 'Traditional craftsmanship', 'icon' => 'thread'],
            ['title' => 'Elegant Packaging', 'copy' => 'Gift-ready every time', 'icon' => 'gift'],
            ['title' => 'Cash on Delivery', 'copy' => 'Pay when you receive', 'icon' => 'wallet'],
            ['title' => 'Easy Returns', 'copy' => 'Hassle-free process', 'icon' => 'rotate-left'],
            ['title' => 'Customer Support', 'copy' => 'We are here to help', 'icon' => 'headphones'],
        ];
        $heroSlides = $heroBanners->isNotEmpty()
            ? $heroBanners
            : collect([
                (object) [
                    'title' => 'Premium sharee collection',
                    'headline' => 'Elegant Sharee Collections for Every Graceful Occasion',
                    'cta_label' => 'Shop Sharee',
                    'cta_url' => route('products.index', ['category' => 'sharee']),
                    'image_url' => 'https://images.unsplash.com/photo-1601121141461-9d6647bca1ed?auto=format&fit=crop&w=1800&q=80',
                ],
            ]);
        $featureStories = [
            [
                'title' => 'Wedding Wardrobe',
                'copy' => 'Statement weaves for ceremonies, gifts, and family occasions.',
                'href' => route('products.index', ['sharee_type' => 'Bridal Sharee']),
                'image' => 'https://images.unsplash.com/photo-1610030469983-98e550d6193c?auto=format&fit=crop&w=900&q=80',
            ],
            [
                'title' => 'Everyday Grace',
                'copy' => 'Soft colors, light textures, and easy daily elegance.',
                'href' => route('products.index', ['sharee_type' => 'Daily Wear Sharee']),
                'image' => 'https://images.unsplash.com/photo-1595341595379-cf1cd0fb7fb3?auto=format&fit=crop&w=900&q=80',
            ],
            [
                'title' => 'Gift-Ready Picks',
                'copy' => 'Sharee, ornaments, and essentials ready for meaningful giving.',
                'href' => route('products.index'),
                'image' => 'https://images.unsplash.com/photo-1513201099705-a9746e1e201f?auto=format&fit=crop&w=900&q=80',
            ],
        ];
        $homeStats = [
            ['value' => '4.9', 'label' => 'Average rating'],
            ['value' => '24h', 'label' => 'Dhaka dispatch'],
            ['value' => 'COD', 'label' => 'Cash on delivery'],
        ];
    @endphp

    <section class="relative isolate min-h-[560px] overflow-hidden border-b border-[#ead8ba] sm:min-h-[620px] lg:min-h-[640px] xl:min-h-[660px]" data-hero-carousel>
        @foreach ($heroSlides as $slide)
            <article class="{{ $loop->first ? 'opacity-100' : 'pointer-events-none opacity-0' }} absolute inset-0 transition-opacity duration-700 ease-out" data-hero-slide>
                <img src="{{ $slide->image_url }}" alt="{{ $slide->title ?: 'Premium sharee collection' }}" class="absolute inset-0 -z-30 h-full w-full object-cover object-[62%_center] sm:object-center">
                <div class="absolute inset-0 -z-20 bg-[#2f241f]/54"></div>
                <div class="absolute inset-0 -z-10 bg-gradient-to-r from-[#261a17]/95 via-[#681d4c]/76 to-[#261a17]/18"></div>

                <div class="mx-auto flex h-full max-w-7xl items-center px-4 py-14 sm:py-16 lg:py-18">
                    <div class="grid w-full gap-8 text-white lg:grid-cols-[minmax(0,1fr)_320px] lg:items-end xl:grid-cols-[minmax(0,1fr)_360px]">
                        <div class="max-w-4xl">
                        <p class="text-[11px] font-bold uppercase tracking-[0.2em] text-[#f4d885] sm:text-xs">Premium light boutique</p>
                        <h1 class="mt-5 max-w-4xl font-serif text-3xl font-bold leading-[1.16] text-white drop-shadow-[0_3px_18px_rgba(0,0,0,0.28)] sm:text-4xl md:text-5xl lg:text-5xl xl:text-6xl">{{ $slide->headline ?: 'Elegant Sharee Collections for Every Graceful Occasion' }}</h1>
                        <p class="mt-6 max-w-3xl text-sm leading-7 text-[#fff4df] sm:text-base sm:leading-8">Rich flat-lay product imagery, fabric closeups, graceful colors, and gift-ready styling keep every product at the center.</p>
                        <div class="mt-8 flex flex-col gap-3 sm:flex-row sm:flex-wrap">
                            <a href="{{ $slide->cta_url ?: route('products.index', ['category' => 'sharee']) }}" class="flex-1 rounded-lg bg-[#8a155b] px-5 py-3 text-center font-semibold text-white shadow-lg shadow-black/20 transition hover:bg-[#6f1047] sm:flex-none sm:px-6">{{ $slide->cta_label ?: 'Shop Sharee' }}</a>
                            <a href="{{ route('offers.index') }}" class="flex-1 rounded-lg border border-[#f4d885] bg-white/10 px-5 py-3 text-center font-semibold text-white backdrop-blur transition hover:bg-white hover:text-[#8a155b] sm:flex-none sm:px-6">View Offers</a>
                        </div>

                        <div class="mt-9 grid max-w-3xl grid-cols-1 gap-3 border-t border-white/20 pt-5 text-xs text-[#fff4df] sm:grid-cols-3 sm:text-sm">
                            <div class="rounded-lg bg-white/8 p-3 ring-1 ring-white/10 sm:bg-transparent sm:p-0 sm:ring-0">
                                <p class="font-bold text-white">Boutique edit</p>
                                <p class="mt-1 text-[#f5dfb1]">Occasion-ready looks</p>
                            </div>
                            <div class="rounded-lg bg-white/8 p-3 ring-1 ring-white/10 sm:bg-transparent sm:p-0 sm:ring-0">
                                <p class="font-bold text-white">Premium fabric</p>
                                <p class="mt-1 text-[#f5dfb1]">Texture-first curation</p>
                            </div>
                            <div class="rounded-lg bg-white/8 p-3 ring-1 ring-white/10 sm:bg-transparent sm:p-0 sm:ring-0">
                                <p class="font-bold text-white">Gift-ready</p>
                                <p class="mt-1 text-[#f5dfb1]">Packed with care</p>
                            </div>
                        </div>
                        </div>
                        <div class="hidden rounded-lg border border-white/18 bg-white/10 p-4 shadow-2xl shadow-black/20 backdrop-blur lg:block">
                            <p class="text-xs font-bold uppercase tracking-[0.18em] text-[#f4d885]">Service notes</p>
                            <div class="mt-4 grid gap-3">
                                @foreach ($homeStats as $stat)
                                    <div class="flex items-center justify-between gap-4 rounded-lg bg-white/10 px-4 py-3 ring-1 ring-white/10">
                                        <span class="font-serif text-2xl font-bold text-white">{{ $stat['value'] }}</span>
                                        <span class="text-right text-xs font-semibold uppercase tracking-wide text-[#f5dfb1]">{{ $stat['label'] }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </article>
        @endforeach

        @if ($heroSlides->count() > 1)
            <div class="absolute bottom-4 left-1/2 z-20 flex -translate-x-1/2 items-center gap-3 rounded-full border border-white/20 bg-[#2f241f]/45 px-3 py-2 backdrop-blur">
                <button type="button" class="grid h-8 w-8 place-items-center rounded-full border border-white/30 text-white transition hover:bg-white hover:text-[#8a155b]" data-hero-prev aria-label="Previous banner">‹</button>
                <div class="flex items-center gap-2">
                    @foreach ($heroSlides as $slide)
                        <button type="button" class="{{ $loop->first ? 'w-6 bg-[#f4d885]' : 'w-2 bg-white/55' }} h-2 rounded-full transition-all" data-hero-dot aria-label="Show banner {{ $loop->iteration }}"></button>
                    @endforeach
                </div>
                <button type="button" class="grid h-8 w-8 place-items-center rounded-full border border-white/30 text-white transition hover:bg-white hover:text-[#8a155b]" data-hero-next aria-label="Next banner">›</button>
            </div>
        @endif
    </section>

    @if ($heroSlides->count() > 1)
        <script>
            (() => {
                const carousel = document.querySelector('[data-hero-carousel]');

                if (! carousel) {
                    return;
                }

                const slides = Array.from(carousel.querySelectorAll('[data-hero-slide]'));
                const dots = Array.from(carousel.querySelectorAll('[data-hero-dot]'));
                const previous = carousel.querySelector('[data-hero-prev]');
                const next = carousel.querySelector('[data-hero-next]');
                let activeIndex = 0;
                let timer = null;

                const showSlide = (nextIndex) => {
                    activeIndex = (nextIndex + slides.length) % slides.length;

                    slides.forEach((slide, index) => {
                        const isActive = index === activeIndex;

                        slide.classList.toggle('opacity-100', isActive);
                        slide.classList.toggle('opacity-0', ! isActive);
                        slide.classList.toggle('pointer-events-none', ! isActive);
                    });

                    dots.forEach((dot, index) => {
                        const isActive = index === activeIndex;

                        dot.classList.toggle('w-6', isActive);
                        dot.classList.toggle('w-2', ! isActive);
                        dot.classList.toggle('bg-[#f4d885]', isActive);
                        dot.classList.toggle('bg-white/55', ! isActive);
                    });
                };

                const startTimer = () => {
                    if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
                        return;
                    }

                    timer = window.setInterval(() => showSlide(activeIndex + 1), 5500);
                };

                const restartTimer = () => {
                    window.clearInterval(timer);
                    startTimer();
                };

                previous?.addEventListener('click', () => {
                    showSlide(activeIndex - 1);
                    restartTimer();
                });

                next?.addEventListener('click', () => {
                    showSlide(activeIndex + 1);
                    restartTimer();
                });

                dots.forEach((dot, index) => {
                    dot.addEventListener('click', () => {
                        showSlide(index);
                        restartTimer();
                    });
                });

                startTimer();
            })();
        </script>
    @endif

    <section class="bg-[#fff7ea]">
        <div class="mx-auto grid max-w-7xl gap-3 px-4 py-5 sm:grid-cols-3">
            @foreach ($featureStories as $story)
                <a href="{{ $story['href'] }}" class="group relative min-h-44 overflow-hidden rounded-lg border border-[#ead8ba] bg-[#2f241f] p-5 text-white shadow-[0_14px_34px_rgba(89,61,48,0.12)] transition hover:-translate-y-1 hover:shadow-[0_18px_46px_rgba(122,31,85,0.18)]">
                    <img src="{{ $story['image'] }}" alt="{{ $story['title'] }}" class="absolute inset-0 h-full w-full object-cover opacity-45 transition duration-500 group-hover:scale-105 group-hover:opacity-55">
                    <div class="absolute inset-0 bg-gradient-to-br from-[#2f241f]/90 via-[#5b2142]/55 to-transparent"></div>
                    <div class="relative flex h-full flex-col justify-end">
                        <p class="text-[10px] font-bold uppercase tracking-[0.18em] text-[#f4d885]">Curated edit</p>
                        <h3 class="mt-2 font-serif text-2xl font-bold">{{ $story['title'] }}</h3>
                        <p class="mt-2 max-w-sm text-sm leading-6 text-[#fff4df]">{{ $story['copy'] }}</p>
                    </div>
                </a>
            @endforeach
        </div>
    </section>

    <section class="mx-auto max-w-7xl px-4 py-12">
        <x-section-title title="Find Your Perfect Weave" subtitle="Curated paths for fabric, mood, and occasion." />
        <div class="mt-7 grid grid-cols-2 gap-3 md:grid-cols-4">
            @foreach ($shareeTypes as $type)
                <a href="{{ route('products.index', ['sharee_type' => $type]) }}" class="group relative min-h-28 overflow-hidden rounded-lg border border-[#ead8ba] bg-white p-4 shadow-[0_10px_28px_rgba(89,61,48,0.07)] transition hover:-translate-y-1 hover:border-[#b78a34] hover:shadow-[0_18px_40px_rgba(122,31,85,0.12)]">
                    <span class="absolute right-3 top-3 font-serif text-4xl font-bold text-[#f4ead8] transition group-hover:text-[#ecd29d]">{{ str_pad((string) ($loop->iteration), 2, '0', STR_PAD_LEFT) }}</span>
                    <span class="relative grid h-10 w-10 place-items-center rounded-lg bg-[#fff6e6] text-[#b78a34] transition group-hover:bg-[#8a155b] group-hover:text-white">✥</span>
                    <span class="relative mt-5 block font-serif text-base font-bold leading-tight text-[#2f241f] sm:text-lg">{{ $type }}</span>
                </a>
            @endforeach
        </div>
    </section>

    <section class="border-y border-[#ead8ba] bg-white">
        <div class="mx-auto grid max-w-7xl gap-8 px-4 py-12 lg:grid-cols-[0.85fr_1.15fr] lg:items-center">
            <div>
                <p class="text-xs font-bold uppercase tracking-[0.18em] text-[#b78a34]">Color stories</p>
                <h2 class="mt-2 font-serif text-3xl font-bold leading-tight text-[#2f241f] md:text-4xl">Choose the shade that carries the occasion.</h2>
                <p class="mt-4 max-w-xl text-sm leading-7 text-[#6f5a50]">From soft daily colors to deeper ceremony tones, jump straight into the palette that feels right.</p>
            </div>
            <div class="grid grid-cols-3 gap-3 sm:grid-cols-4 md:grid-cols-6">
                @foreach ($colorOptions as $color)
                    <a href="{{ route('products.index', ['color' => $color['name']]) }}" class="group rounded-lg border border-[#ead8ba] bg-[#fffaf4] p-3 text-center text-xs font-bold text-[#5a463c] transition hover:-translate-y-1 hover:border-[#b78a34] hover:bg-white hover:shadow-md">
                        <span class="mx-auto mb-2 block h-12 w-12 rounded-full border-4 border-white shadow-[0_0_0_1px_#d8b879,0_8px_18px_rgba(89,61,48,0.16)] transition group-hover:scale-105" style="background: {{ $color['code'] }}"></span>
                        {{ $color['name'] }}
                    </a>
                @endforeach
            </div>
        </div>
    </section>

    <section class="mx-auto max-w-7xl px-4 py-12">
        <div class="flex flex-wrap items-end justify-between gap-4">
            <x-section-title title="Best Sellers" subtitle="Customer favorites handpicked from our premium sharee edits." />
            <a href="{{ route('products.index') }}" class="rounded-lg border border-[#d8b879] bg-white px-4 py-2 text-sm font-bold text-[#8a155b] transition hover:border-[#8a155b]">View all</a>
        </div>
        <div class="mt-6 grid grid-cols-2 gap-3 sm:gap-5 lg:grid-cols-4">
            @foreach ($bestSellers as $product)
                <x-storefront.product-card :product="$product" />
            @endforeach
        </div>
    </section>

    <section class="bg-[#2f241f]">
        <div class="mx-auto max-w-7xl px-4 py-12 text-white">
            <div class="grid gap-4 md:grid-cols-[auto_1fr_auto] md:items-end">
                <div>
                    <p class="text-xs font-bold uppercase tracking-[0.18em] text-[#f4d885]">Sunnah Sharee Ghar</p>
                    <h2 class="mt-1 font-serif text-3xl font-bold leading-tight text-white md:text-4xl">Curated Collections for Every Occasion</h2>
                </div>
                <div class="hidden h-px bg-gradient-to-r from-[#f4d885] via-white/35 to-transparent md:block"></div>
                <p class="max-w-xs text-sm leading-6 text-[#fff4df] md:text-right">Handpicked selections just for you.</p>
            </div>
            <div class="mt-7 grid gap-4 sm:grid-cols-2 lg:grid-cols-6">
                @foreach ($collections as $collection)
                    <a href="{{ route('collections.show', $collection) }}" class="group relative min-h-56 overflow-hidden rounded-lg border border-white/15 bg-white/10 p-4 shadow-lg transition hover:-translate-y-1 hover:shadow-2xl {{ $loop->first ? 'sm:col-span-2 lg:col-span-2 lg:row-span-2 lg:min-h-[28rem]' : 'lg:col-span-2' }}">
                        <img src="{{ $collection->banner_url ?: ($collectionImages[$collection->name] ?? 'https://images.unsplash.com/photo-1601121141461-9d6647bca1ed?auto=format&fit=crop&w=900&q=80') }}" alt="{{ $collection->name }}" class="absolute inset-0 h-full w-full object-cover opacity-60 transition duration-500 group-hover:scale-105">
                        <div class="absolute inset-0 bg-gradient-to-t from-[#241815]/95 via-[#5b2142]/45 to-transparent"></div>
                        <div class="relative flex h-full flex-col justify-end">
                            <p class="text-[10px] font-bold uppercase tracking-[0.18em] text-[#f4d885]">Collection</p>
                            <h3 class="mt-2 font-serif text-2xl font-bold">{{ $collection->name }}</h3>
                            <p class="mt-2 line-clamp-2 text-sm leading-6 text-[#fff4df]">{{ $collection->description }}</p>
                            <span class="mt-4 inline-flex h-9 w-9 items-center justify-center rounded-full border border-[#f4d885] text-[#f4d885] transition group-hover:bg-[#f4d885] group-hover:text-[#8a155b]">›</span>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    </section>

    <section class="mx-auto max-w-7xl px-4 py-12">
        <div class="flex flex-wrap items-end justify-between gap-4">
            <x-section-title title="Fresh Weaves, Just for You" subtitle="New designs. Fresh colors. Ready to fall in love." />
            <a href="{{ route('products.index', ['sort' => 'latest']) }}" class="rounded-lg border border-[#d8b879] bg-white px-4 py-2 text-sm font-bold text-[#8a155b] transition hover:border-[#8a155b]">New arrivals</a>
        </div>
        <div class="mt-6 grid grid-cols-2 gap-3 sm:gap-5 lg:grid-cols-4">
            @foreach ($newArrivals as $product)
                <x-storefront.product-card :product="$product" />
            @endforeach
        </div>
    </section>

    <section class="bg-[#fff7ea]">
        <div class="mx-auto max-w-7xl px-4 py-12">
            <x-section-title title="More from Sunnah Sharee Ghar" subtitle="Secondary boutique essentials selected to complement your sharee shopping." />
            <div class="mt-6 grid grid-cols-2 gap-3 sm:gap-4 lg:grid-cols-4">
                @foreach ($essentials as $essential)
                    <article class="group overflow-hidden rounded-lg border border-[#ead8ba] bg-[#fffdf8] shadow-[0_10px_28px_rgba(89,61,48,0.08)] transition hover:-translate-y-1 hover:border-[#c9a24a]">
                        <a href="{{ route('products.index', ['category' => $essential['slug']]) }}" class="block">
                            <div class="relative aspect-[4/3] overflow-hidden">
                                <img src="{{ $essential['image'] }}" alt="{{ $essential['name'] }}" class="h-full w-full object-cover transition duration-500 group-hover:scale-105">
                                <span class="absolute left-3 top-3 rounded-full border border-[#f1d88a]/70 bg-white/90 px-3 py-1 text-[10px] font-bold uppercase tracking-wide text-[#8a155b]">Essentials</span>
                            </div>
                            <div class="p-3 sm:p-5">
                                <h3 class="font-serif text-lg font-bold text-[#2f241f] sm:text-xl">{{ $essential['name'] }}</h3>
                                <p class="mt-2 min-h-10 text-xs leading-5 text-[#6f5a50] sm:text-sm">{{ $essential['subtitle'] }}</p>
                                <span class="mt-4 inline-flex rounded-lg bg-[#8a155b] px-4 py-2 text-xs font-bold text-white transition group-hover:bg-[#6f1047]">Explore</span>
                            </div>
                        </a>
                    </article>
                @endforeach
            </div>
        </div>
    </section>

    <section class="mx-auto grid max-w-7xl gap-5 px-4 py-12 lg:grid-cols-2">
        <div class="relative min-h-72 overflow-hidden rounded-lg bg-[#8a155b] p-8 text-white shadow-[0_14px_36px_rgba(122,31,85,0.22)]">
            <img src="https://images.unsplash.com/photo-1594736797933-d0501ba2fe65?auto=format&fit=crop&w=1000&q=80" alt="" class="absolute inset-0 h-full w-full object-cover opacity-35">
            <div class="absolute inset-0 bg-gradient-to-r from-[#8a155b] via-[#8a155b]/80 to-transparent"></div>
            <div class="relative z-10 max-w-sm">
                <p class="text-xs font-bold uppercase tracking-[0.18em] text-[#f1d88a]">Limited Time Offer</p>
                <h2 class="mt-2 font-serif text-3xl font-bold">Boutique Savings</h2>
                <p class="mt-2 text-sm leading-6 text-white/85">Up to 20% off on selected collections.</p>
                <a href="{{ route('offers.index') }}" class="mt-6 inline-block rounded-lg bg-white px-5 py-3 text-sm font-bold text-[#8a155b]">Shop Offers</a>
            </div>
        </div>
        <div class="relative min-h-72 overflow-hidden rounded-lg border border-[#ead8ba] bg-[#f8ead0] p-8 shadow-sm">
            <img src="https://images.unsplash.com/photo-1515562141207-7a88fb7ce338?auto=format&fit=crop&w=1000&q=80" alt="" class="absolute inset-0 h-full w-full object-cover opacity-25">
            <div class="absolute inset-0 bg-gradient-to-r from-[#fff7ea] via-[#fff7ea]/86 to-transparent"></div>
            <div class="relative max-w-sm">
                <p class="text-xs font-bold uppercase tracking-[0.18em] text-[#8a155b]">Combo Deals</p>
                <h2 class="mt-2 font-serif text-3xl font-bold">Sharee Gifts Made Simple</h2>
                <p class="mt-2 text-sm leading-6 text-[#6f5a50]">Beautiful combos for every occasion.</p>
                <a href="{{ route('combos.index') }}" class="mt-6 inline-block rounded-lg bg-[#8a155b] px-5 py-3 text-sm font-bold text-white">View Combos</a>
            </div>
        </div>
    </section>

    <section class="mx-auto max-w-7xl px-4 py-12">
        <x-section-title title="Trusted by Thousands, Loved for Quality" subtitle="Premium quality, elegant designs, and service you can trust." />
        <div class="mt-6 grid gap-3 sm:grid-cols-2 lg:grid-cols-6">
            @foreach ($trustCards as $card)
                <div class="rounded-lg border border-[#ead8ba] bg-white/80 p-4 text-center shadow-sm transition hover:-translate-y-1 hover:border-[#c9a24a] hover:bg-white hover:shadow-md">
                    <span class="mx-auto mb-3 grid h-10 w-10 place-items-center rounded-lg bg-[#fff6e6] text-[#b78a34]">
                        @if (isset($card['image']))
                            <img src="{{ $card['image'] }}" alt="" class="h-5 w-5 object-contain">
                        @else
                            <x-storefront.icon :name="$card['icon']" class="h-5 w-5" />
                        @endif
                    </span>
                    <h3 class="text-sm font-bold">{{ $card['title'] }}</h3>
                    <p class="mt-1 text-xs text-[#6f5a50]">{{ $card['copy'] }}</p>
                </div>
            @endforeach
        </div>
    </section>
@endsection
