@extends('layouts.storefront', ['title' => 'Order Confirmed'])

@section('content')
    <section class="mx-auto max-w-4xl px-4 py-12">
        <div class="rounded-lg border border-[#eadcc3] bg-white p-8 text-center shadow-sm">
            <p class="text-sm font-bold uppercase text-[#c9a24a]">Order Placed</p>
            <h1 class="mt-2 font-serif text-4xl font-bold">Thank you for your order</h1>
            <p class="mt-3 text-[#6f5a50]">Your order number is <strong>{{ $order->order_number }}</strong>. We will contact you to confirm delivery.</p>
            <div class="mx-auto mt-6 max-w-md rounded-lg bg-[#fffaf0] p-5 text-left text-sm">
                <div class="flex justify-between"><span>Status</span><strong>{{ str($order->status)->title() }}</strong></div>
                <div class="mt-2 flex justify-between"><span>Payment</span><strong>{{ str($order->payment_status)->title() }}</strong></div>
                <div class="mt-2 flex justify-between"><span>Total</span><strong>৳{{ number_format((float) $order->total) }}</strong></div>
            </div>
            <a href="{{ route('products.index') }}" class="mt-6 inline-block rounded-lg bg-[#7a1f55] px-6 py-3 font-semibold text-white">Continue Shopping</a>
        </div>
    </section>
@endsection
