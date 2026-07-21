@extends('layouts.storefront', ['title' => $order->order_number])

@section('content')
    <section class="mx-auto max-w-4xl px-4 py-8">
        <div class="mb-4 flex justify-end">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button class="rounded-lg border border-red-200 bg-red-50 px-4 py-2 text-sm font-semibold text-red-700">Logout</button>
            </form>
        </div>
        <div class="rounded-lg border border-[#eadcc3] bg-white p-6 shadow-sm">
            <p class="text-sm font-bold uppercase text-[#c9a24a]">Order Details</p>
            <h1 class="font-serif text-3xl font-bold">{{ $order->order_number }}</h1>
            <div class="mt-4 grid gap-3 text-sm md:grid-cols-3">
                <div>Status: <strong>{{ str($order->status)->title() }}</strong></div>
                <div>Payment: <strong>{{ str($order->payment_status)->title() }}</strong></div>
                <div>Total: <strong>৳{{ number_format((float) $order->total) }}</strong></div>
            </div>
            <table class="mt-6 w-full text-left text-sm">
                <thead><tr class="border-b"><th class="py-3">Product</th><th>Variant</th><th>Qty</th><th>Total</th></tr></thead>
                <tbody>
                @foreach ($order->items as $item)
                    <tr class="border-b"><td class="py-3">{{ $item->product_name }}</td><td>{{ $item->variant_name }}</td><td>{{ $item->quantity }}</td><td>৳{{ number_format((float) $item->total) }}</td></tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </section>
@endsection
