@extends('layouts.storefront', ['title' => $offer->title])

@section('content')
    <section class="mx-auto max-w-7xl px-4 py-8">
        @php($coverImage = $offer->banner_url ?: $featuredProducts->first()?->primaryImage())

        <div class="relative aspect-[3/1] overflow-hidden rounded-lg border border-[#e5c98f] bg-[#f8f1e8] shadow-sm">
            @if ($coverImage)
                <img src="{{ $coverImage }}" alt="{{ $offer->title }}" class="h-full w-full object-cover">
            @endif
            <div class="absolute inset-0 bg-gradient-to-r from-[#2f1728]/85 via-[#6f1d52]/45 to-transparent"></div>
            <div class="absolute inset-y-0 left-0 hidden max-w-2xl flex-col justify-center p-6 text-white md:flex md:p-10">
                <p class="text-xs font-bold uppercase tracking-[0.24em] text-[#e5c27a]">Offer Zone</p>
                <h1 class="mt-2 font-serif text-4xl font-bold leading-tight md:text-6xl">{{ $offer->title }}</h1>
                @if ($offer->description)
                    <p class="mt-4 max-w-xl text-sm leading-6 text-white/85 md:text-base">{{ $offer->description }}</p>
                @endif
            </div>
        </div>

        <div class="mt-5 rounded-lg border border-[#eadcc3] bg-white/90 p-5 shadow-sm md:hidden">
            <p class="text-xs font-bold uppercase tracking-[0.24em] text-[#c08b32]">Offer Zone</p>
            <h1 class="mt-2 font-serif text-4xl font-bold leading-tight text-[#3a1c2f]">{{ $offer->title }}</h1>
            @if ($offer->description)
                <p class="mt-3 text-sm leading-6 text-[#6f5a50]">{{ $offer->description }}</p>
            @endif
        </div>

        @if ($featuredProducts->isNotEmpty())
            <div class="mt-10">
                <div class="flex items-end justify-between gap-4">
                    <x-section-title title="Featured From This Offer" subtitle="A quick look at selected discounted pieces." />
                </div>
                <div class="mt-6 flex snap-x gap-5 overflow-x-auto pb-3">
                    @foreach ($featuredProducts as $product)
                        <div class="min-w-64 snap-start">
                            <x-storefront.product-card :product="$product" />
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <div class="mt-10">
            <x-section-title title="All Offer Products" subtitle="Filter this offer by color, fabric, occasion, price, and availability." />
            @include('storefront.partials.product-filter-grid', ['showCategoryFilter' => false])
        </div>
    </section>
@endsection
