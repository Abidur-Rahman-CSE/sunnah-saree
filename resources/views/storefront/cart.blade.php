@extends('layouts.storefront', ['title' => 'Cart'])

@section('content')
    <section class="mx-auto max-w-7xl px-4 py-8">
        <x-section-title title="Cart" subtitle="Update quantities, remove items, and review totals before checkout." />
        <div class="mt-8 grid min-w-0 gap-6 lg:grid-cols-[minmax(0,1fr)_360px]">
            <div class="min-w-0 space-y-4">
                @forelse ($cart->items as $item)
                    <div class="grid min-w-0 grid-cols-[80px_minmax(0,1fr)] gap-4 rounded-lg border border-[#eadcc3] bg-white p-4 shadow-sm md:grid-cols-[96px_minmax(0,1fr)_auto] md:items-center">
                        <img src="{{ $item->product->primaryImage() }}" alt="{{ $item->product->name }}" class="aspect-square w-full rounded-lg object-cover">
                        <div class="min-w-0">
                            <h2 class="font-serif text-lg font-bold leading-tight sm:text-xl">{{ $item->product->name }}</h2>
                            <p class="text-sm text-[#6f5a50]">{{ $item->variant?->color }} · ৳{{ number_format($item->product->finalPrice()) }}</p>
                        </div>
                        <div class="col-span-2 flex flex-wrap items-center gap-2 md:col-span-1 md:justify-end">
                            <form action="{{ route('cart.update', $item) }}" method="POST" class="flex min-w-0 flex-1 gap-2 sm:flex-none">
                                @csrf @method('PATCH')
                                <label class="sr-only" for="cart-quantity-{{ $item->id }}">Quantity for {{ $item->product->name }}</label>
                                <input id="cart-quantity-{{ $item->id }}" name="quantity" value="{{ $item->quantity }}" type="number" min="1" class="w-20 rounded-lg border border-[#dfcda9] px-3 py-2" aria-label="Quantity for {{ $item->product->name }}">
                                <button class="flex-1 rounded-lg border border-[#7a1f55] px-3 py-2 text-sm font-semibold text-[#7a1f55] sm:flex-none">Update</button>
                            </form>
                            <form action="{{ route('cart.destroy', $item) }}" method="POST">
                                @csrf @method('DELETE')
                                <button class="rounded-lg bg-[#f8e8e8] px-3 py-2 text-sm font-semibold text-red-700">Remove</button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="rounded-lg border border-[#eadcc3] bg-white p-8 text-center">Your cart is empty.</div>
                @endforelse
            </div>
            <aside class="h-fit rounded-lg border border-[#eadcc3] bg-white p-5 shadow-sm">
                <h2 class="font-serif text-2xl font-bold">Order Summary</h2>
                <div class="mt-5 rounded-lg bg-[#fffaf0] p-3">
                    @if ($coupon)
                        <div class="flex items-center justify-between gap-3 text-sm">
                            <span><strong>{{ $coupon->code }}</strong> applied</span>
                            <form action="{{ route('cart.coupon.remove') }}" method="POST">
                                @csrf @method('DELETE')
                                <button class="font-semibold text-red-700">Remove</button>
                            </form>
                        </div>
                    @else
                        <form action="{{ route('cart.coupon.apply') }}" method="POST" class="grid gap-2 sm:flex">
                            @csrf
                            <label class="sr-only" for="coupon-code">Coupon code</label>
                            <input id="coupon-code" name="coupon_code" value="{{ old('coupon_code') }}" class="min-w-0 flex-1 rounded-lg border border-[#dfcda9] px-3 py-2 text-sm" placeholder="Coupon code">
                            <button class="rounded-lg bg-[#7a1f55] px-3 py-2 text-sm font-semibold text-white">Apply</button>
                        </form>
                    @endif
                    @error('coupon_code')
                        <p class="mt-2 text-sm text-red-700">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mt-5 grid gap-3 text-sm">
                    <div class="flex justify-between"><span>Subtotal</span><span>৳{{ number_format($subtotal) }}</span></div>
                    <div class="flex justify-between"><span>Delivery charge</span><span>৳{{ number_format($deliveryCharge) }}</span></div>
                    <div class="flex justify-between"><span>Coupon discount</span><span>-৳{{ number_format($discountAmount) }}</span></div>
                    <div class="flex justify-between border-t border-[#eadcc3] pt-3 text-lg font-bold"><span>Total</span><span>৳{{ number_format($total) }}</span></div>
                </div>
                <a href="{{ route('checkout.create') }}" class="mt-6 block rounded-lg bg-[#7a1f55] px-4 py-3 text-center font-semibold text-white">Checkout</a>
            </aside>
        </div>
    </section>
@endsection
