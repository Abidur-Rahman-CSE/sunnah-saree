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

        $collectionImages = [
            'Eid Collection' => 'https://images.unsplash.com/photo-1607860108855-64acf2078ed9?auto=format&fit=crop&w=900&q=80',
            'Wedding Collection' => 'https://images.unsplash.com/photo-1594736797933-d0501ba2fe65?auto=format&fit=crop&w=900&q=80',
            'Bridal Collection' => 'https://images.unsplash.com/photo-1601121141461-9d6647bca1ed?auto=format&fit=crop&w=900&q=80',
            'Gift Collection' => 'https://images.unsplash.com/photo-1513201099705-a9746e1e201f?auto=format&fit=crop&w=900&q=80',
            'Budget Collection' => 'https://images.unsplash.com/photo-1595341595379-cf1cd0fb7fb3?auto=format&fit=crop&w=900&q=80',
            'Premium Collection' => 'https://images.unsplash.com/photo-1610030469983-98e550d6193c?auto=format&fit=crop&w=900&q=80',
        ];
    @endphp

    <section class="mx-auto grid max-w-7xl gap-8 px-4 py-8 lg:grid-cols-[1.05fr_0.95fr] lg:items-center">
        <div class="py-8">
            <p class="text-sm font-bold uppercase text-[#c9a24a]">Premium light boutique</p>
            <h1 class="mt-3 max-w-3xl font-serif text-4xl font-bold leading-tight text-[#2f241f] md:text-6xl">{{ $hero?->headline ?? 'Elegant Sharee Collections for Every Graceful Occasion' }}</h1>
            <p class="mt-5 max-w-2xl text-lg text-[#6f5a50]">No model photos needed. Rich flat-lay product imagery, fabric closeups, graceful colors, and gift-ready styling keep every product at the center.</p>
            <div class="mt-8 flex flex-wrap gap-3">
                <a href="{{ route('products.index', ['category' => 'sharee']) }}" class="rounded-lg bg-[#8a155b] px-6 py-3 font-semibold text-white shadow-lg shadow-[#8a155b]/20 transition hover:bg-[#6f1047]">{{ $hero?->cta_label ?? 'Shop Sharee' }}</a>
                <a href="{{ route('offers.index') }}" class="rounded-lg border border-[#c9a24a] bg-white px-6 py-3 font-semibold text-[#8a155b] transition hover:border-[#8a155b]">View Offers</a>
            </div>
        </div>
        <div class="relative">
            <img src="{{ $hero?->image_url ?? 'https://images.unsplash.com/photo-1601121141461-9d6647bca1ed?auto=format&fit=crop&w=1600&q=80' }}" alt="Premium fabric flat-lay" class="aspect-[4/3] w-full rounded-lg border border-[#d8b879] object-cover shadow-2xl shadow-[#7a1f55]/10">
            <div class="absolute bottom-4 left-4 rounded-lg border border-[#ead8ba] bg-white/90 p-4 shadow-lg backdrop-blur">
                <p class="text-xs font-bold uppercase tracking-wide text-[#c9a24a]">Featured</p>
                <p class="font-serif text-xl font-bold">Bridal boutique edit</p>
            </div>
        </div>
    </section>

    <section class="mx-auto max-w-7xl px-4 py-8">
        <x-section-title title="Find Your Perfect Weave" subtitle="Curated for every occasion and style." />
        <div class="mt-6 grid grid-cols-2 gap-3 md:grid-cols-4">
            @foreach ($shareeTypes as $type)
                <a href="{{ route('products.index', ['sharee_type' => $type]) }}" class="group flex items-center gap-4 rounded-lg border border-[#ead8ba] bg-white/80 p-5 font-serif text-base font-bold shadow-sm transition hover:border-[#b78a34] hover:bg-white hover:shadow-md">
                    <span class="grid h-10 w-10 place-items-center rounded-lg bg-[#fff6e6] text-[#b78a34] transition group-hover:bg-[#8a155b] group-hover:text-white">✥</span>
                    {{ $type }}
                </a>
            @endforeach
        </div>
    </section>

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

    <section class="mx-auto max-w-7xl px-4 py-8">
        <x-section-title title="Best Sellers" subtitle="Customer favorites handpicked from our premium sharee edits." />
        <div class="mt-6 grid gap-5 md:grid-cols-4">
            @foreach ($bestSellers as $product)
                <x-storefront.product-card :product="$product" />
            @endforeach
        </div>
    </section>

    <section class="mx-auto max-w-7xl px-4 py-8">
        <x-section-title title="Fresh Weaves, Just for You" subtitle="New designs. Fresh colors. Ready to fall in love." />
        <div class="mt-6 grid gap-5 md:grid-cols-4">
            @foreach ($newArrivals as $product)
                <x-storefront.product-card :product="$product" />
            @endforeach
        </div>
    </section>

    <section class="mx-auto max-w-7xl px-4 py-8">
        <x-section-title title="Curated Collections for Every Occasion" subtitle="Handpicked selections just for you." />
        <div class="mt-6 grid gap-4 sm:grid-cols-2 lg:grid-cols-6">
            @foreach ($collections as $collection)
                <a href="{{ route('collections.show', $collection) }}" class="group relative min-h-40 overflow-hidden rounded-lg border border-[#ead8ba] bg-white p-4 shadow-sm transition hover:-translate-y-1 hover:shadow-lg">
                    <img src="{{ $collectionImages[$collection->name] ?? $collection->banner_url }}" alt="{{ $collection->name }}" class="absolute inset-0 h-full w-full object-cover opacity-55 transition group-hover:scale-105">
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

    <section class="mx-auto max-w-7xl px-4 py-8">
        <x-section-title title="More from Sunnah Sharee Ghar" subtitle="Secondary boutique essentials selected to complement your sharee shopping." />
        <div class="mt-6 grid grid-cols-2 gap-4 lg:grid-cols-4">
            @foreach ($essentials as $essential)
                <article class="group overflow-hidden rounded-lg border border-[#ead8ba] bg-[#fffdf8] shadow-[0_10px_28px_rgba(89,61,48,0.08)] transition hover:-translate-y-1 hover:border-[#c9a24a]">
                    <a href="{{ route('products.index', ['category' => $essential['slug']]) }}" class="block">
                        <div class="relative aspect-[4/3] overflow-hidden">
                            <img src="{{ $essential['image'] }}" alt="{{ $essential['name'] }}" class="h-full w-full object-cover transition duration-500 group-hover:scale-105">
                            <span class="absolute left-3 top-3 rounded-full border border-[#f1d88a]/70 bg-white/90 px-3 py-1 text-[10px] font-bold uppercase tracking-wide text-[#8a155b]">Essentials</span>
                        </div>
                        <div class="p-5">
                            <h3 class="font-serif text-xl font-bold text-[#2f241f]">{{ $essential['name'] }}</h3>
                            <p class="mt-2 min-h-10 text-sm leading-5 text-[#6f5a50]">{{ $essential['subtitle'] }}</p>
                            <span class="mt-4 inline-flex rounded-lg bg-[#8a155b] px-4 py-2 text-xs font-bold text-white transition group-hover:bg-[#6f1047]">Explore</span>
                        </div>
                    </a>
                </article>
            @endforeach
        </div>
    </section>

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

    <section class="mx-auto max-w-7xl px-4 py-8">
        <x-section-title title="Trusted by Thousands, Loved for Quality" subtitle="Premium quality, elegant designs, and service you can trust." />
        <div class="mt-6 grid gap-3 sm:grid-cols-2 lg:grid-cols-6">
            @foreach (['Premium Fabrics' => 'Sourced with care', 'Authentic Weaves' => 'Traditional craftsmanship', 'Elegant Packaging' => 'Gift-ready every time', 'Cash on Delivery' => 'Pay when you receive', 'Easy Returns' => 'Hassle-free process', 'Customer Support' => 'We are here to help'] as $title => $copy)
                <div class="rounded-lg border border-[#ead8ba] bg-white/80 p-4 text-center">
                    <span class="mx-auto mb-3 grid h-10 w-10 place-items-center rounded-lg bg-[#fff6e6] text-[#b78a34]">✧</span>
                    <h3 class="text-sm font-bold">{{ $title }}</h3>
                    <p class="mt-1 text-xs text-[#6f5a50]">{{ $copy }}</p>
                </div>
            @endforeach
        </div>
    </section>
@endsection
