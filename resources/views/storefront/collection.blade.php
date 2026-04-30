@extends('layouts.storefront', ['title' => $collection->name])

@section('content')
    <section class="mx-auto max-w-7xl px-4 py-8">
        <div class="rounded-lg bg-white p-8 shadow-sm">
            <p class="text-sm font-bold uppercase text-[#c9a24a]">Collection</p>
            <h1 class="font-serif text-4xl font-bold">{{ $collection->name }}</h1>
            <p class="mt-3 text-[#6f5a50]">{{ $collection->description }}</p>
        </div>
        <div class="mt-8 grid gap-5 md:grid-cols-4">
            @foreach ($products as $product)
                <x-storefront.product-card :product="$product" />
            @endforeach
        </div>
        <div class="mt-8">{{ $products->links() }}</div>
    </section>
@endsection
