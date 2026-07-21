@extends('layouts.storefront', ['title' => 'Wishlist'])

@section('content')
    <section class="mx-auto max-w-7xl px-4 py-8">
        <div class="flex flex-wrap items-center justify-between gap-3">
            <x-section-title title="Wishlist" subtitle="Saved products for later purchase." />
            <div class="flex flex-wrap gap-2">
                <a href="{{ route('account.dashboard') }}" class="rounded-lg border border-[#7a1f55] px-4 py-2 text-sm font-semibold text-[#7a1f55]">Dashboard</a>
                <a href="{{ route('account.orders.index') }}" class="rounded-lg bg-[#7a1f55] px-4 py-2 text-sm font-semibold text-white">Order History</a>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button class="rounded-lg border border-red-200 bg-red-50 px-4 py-2 text-sm font-semibold text-red-700">Logout</button>
                </form>
            </div>
        </div>
        <div class="mt-8 grid gap-5 md:grid-cols-4">
            @forelse ($wishlistItems as $item)
                <div class="relative">
                    <x-storefront.product-card :product="$item->product" />
                    <form action="{{ route('wishlist.destroy', $item->product) }}" method="POST" class="mt-3">
                        @csrf @method('DELETE')
                        <button class="w-full rounded-lg border border-red-200 bg-red-50 px-4 py-2 text-sm font-semibold text-red-700">Remove from Wishlist</button>
                    </form>
                </div>
            @empty
                <div class="rounded-lg border border-[#eadcc3] bg-white p-8 text-center md:col-span-4">
                    <p>Your wishlist is empty.</p>
                    <a href="{{ route('products.index') }}" class="mt-4 inline-block rounded-lg bg-[#7a1f55] px-5 py-3 font-semibold text-white">Browse Products</a>
                </div>
            @endforelse
        </div>
        <div class="mt-8">{{ $wishlistItems->links() }}</div>
    </section>
@endsection
