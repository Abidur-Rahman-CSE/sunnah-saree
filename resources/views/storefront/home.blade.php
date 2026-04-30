@extends('layouts.storefront', ['title' => 'Sunnah Sharee Ghar'])

@section('content')
    <section class="mx-auto grid max-w-7xl gap-8 px-4 py-8 lg:grid-cols-[1.05fr_0.95fr] lg:items-center">
        <div class="py-8">
            <p class="text-sm font-bold uppercase text-[#c9a24a]">Premium light boutique</p>
            <h1 class="mt-3 max-w-3xl font-serif text-4xl font-bold leading-tight text-[#2f241f] md:text-6xl">{{ $hero?->headline ?? 'Elegant Sharee Collections for Every Graceful Occasion' }}</h1>
            <p class="mt-5 max-w-2xl text-lg text-[#6f5a50]">No model photos needed. Rich flat-lay product imagery, fabric closeups, graceful colors, and gift-ready styling keep every product at the center.</p>
            <div class="mt-8 flex flex-wrap gap-3">
                <a href="{{ route('products.index') }}" class="rounded-lg bg-[#7a1f55] px-6 py-3 font-semibold text-white">{{ $hero?->cta_label ?? 'Shop Sharee' }}</a>
                <a href="{{ route('offers.index') }}" class="rounded-lg border border-[#c9a24a] px-6 py-3 font-semibold text-[#7a1f55]">View Offers</a>
            </div>
        </div>
        <div class="relative">
            <img src="{{ $hero?->image_url ?? 'https://images.unsplash.com/photo-1601121141461-9d6647bca1ed?auto=format&fit=crop&w=1600&q=80' }}" alt="Premium fabric flat-lay" class="aspect-[4/3] w-full rounded-lg object-cover shadow-2xl">
            <div class="absolute bottom-4 left-4 rounded-lg bg-white/90 p-4 shadow-lg">
                <p class="text-xs uppercase text-[#c9a24a]">Featured</p>
                <p class="font-serif text-xl font-bold">Bridal boutique edit</p>
            </div>
        </div>
    </section>

    <section class="mx-auto max-w-7xl px-4 py-8">
        <x-section-title title="Shop by Sharee Type" subtitle="Customer-first browsing for the way people search." />
        <div class="mt-6 grid grid-cols-2 gap-3 md:grid-cols-4">
            @foreach (['Katan Sharee', 'Chumki Sharee', 'Banarasi Sharee', 'Silk Sharee', 'Cotton Sharee', 'Bridal Sharee', 'Party Wear Sharee', 'Daily Wear Sharee'] as $type)
                <a href="{{ route('products.index', ['sharee_type' => $type]) }}" class="rounded-lg border border-[#eadcc3] bg-white p-5 font-serif text-lg font-bold shadow-sm hover:border-[#c9a24a]">{{ $type }}</a>
            @endforeach
        </div>
    </section>

    <section class="mx-auto max-w-7xl px-4 py-8">
        <x-section-title title="Shop by Color" subtitle="Color-led browsing for saree decisions." />
        <div class="mt-6 grid grid-cols-2 gap-3 md:grid-cols-7">
            @foreach (['Royal Blue' => '#173b8f', 'Maroon' => '#7a1f2b', 'Magenta' => '#b31972', 'Purple' => '#6b3aa8', 'Gold' => '#c9a24a', 'Black' => '#1f1f1f', 'Pastel' => '#f2b7c6'] as $name => $hex)
                <a href="{{ route('products.index', ['color' => $name]) }}" class="rounded-lg border border-[#eadcc3] bg-white p-4 text-sm font-semibold shadow-sm">
                    <span class="mb-3 block h-10 rounded-md" style="background: {{ $hex }}"></span>{{ $name }}
                </a>
            @endforeach
        </div>
    </section>

    <section class="mx-auto max-w-7xl px-4 py-8">
        <x-section-title title="Best Sellers" subtitle="Trusted pieces customers keep choosing." />
        <div class="mt-6 grid gap-5 md:grid-cols-4">
            @foreach ($bestSellers as $product)
                <x-storefront.product-card :product="$product" />
            @endforeach
        </div>
    </section>

    <section class="mx-auto max-w-7xl px-4 py-8">
        <x-section-title title="New Arrivals" subtitle="Fresh fabrics, colors, and gift-ready picks." />
        <div class="mt-6 grid gap-5 md:grid-cols-4">
            @foreach ($newArrivals as $product)
                <x-storefront.product-card :product="$product" />
            @endforeach
        </div>
    </section>

    <section class="mx-auto max-w-7xl px-4 py-8">
        <x-section-title title="Featured Collections" subtitle="Occasion-based edits for faster shopping." />
        <div class="mt-6 grid gap-4 md:grid-cols-3">
            @foreach ($collections as $collection)
                <a href="{{ route('collections.show', $collection) }}" class="rounded-lg border border-[#eadcc3] bg-white p-6 shadow-sm">
                    <p class="text-sm font-bold uppercase text-[#c9a24a]">Collection</p>
                    <h3 class="mt-2 font-serif text-2xl font-bold">{{ $collection->name }}</h3>
                    <p class="mt-2 text-sm text-[#6f5a50]">{{ $collection->description }}</p>
                </a>
            @endforeach
        </div>
    </section>

    <section class="mx-auto grid max-w-7xl gap-5 px-4 py-8 lg:grid-cols-2">
        <div class="rounded-lg bg-[#7a1f55] p-8 text-white">
            <p class="font-bold uppercase text-[#f1d88a]">Offer Zone</p>
            <h2 class="mt-2 font-serif text-3xl font-bold">Limited boutique savings</h2>
            <a href="{{ route('offers.index') }}" class="mt-6 inline-block rounded-lg bg-white px-5 py-3 font-semibold text-[#7a1f55]">Shop Offers</a>
        </div>
        <div class="rounded-lg bg-[#f4e8cd] p-8">
            <p class="font-bold uppercase text-[#7a1f55]">Combo Deals</p>
            <h2 class="mt-2 font-serif text-3xl font-bold">Sharee gifts made simple</h2>
            <a href="{{ route('combos.index') }}" class="mt-6 inline-block rounded-lg bg-[#7a1f55] px-5 py-3 font-semibold text-white">View Combos</a>
        </div>
    </section>

    <section class="mx-auto max-w-7xl px-4 py-8">
        <x-section-title title="Why Choose Sunnah Sharee Ghar" subtitle="Premium fabric, elegant design, gift-ready packaging, trusted quality, cash on delivery, and easy returns." />
    </section>
@endsection
