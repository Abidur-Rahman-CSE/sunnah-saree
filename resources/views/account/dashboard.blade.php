@extends('layouts.storefront', ['title' => 'Account'])

@section('content')
    <section class="mx-auto max-w-7xl px-4 py-8">
        <x-section-title title="Customer Dashboard" subtitle="Order history and account details." />
        <div class="mt-6 flex flex-wrap gap-3">
            <a href="{{ route('account.orders.index') }}" class="rounded-lg border border-[#7a1f55] px-4 py-2 text-sm font-semibold text-[#7a1f55]">Order History</a>
            <a href="{{ route('account.wishlist.index') }}" class="rounded-lg bg-[#7a1f55] px-4 py-2 text-sm font-semibold text-white">Wishlist</a>
        </div>
        <div class="mt-8 rounded-lg border border-[#eadcc3] bg-white p-6 shadow-sm">
            <h2 class="font-serif text-2xl font-bold">Recent Orders</h2>
            <div class="mt-4 overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead><tr class="border-b"><th class="py-3">Order</th><th>Status</th><th>Total</th><th></th></tr></thead>
                    <tbody>
                    @forelse ($orders as $order)
                        <tr class="border-b"><td class="py-3">{{ $order->order_number }}</td><td>{{ str($order->status)->title() }}</td><td>৳{{ number_format((float) $order->total) }}</td><td><a class="font-semibold text-[#7a1f55]" href="{{ route('account.orders.show', $order) }}">View</a></td></tr>
                    @empty
                        <tr><td class="py-4" colspan="4">No orders yet.</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </section>
@endsection
