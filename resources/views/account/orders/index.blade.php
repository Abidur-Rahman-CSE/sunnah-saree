@extends('layouts.storefront', ['title' => 'Order History'])

@section('content')
    <section class="mx-auto max-w-7xl px-4 py-8">
        <x-section-title title="Order History" />
        <div class="mt-8 rounded-lg border border-[#eadcc3] bg-white p-6 shadow-sm">
            <table class="w-full text-left text-sm">
                <thead><tr class="border-b"><th class="py-3">Order</th><th>Status</th><th>Payment</th><th>Total</th><th></th></tr></thead>
                <tbody>
                @foreach ($orders as $order)
                    <tr class="border-b"><td class="py-3">{{ $order->order_number }}</td><td>{{ str($order->status)->title() }}</td><td>{{ str($order->payment_status)->title() }}</td><td>৳{{ number_format((float) $order->total) }}</td><td><a class="font-semibold text-[#7a1f55]" href="{{ route('account.orders.show', $order) }}">View</a></td></tr>
                @endforeach
                </tbody>
            </table>
            <div class="mt-5">{{ $orders->links() }}</div>
        </div>
    </section>
@endsection
