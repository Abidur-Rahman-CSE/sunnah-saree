@extends('layouts.storefront', ['title' => 'Account'])

@section('content')
    @php
        $user = auth()->user();
        $totalOrders = $orders->count();
        $totalSpent = $orders->sum('total');
        $pendingOrders = $orders->where('status', 'pending')->count();
    @endphp

    <section class="mx-auto max-w-7xl px-4 py-8">
        <div class="rounded-lg border border-[#ead8ba] bg-white p-5 shadow-sm sm:p-6">
            <div class="flex flex-col gap-5 lg:flex-row lg:items-center lg:justify-between">
                <div class="flex items-center gap-4">
                    <div class="grid h-16 w-16 shrink-0 place-items-center rounded-lg bg-[#8a155b] font-serif text-2xl font-bold text-white shadow-lg shadow-[#8a155b]/20">
                        {{ str($user->name)->substr(0, 1)->upper() }}
                    </div>
                    <div>
                        <p class="text-xs font-bold uppercase tracking-[0.18em] text-[#c9a24a]">My Account</p>
                        <h1 class="font-serif text-3xl font-bold text-[#2f241f]">{{ $user->name }}</h1>
                        <p class="mt-1 text-sm text-[#6f5a50]">{{ $user->email }}</p>
                    </div>
                </div>
                <div class="flex flex-wrap gap-2">
                    <a href="{{ route('account.orders.index') }}" class="rounded-lg border border-[#7a1f55] px-4 py-2 text-sm font-semibold text-[#7a1f55] transition hover:bg-[#7a1f55] hover:text-white">Order History</a>
                    <a href="{{ route('account.wishlist.index') }}" class="rounded-lg bg-[#7a1f55] px-4 py-2 text-sm font-semibold text-white transition hover:bg-[#5f1742]">Wishlist</a>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button class="rounded-lg border border-red-200 bg-red-50 px-4 py-2 text-sm font-semibold text-red-700 transition hover:bg-red-100">Logout</button>
                    </form>
                </div>
            </div>

            <div class="mt-6 grid gap-3 sm:grid-cols-3">
                <div class="rounded-lg border border-[#ead8ba] bg-[#fffaf4] p-4">
                    <p class="text-xs font-bold uppercase tracking-wide text-[#8a155b]">Recent Orders</p>
                    <p class="mt-2 text-3xl font-bold text-[#2f241f]">{{ $totalOrders }}</p>
                </div>
                <div class="rounded-lg border border-[#ead8ba] bg-[#fffaf4] p-4">
                    <p class="text-xs font-bold uppercase tracking-wide text-[#8a155b]">Pending</p>
                    <p class="mt-2 text-3xl font-bold text-[#2f241f]">{{ $pendingOrders }}</p>
                </div>
                <div class="rounded-lg border border-[#ead8ba] bg-[#fffaf4] p-4">
                    <p class="text-xs font-bold uppercase tracking-wide text-[#8a155b]">Recent Spend</p>
                    <p class="mt-2 text-3xl font-bold text-[#2f241f]">৳{{ number_format((float) $totalSpent) }}</p>
                </div>
            </div>
        </div>

        <div class="mt-8 rounded-lg border border-[#eadcc3] bg-white p-6 shadow-sm">
            <div class="flex items-center justify-between gap-3">
                <div>
                    <p class="text-xs font-bold uppercase tracking-[0.18em] text-[#c9a24a]">Orders</p>
                    <h2 class="font-serif text-2xl font-bold">Recent Orders</h2>
                </div>
                <a href="{{ route('account.orders.index') }}" class="rounded-lg bg-[#fff6e8] px-4 py-2 text-sm font-bold text-[#8a155b]">View all</a>
            </div>
            <div class="mt-5 hidden overflow-x-auto sm:block">
                <table class="w-full text-left text-sm">
                    <thead><tr class="border-b text-[#6f5a50]"><th class="py-3">Order</th><th>Status</th><th>Total</th><th></th></tr></thead>
                    <tbody>
                    @forelse ($orders as $order)
                        <tr class="border-b">
                            <td class="py-3 font-semibold">{{ $order->order_number }}</td>
                            <td><span class="rounded-full bg-[#fff6e8] px-3 py-1 text-xs font-bold text-[#8a155b]">{{ str($order->status)->title() }}</span></td>
                            <td class="font-semibold">৳{{ number_format((float) $order->total) }}</td>
                            <td><a class="font-semibold text-[#7a1f55]" href="{{ route('account.orders.show', $order) }}">View</a></td>
                        </tr>
                    @empty
                        <tr><td class="py-4" colspan="4">No orders yet.</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-5 grid gap-3 sm:hidden">
                @forelse ($orders as $order)
                    <a href="{{ route('account.orders.show', $order) }}" class="rounded-lg border border-[#ead8ba] bg-[#fffaf4] p-4">
                        <div class="flex items-center justify-between gap-3">
                            <p class="font-bold">{{ $order->order_number }}</p>
                            <span class="rounded-full bg-white px-3 py-1 text-xs font-bold text-[#8a155b]">{{ str($order->status)->title() }}</span>
                        </div>
                        <p class="mt-3 text-sm font-semibold text-[#6f5a50]">৳{{ number_format((float) $order->total) }}</p>
                    </a>
                @empty
                    <div class="rounded-lg border border-[#ead8ba] bg-[#fffaf4] p-6 text-center">
                        <p class="text-sm text-[#6f5a50]">No orders yet.</p>
                        <a href="{{ route('products.index') }}" class="mt-4 inline-flex rounded-lg bg-[#7a1f55] px-4 py-2 text-sm font-bold text-white">Start shopping</a>
                    </div>
                @endforelse
            </div>
        </div>
    </section>
@endsection
