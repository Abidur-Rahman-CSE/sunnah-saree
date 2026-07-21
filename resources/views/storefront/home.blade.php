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
        $homeSections = [
            'hero' => \App\Models\Setting::valueFor('home_section_hero_enabled', '1') === '1',
            'sharee_types' => \App\Models\Setting::valueFor('home_section_sharee_types_enabled', '1') === '1',
            'colors' => \App\Models\Setting::valueFor('home_section_colors_enabled', '1') === '1',
            'best_sellers' => \App\Models\Setting::valueFor('home_section_best_sellers_enabled', '1') === '1',
            'new_arrivals' => \App\Models\Setting::valueFor('home_section_new_arrivals_enabled', '1') === '1',
            'collections' => \App\Models\Setting::valueFor('home_section_collections_enabled', '1') === '1',
            'essentials' => \App\Models\Setting::valueFor('home_section_essentials_enabled', '1') === '1',
            'promo_banners' => \App\Models\Setting::valueFor('home_section_promo_banners_enabled', '1') === '1',
            'testimonials' => \App\Models\Setting::valueFor('home_section_testimonials_enabled', '1') === '1',
            'trust' => \App\Models\Setting::valueFor('home_section_trust_enabled', '1') === '1',
        ];
    @endphp

    @if ($homeSections['hero'])
    <section class="relative isolate min-h-[520px] overflow-hidden border-b border-[#ead8ba] sm:min-h-[620px] lg:min-h-[640px] xl:min-h-[660px]" data-hero-carousel>
        @foreach ($heroSlides as $slide)
            <article class="{{ $loop->first ? 'opacity-100' : 'pointer-events-none opacity-0' }} absolute inset-0 transition-opacity duration-700 ease-out" data-hero-slide>
                <img src="{{ $slide->image_url }}" alt="{{ $slide->title ?: 'Premium sharee collection' }}" class="absolute inset-0 -z-30 h-full w-full object-cover object-[68%_center] sm:object-center">
                <div class="absolute inset-0 -z-20 bg-[#2f241f]/64 sm:bg-[#2f241f]/58"></div>
                <div class="absolute inset-0 -z-10 bg-gradient-to-b from-[#2f241f]/96 via-[#5c2342]/72 to-[#2f241f]/58 sm:bg-gradient-to-r sm:from-[#2f241f]/95 sm:via-[#5c2342]/76 sm:to-[#2f241f]/24"></div>

                <div class="mx-auto flex h-full max-w-7xl items-center px-4 py-8 sm:py-16 lg:py-18">
                    <div class="w-full max-w-4xl text-white">
                        <p class="text-[10px] font-bold uppercase tracking-[0.22em] text-[#f4d885] sm:text-xs">Premium light boutique</p>
                        <h1 class="mt-3 max-w-2xl font-serif text-2xl font-bold leading-[1.14] text-white drop-shadow-[0_3px_18px_rgba(0,0,0,0.28)] sm:mt-5 sm:max-w-4xl sm:text-4xl md:text-5xl lg:text-5xl xl:text-6xl">{{ $slide->headline ?: 'Elegant Sharee Collections for Every Graceful Occasion' }}</h1>
                        <p class="mt-4 max-w-xl text-sm leading-6 text-[#fff4df] sm:mt-6 sm:max-w-3xl sm:text-base sm:leading-8">Rich flat-lay product imagery, fabric closeups, graceful colors, and gift-ready styling keep every product at the center.</p>
                        <div class="mt-5 grid grid-cols-2 gap-3 sm:mt-8 sm:flex sm:flex-wrap">
                            <a href="{{ $slide->cta_url ?: route('products.index', ['category' => 'sharee']) }}" class="rounded-lg bg-[#8a155b] px-4 py-2.5 text-center text-sm font-semibold text-white shadow-lg shadow-black/20 transition hover:bg-[#6f1047] sm:flex-none sm:px-6 sm:py-3 sm:text-base">{{ $slide->cta_label ?: 'Shop Sharee' }}</a>
                            <a href="{{ route('offers.index') }}" class="rounded-lg border border-[#f4d885] bg-white/10 px-4 py-2.5 text-center text-sm font-semibold text-white backdrop-blur transition hover:bg-white hover:text-[#8a155b] sm:flex-none sm:px-6 sm:py-3 sm:text-base">View Offers</a>
                        </div>

                        <div class="mt-6 hidden max-w-3xl grid-cols-3 gap-3 border-t border-white/20 pt-4 text-xs text-[#fff4df] sm:mt-9 sm:grid sm:text-sm">
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
    @endif

    @if ($homeSections['sharee_types'])
    <section class="mx-auto max-w-7xl px-4 py-8">
        <x-section-title title="Find Your Perfect Weave" subtitle="Curated for every occasion and style." />
        <div class="mt-6 grid grid-cols-2 gap-3 md:grid-cols-4">
            @foreach ($shareeTypes as $type)
                <a href="{{ route('products.index', ['sharee_type' => $type]) }}" class="group flex min-w-0 items-center gap-3 rounded-lg border border-[#ead8ba] bg-white/80 p-3 font-serif text-sm font-bold shadow-sm transition hover:border-[#b78a34] hover:bg-white hover:shadow-md sm:gap-4 sm:p-5 sm:text-base">
                    <span class="grid h-9 w-9 shrink-0 place-items-center rounded-lg bg-[#fff6e6] text-[#b78a34] transition group-hover:bg-[#8a155b] group-hover:text-white sm:h-10 sm:w-10">✥</span>
                    {{ $type }}
                </a>
            @endforeach
        </div>
    </section>
    @endif

    @if ($homeSections['colors'])
    <section class="mx-auto max-w-7xl px-4 py-8">
        <x-section-title title="Colors That Celebrate You" subtitle="Explore shades that suit every mood." />
        <div class="mt-6 grid grid-cols-3 gap-4 sm:grid-cols-4 md:grid-cols-6 lg:grid-cols-11">
            @foreach ($colorOptions as $color)
                <a href="{{ route('products.index', ['color' => $color['name']]) }}" class="text-center text-xs font-medium text-[#5a463c]">
                    <span class="mx-auto mb-2 block h-12 w-12 rounded-full border-4 border-white shadow-[0_0_0_1px_#d8b879,0_8px_18px_rgba(89,61,48,0.16)]" style="background: {{ $color['code'] }}"></span>
                    {{ $color['name'] }}
                </a>
            @endforeach
        </div>
    </section>
    @endif

    @if ($homeSections['best_sellers'])
    <section class="mx-auto max-w-7xl px-4 py-8">
        <x-section-title title="Best Sellers" subtitle="Customer favorites handpicked from our premium sharee edits." />
        <div class="mt-6 grid grid-cols-2 gap-3 sm:gap-5 lg:grid-cols-4">
            @foreach ($bestSellers as $product)
                <x-storefront.product-card :product="$product" />
            @endforeach
        </div>
    </section>
    @endif

    @if ($homeSections['new_arrivals'])
    <section class="mx-auto max-w-7xl px-4 py-8">
        <x-section-title title="Fresh Weaves, Just for You" subtitle="New designs. Fresh colors. Ready to fall in love." />
        <div class="mt-6 grid grid-cols-2 gap-3 sm:gap-5 lg:grid-cols-4">
            @foreach ($newArrivals as $product)
                <x-storefront.product-card :product="$product" />
            @endforeach
        </div>
    </section>
    @endif

    @if ($homeSections['collections'])
    <section class="mx-auto max-w-7xl px-4 py-8">
        <x-section-title title="Curated Collections for Every Occasion" subtitle="Handpicked selections just for you." />
        <div class="mt-6 flex snap-x gap-4 overflow-x-auto pb-3 lg:grid lg:grid-cols-6 lg:overflow-visible lg:pb-0">
            @foreach ($collections as $collection)
                <a href="{{ route('collections.show', $collection) }}" class="group relative min-h-40 min-w-[calc(50%-0.5rem)] snap-start overflow-hidden rounded-lg border border-[#ead8ba] bg-white p-3 shadow-sm transition hover:-translate-y-1 hover:shadow-lg sm:min-w-64 sm:p-4 lg:min-w-0">
                    <img src="{{ $collection->banner_url ?: ($collectionImages[$collection->name] ?? 'https://images.unsplash.com/photo-1601121141461-9d6647bca1ed?auto=format&fit=crop&w=900&q=80') }}" alt="{{ $collection->name }}" class="absolute inset-0 h-full w-full object-cover opacity-55 transition group-hover:scale-105">
                    <div class="absolute inset-0 bg-gradient-to-br from-white via-white/70 to-transparent"></div>
                    <div class="relative">
                        <h3 class="font-serif text-lg font-bold">{{ $collection->name }}</h3>
                        <p class="mt-1 line-clamp-2 text-xs text-[#6f5a50]">{{ $collection->description }}</p>
                        <span class="mt-5 grid h-8 w-8 place-items-center rounded-full border border-[#d8b879] bg-white text-[#8a155b]">›</span>
                    </div>
                </a>
            @endforeach
        </div>
    </section>
    @endif

    @if ($homeSections['essentials'])
    <section class="mx-auto max-w-7xl px-4 py-8">
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
    </section>
    @endif

    @if ($homeSections['promo_banners'])
    <section class="mx-auto grid max-w-7xl gap-5 px-4 py-8 lg:grid-cols-2">
        <div class="relative overflow-hidden rounded-lg bg-[#8a155b] p-8 text-white shadow-[0_14px_36px_rgba(122,31,85,0.22)]">
            <div class="relative z-10">
                <p class="text-xs font-bold uppercase tracking-[0.18em] text-[#f1d88a]">Limited Time Offer</p>
                <h2 class="mt-2 font-serif text-3xl font-bold">Boutique Savings</h2>
                <p class="mt-2 text-sm text-white/80">Up to 20% off on selected collections.</p>
                <a href="{{ route('offers.index') }}" class="mt-6 inline-block rounded-lg bg-white px-5 py-3 text-sm font-bold text-[#8a155b]">Shop Offers</a>
            </div>
        </div>
        <div class="relative overflow-hidden rounded-lg border border-[#ead8ba] bg-[#f8ead0] p-8 shadow-sm">
            <p class="text-xs font-bold uppercase tracking-[0.18em] text-[#8a155b]">Combo Deals</p>
            <h2 class="mt-2 font-serif text-3xl font-bold">Sharee Gifts Made Simple</h2>
            <p class="mt-2 text-sm text-[#6f5a50]">Beautiful combos for every occasion.</p>
            <a href="{{ route('combos.index') }}" class="mt-6 inline-block rounded-lg bg-[#8a155b] px-5 py-3 text-sm font-bold text-white">View Combos</a>
        </div>
    </section>
    @endif

    @if ($homeSections['testimonials'] && $testimonials->isNotEmpty())
    <section class="mx-auto max-w-7xl px-4 py-8" data-testimonial-carousel>
        <div class="flex items-end justify-between gap-4">
            <x-section-title title="Customer Stories" subtitle="Real feedback from Sunnah Sharee shoppers." />
            <div class="hidden items-center gap-2 sm:flex">
                <button type="button" class="grid h-10 w-10 place-items-center rounded-full border border-[#d8b879] bg-white text-xl font-bold text-[#8a155b] transition hover:bg-[#8a155b] hover:text-white" data-testimonial-prev aria-label="Previous testimonial">‹</button>
                <button type="button" class="grid h-10 w-10 place-items-center rounded-full border border-[#d8b879] bg-white text-xl font-bold text-[#8a155b] transition hover:bg-[#8a155b] hover:text-white" data-testimonial-next aria-label="Next testimonial">›</button>
                <a href="{{ route('testimonials.index') }}" class="rounded-lg border border-[#d8b879] px-4 py-2 text-sm font-bold text-[#8a155b] transition hover:bg-[#8a155b] hover:text-white">View More</a>
            </div>
        </div>
        <div class="relative mt-6">
            <button type="button" class="absolute left-0 top-1/2 z-10 hidden h-11 w-11 -translate-y-1/2 place-items-center rounded-full border border-[#d8b879] bg-white/95 text-2xl font-bold text-[#8a155b] shadow-lg transition hover:bg-[#8a155b] hover:text-white md:grid" data-testimonial-prev aria-label="Previous testimonial">‹</button>
            <div class="flex h-[29rem] items-center justify-center gap-4 overflow-hidden py-4 sm:h-[31rem]">
                @foreach ($testimonials as $testimonial)
                    @php
                        $initialState = $loop->first ? 'is-active' : ($loop->iteration <= 3 ? 'is-side' : 'is-hidden');
                        $testimonialCardClass = 'group testimonial-carousel-card overflow-hidden rounded-lg border border-[#ead8ba] bg-white shadow-sm transition-all duration-500 hover:shadow-lg '.$initialState;
                    @endphp
                    @if ($testimonial->facebook_post_url)
                        <a href="{{ $testimonial->facebook_post_url }}" target="_blank" rel="noopener" class="{{ $testimonialCardClass }}" data-testimonial-card data-testimonial-index="{{ $loop->index }}">
                            @include('storefront.partials.testimonial-card', ['testimonial' => $testimonial])
                        </a>
                    @else
                        <article class="{{ $testimonialCardClass }}" data-testimonial-card data-testimonial-index="{{ $loop->index }}">
                            @include('storefront.partials.testimonial-card', ['testimonial' => $testimonial])
                        </article>
                    @endif
                @endforeach
            </div>
            <button type="button" class="absolute right-0 top-1/2 z-10 hidden h-11 w-11 -translate-y-1/2 place-items-center rounded-full border border-[#d8b879] bg-white/95 text-2xl font-bold text-[#8a155b] shadow-lg transition hover:bg-[#8a155b] hover:text-white md:grid" data-testimonial-next aria-label="Next testimonial">›</button>
        </div>
        <div class="mt-2 flex items-center justify-center gap-2">
            @foreach ($testimonials as $testimonial)
                <button type="button" class="{{ $loop->first ? 'w-6 bg-[#8a155b]' : 'w-2 bg-[#d8b879]' }} h-2 rounded-full transition-all" data-testimonial-dot aria-label="Show testimonial {{ $loop->iteration }}"></button>
            @endforeach
        </div>
        <div class="mt-4 flex items-center justify-center gap-2 sm:hidden">
            <button type="button" class="grid h-9 w-9 place-items-center rounded-full border border-[#d8b879] bg-white text-lg font-bold text-[#8a155b]" data-testimonial-prev aria-label="Previous testimonial">‹</button>
            <a href="{{ route('testimonials.index') }}" class="rounded-lg border border-[#d8b879] px-4 py-2 text-sm font-bold text-[#8a155b] transition hover:bg-[#8a155b] hover:text-white">View More</a>
            <button type="button" class="grid h-9 w-9 place-items-center rounded-full border border-[#d8b879] bg-white text-lg font-bold text-[#8a155b]" data-testimonial-next aria-label="Next testimonial">›</button>
        </div>
    </section>
    <style>
        .testimonial-carousel-card.is-active {
            display: block;
            order: 2;
            width: min(100%, 28rem);
            transform: scale(1);
            opacity: 1;
            z-index: 2;
        }

        .testimonial-carousel-card.is-side {
            display: none;
            order: 1;
            width: 15rem;
            transform: scale(.9);
            opacity: .68;
            z-index: 1;
        }

        .testimonial-carousel-card.is-side-next {
            order: 3;
        }

        .testimonial-carousel-card.is-hidden {
            display: none;
        }

        @media (min-width: 640px) {
            .testimonial-carousel-card.is-side {
                display: block;
            }
        }
    </style>
    <script>
        (() => {
            const carousel = document.querySelector('[data-testimonial-carousel]');

            if (! carousel) {
                return;
            }

            const cards = Array.from(carousel.querySelectorAll('[data-testimonial-card]'));
            const dots = Array.from(carousel.querySelectorAll('[data-testimonial-dot]'));
            const previousButtons = carousel.querySelectorAll('[data-testimonial-prev]');
            const nextButtons = carousel.querySelectorAll('[data-testimonial-next]');
            let activeIndex = 0;
            let timer = null;

            const showTestimonial = (nextIndex) => {
                activeIndex = (nextIndex + cards.length) % cards.length;
                const previousIndex = (activeIndex - 1 + cards.length) % cards.length;
                const nextCardIndex = (activeIndex + 1) % cards.length;

                cards.forEach((card, index) => {
                    card.classList.toggle('is-active', index === activeIndex);
                    card.classList.toggle('is-side', index === previousIndex || index === nextCardIndex);
                    card.classList.toggle('is-side-next', index === nextCardIndex);
                    card.classList.toggle('is-hidden', index !== activeIndex && index !== previousIndex && index !== nextCardIndex);
                });

                dots.forEach((dot, index) => {
                    dot.classList.toggle('w-6', index === activeIndex);
                    dot.classList.toggle('w-2', index !== activeIndex);
                    dot.classList.toggle('bg-[#8a155b]', index === activeIndex);
                    dot.classList.toggle('bg-[#d8b879]', index !== activeIndex);
                });
            };

            const startTimer = () => {
                if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
                    return;
                }

                timer = window.setInterval(() => showTestimonial(activeIndex + 1), 4500);
            };

            const restartTimer = () => {
                window.clearInterval(timer);
                startTimer();
            };

            previousButtons.forEach((button) => button.addEventListener('click', () => {
                showTestimonial(activeIndex - 1);
                restartTimer();
            }));
            nextButtons.forEach((button) => button.addEventListener('click', () => {
                showTestimonial(activeIndex + 1);
                restartTimer();
            }));
            dots.forEach((dot, index) => dot.addEventListener('click', () => {
                showTestimonial(index);
                restartTimer();
            }));
            carousel.addEventListener('mouseenter', () => window.clearInterval(timer));
            carousel.addEventListener('mouseleave', restartTimer);
            carousel.addEventListener('focusin', () => window.clearInterval(timer));
            carousel.addEventListener('focusout', restartTimer);
            showTestimonial(0);
            startTimer();
        })();
    </script>
    @endif

    @if ($homeSections['trust'])
    <section class="mx-auto max-w-7xl px-4 py-8">
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
    @endif
@endsection
