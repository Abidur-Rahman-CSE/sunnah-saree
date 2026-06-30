@extends('layouts.storefront', ['title' => $collection->name])

@section('content')
    <section class="mx-auto max-w-7xl px-4 py-8">
        @php($coverImage = $collection->banner_url ?: $featuredProducts->first()?->primaryImage())

        <div class="relative aspect-[3/1] overflow-hidden rounded-lg border border-[#e5c98f] bg-[#f8f1e8] shadow-sm">
            @if ($coverImage)
                <img src="{{ $coverImage }}" alt="{{ $collection->name }}" class="h-full w-full object-cover">
            @endif
            <div class="absolute inset-0 bg-gradient-to-r from-[#fffaf3]/95 via-[#fffaf3]/75 to-transparent"></div>
            <div class="absolute inset-y-0 left-0 hidden max-w-2xl flex-col justify-center p-6 md:flex md:p-10">
                <p class="text-xs font-bold uppercase tracking-[0.24em] text-[#c08b32]">Collection</p>
                <h1 class="mt-2 font-serif text-4xl font-bold leading-tight text-[#3a1c2f] md:text-6xl">{{ $collection->name }}</h1>
                @if ($collection->description)
                    <p class="mt-4 max-w-xl text-sm leading-6 text-[#6f5a50] md:text-base">{{ $collection->description }}</p>
                @endif
            </div>
        </div>

        <div class="mt-5 rounded-lg border border-[#eadcc3] bg-white/90 p-5 shadow-sm md:hidden">
            <p class="text-xs font-bold uppercase tracking-[0.24em] text-[#c08b32]">Collection</p>
            <h1 class="mt-2 font-serif text-4xl font-bold leading-tight text-[#3a1c2f]">{{ $collection->name }}</h1>
            @if ($collection->description)
                <p class="mt-3 text-sm leading-6 text-[#6f5a50]">{{ $collection->description }}</p>
            @endif
        </div>

        @if ($featuredProducts->isNotEmpty())
            <div class="mt-10">
                <x-section-title title="Collection Highlights" subtitle="A quick carousel of selected pieces from this collection." />
                <div class="mt-6 flex snap-x gap-3 overflow-x-auto pb-3 sm:gap-5">
                    @foreach ($featuredProducts as $product)
                        <div class="min-w-[calc(50%-0.375rem)] snap-start sm:min-w-56 lg:min-w-64">
                            <x-storefront.product-card :product="$product" />
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <div class="mt-10">
            <x-section-title title="All Collection Products" subtitle="Filter this collection by color, fabric, occasion, price, and availability." />
            @include('storefront.partials.product-filter-grid', ['showCategoryFilter' => false])
        </div>
    </section>
@endsection
