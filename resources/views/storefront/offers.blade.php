@extends('layouts.storefront', ['title' => 'Offer Zone'])

@section('content')
    <section class="mx-auto max-w-7xl px-4 py-8">
        <x-section-title title="Offer Zone" subtitle="Active campaigns and discounted boutique picks." />
        <div class="mt-8 grid gap-8">
            @foreach ($offers as $offer)
                <div class="rounded-lg border border-[#eadcc3] bg-white p-6 shadow-sm">
                    <h2 class="font-serif text-3xl font-bold">{{ $offer->title }}</h2>
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
