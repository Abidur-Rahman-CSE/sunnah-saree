@extends('layouts.storefront', ['title' => 'Checkout'])

@section('content')
    <section class="mx-auto max-w-7xl px-4 py-8">
        <x-section-title title="Checkout" subtitle="Cash on delivery is ready. Online payment has placeholder structure for gateway integration." />
        <form action="{{ route('checkout.store') }}" method="POST" class="mt-8 grid min-w-0 gap-6 lg:grid-cols-[minmax(0,1fr)_360px]">
            @csrf
            <div class="min-w-0 rounded-lg border border-[#eadcc3] bg-white p-5 shadow-sm">
                <h2 class="font-serif text-2xl font-bold">Customer Info</h2>
                <div class="mt-5 grid gap-4 md:grid-cols-2">
                    <x-admin.field label="Name"><input name="customer_name" value="{{ old('customer_name', auth()->user()?->name) }}" class="w-full min-w-0 rounded-lg border border-[#dfcda9] px-4 py-3" placeholder="Name"></x-admin.field>
                    <x-admin.field label="Phone"><input name="customer_phone" value="{{ old('customer_phone', auth()->user()?->phone) }}" class="w-full min-w-0 rounded-lg border border-[#dfcda9] px-4 py-3" placeholder="Phone"></x-admin.field>
                    <x-admin.field label="Email" span><input name="customer_email" value="{{ old('customer_email', auth()->user()?->email) }}" class="w-full min-w-0 rounded-lg border border-[#dfcda9] px-4 py-3" placeholder="Email"></x-admin.field>
                    <x-admin.field label="Shipping address" span><textarea name="shipping_address" class="w-full min-w-0 rounded-lg border border-[#dfcda9] px-4 py-3" rows="4" placeholder="Shipping address">{{ old('shipping_address', auth()->user()?->address) }}</textarea></x-admin.field>
                </div>
                @if ($errors->any())
                    <div class="mt-4 rounded-lg bg-red-50 p-4 text-sm text-red-700">{{ $errors->first() }}</div>
                @endif
                <h2 class="mt-8 font-serif text-2xl font-bold">Payment Method</h2>
                <div class="mt-4 grid gap-3">
                    <label class="rounded-lg border border-[#dfcda9] p-4"><input type="radio" name="payment_method" value="cod" checked> Cash on Delivery</label>
                    <label class="rounded-lg border border-[#dfcda9] p-4"><input type="radio" name="payment_method" value="online"> Online payment gateway placeholder</label>
                </div>
            </div>
            <aside class="h-fit rounded-lg border border-[#eadcc3] bg-white p-5 shadow-sm">
                <h2 class="font-serif text-2xl font-bold">Summary</h2>
                <div class="mt-4 space-y-3 text-sm">
                    @foreach ($cart->items as $item)
                        <div class="flex justify-between gap-3"><span class="min-w-0">{{ $item->product->name }} x {{ $item->quantity }}</span><span class="shrink-0">৳{{ number_format($item->lineTotal()) }}</span></div>
                    @endforeach
                </div>
                <div class="mt-5 grid gap-3 border-t border-[#eadcc3] pt-4 text-sm">
                    <div class="flex justify-between"><span>Subtotal</span><span>৳{{ number_format($subtotal) }}</span></div>
                    <div class="flex justify-between"><span>Delivery</span><span>৳{{ number_format($deliveryCharge) }}</span></div>
                    <div class="flex justify-between"><span>Discount{{ $coupon ? ' · '.$coupon->code : '' }}</span><span>-৳{{ number_format($discountAmount) }}</span></div>
                    <div class="flex justify-between text-lg font-bold"><span>Total</span><span>৳{{ number_format($total) }}</span></div>
                </div>
                <button class="mt-6 w-full rounded-lg bg-[#7a1f55] px-4 py-3 font-semibold text-white">Place Order</button>
            </aside>
        </form>
    </section>
@endsection
