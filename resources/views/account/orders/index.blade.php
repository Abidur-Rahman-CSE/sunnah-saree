@extends('layouts.storefront', ['title' => 'Order History'])

@section('content')
    <section class="mx-auto max-w-7xl px-4 py-8">
        <div class="flex flex-wrap items-center justify-between gap-3">
            <x-section-title title="Order History" />
            <div class="flex flex-wrap gap-2">
                <a href="{{ route('account.dashboard') }}" class="rounded-lg border border-[#7a1f55] px-4 py-2 text-sm font-semibold text-[#7a1f55]">Dashboard</a>
                <a href="{{ route('account.wishlist.index') }}" class="rounded-lg bg-[#7a1f55] px-4 py-2 text-sm font-semibold text-white">Wishlist</a>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button class="rounded-lg border border-red-200 bg-red-50 px-4 py-2 text-sm font-semibold text-red-700">Logout</button>
                </form>
            </div>
        </div>
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
