@extends('layouts.storefront', ['title' => 'Offer Zone'])

@section('content')
    <section class="mx-auto max-w-7xl px-4 py-8">
        <x-section-title title="Offer Zone" subtitle="Active campaigns and discounted boutique picks." />
        <div class="mt-8 grid gap-10">
            @foreach ($offers as $offer)
                @php($coverImage = $offer->banner_url ?: $offer->products->first()?->primaryImage())
                <article class="overflow-hidden rounded-lg border border-[#eadcc3] bg-white shadow-sm">
                    <div class="relative aspect-[3/1] overflow-hidden bg-[#f8f1e8]">
                        @if ($coverImage)
                            <img src="{{ $coverImage }}" alt="{{ $offer->title }}" class="h-full w-full object-cover">
                        @endif
                        <div class="absolute inset-0 bg-gradient-to-r from-[#2f1728]/80 via-[#5f1848]/45 to-transparent"></div>
                        <div class="absolute inset-y-0 left-0 hidden max-w-xl flex-col justify-center p-6 text-white md:flex md:p-10">
                            <p class="text-xs font-bold uppercase tracking-[0.22em] text-[#e5c27a]">Limited Offer</p>
                            <h2 class="mt-2 font-serif text-3xl font-bold leading-tight md:text-5xl">{{ $offer->title }}</h2>
                            @if ($offer->description)
                                <p class="mt-3 line-clamp-2 text-sm text-white/85 md:text-base">{{ $offer->description }}</p>
                            @endif
                            <a href="{{ route('offers.show', $offer) }}" class="mt-5 inline-flex w-fit items-center rounded-lg bg-white px-5 py-3 text-sm font-bold text-[#7a1f55] shadow-sm transition hover:bg-[#f9eddf]">View More</a>
                        </div>
                    </div>

                    <div class="border-b border-[#eadcc3] bg-[#fffaf3] px-5 py-5 md:hidden">
                        <p class="text-xs font-bold uppercase tracking-[0.22em] text-[#c08b32]">Limited Offer</p>
                        <h2 class="mt-2 font-serif text-3xl font-bold leading-tight text-[#3a1c2f]">{{ $offer->title }}</h2>
                        @if ($offer->description)
                            <p class="mt-2 text-sm text-[#6f5a50]">{{ $offer->description }}</p>
                        @endif
                        <a href="{{ route('offers.show', $offer) }}" class="mt-4 inline-flex rounded-lg bg-[#7a1f55] px-5 py-3 text-sm font-bold text-white shadow-sm">View More</a>
                    </div>

                    <div class="px-5 py-6 md:px-8">
                        <div class="flex items-end justify-between gap-4">
                            <div>
                                <p class="text-xs font-bold uppercase tracking-[0.2em] text-[#c08b32]">Selected Picks</p>
                                <h3 class="font-serif text-2xl font-bold text-[#2d1a22]">Featured products in this offer</h3>
                            </div>
                            <a href="{{ route('offers.show', $offer) }}" class="hidden text-sm font-bold text-[#7a1f55] md:inline">Explore all</a>
                        </div>
                        <div class="mt-5 flex snap-x gap-3 overflow-x-auto pb-3 sm:gap-5">
                            @forelse ($offer->products->take(8) as $product)
                                <div class="min-w-[calc(50%-0.375rem)] snap-start sm:min-w-56 lg:min-w-64">
                                    <x-storefront.product-card :product="$product" />
                                </div>
                            @empty
                                <div class="w-full rounded-lg border border-dashed border-[#dfcda9] bg-[#fffaf3] p-8 text-center text-[#6f5a50]">No products are attached to this offer yet.</div>
                            @endforelse
                        </div>
                    </div>
                </article>
            @endforeach
        </div>
    </section>
@endsection
