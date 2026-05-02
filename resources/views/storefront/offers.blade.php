@extends('layouts.storefront', ['title' => 'Offer Zone'])

@section('content')
    <section class="relative mx-auto max-w-7xl px-4 py-8">
        <x-ui.page-pattern />
        <div class="relative overflow-hidden rounded-lg border border-[#ead8ba] bg-gradient-to-br from-white via-[#fffaf4] to-[#fff7ea] p-8 shadow-sm">
            <x-ui.corner-ornament position="top-right" class="hidden md:block" />
            <x-section-title title="Offer Zone" subtitle="Active campaigns and discounted boutique picks." />
        </div>
        <div class="relative mt-8 grid gap-8">
            @foreach ($offers as $offer)
                <div class="relative overflow-hidden rounded-lg border border-[#ead8ba] bg-white/95 p-6 shadow-sm">
                    <x-ui.corner-ornament position="bottom-right" class="hidden md:block h-16 w-16 opacity-30" />
                    <h2 class="font-serif text-3xl font-bold">{{ $offer->title }}</h2>
                    <x-ui.section-divider class="mx-0 w-52" />
                    <p class="mt-2 text-[#6f5a50]">{{ $offer->description }}</p>
                    <div class="mt-6 grid gap-5 md:grid-cols-4">
                        @foreach ($offer->products as $product)
                            <x-storefront.product-card :product="$product" />
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
    </section>
@endsection
