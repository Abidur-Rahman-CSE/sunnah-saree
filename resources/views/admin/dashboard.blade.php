@extends('layouts.admin', ['heading' => 'Dashboard'])

@section('content')
    <div class="grid gap-4 md:grid-cols-4">
        @foreach (['Total orders' => $totalOrders, 'Today orders' => $todaysOrders, 'Pending orders' => $pendingOrders, 'Total sales' => '৳'.number_format((float) $totalSales)] as $label => $value)
            <div class="rounded-lg border border-[#e5ded0] bg-white p-5 shadow-sm"><p class="text-sm text-[#6f5a50]">{{ $label }}</p><p class="mt-2 text-2xl font-bold">{{ $value }}</p></div>
        @endforeach
    </div>
    <div class="mt-6 grid gap-6 lg:grid-cols-2">
        <div class="rounded-lg border border-[#e5ded0] bg-white p-5 shadow-sm">
            <h2 class="font-bold">Recent Orders</h2>
            <div class="mt-4 grid gap-3 text-sm">
                @foreach ($recentOrders as $order)
                    <a href="{{ route('admin.orders.show', $order) }}" class="flex justify-between rounded-lg bg-[#f7f5f0] p-3"><span>{{ $order->order_number }}</span><span>৳{{ number_format((float) $order->total) }}</span></a>
                @endforeach
            </div>
        </div>
        <div class="rounded-lg border border-[#e5ded0] bg-white p-5 shadow-sm">
            <h2 class="font-bold">Low Stock Products</h2>
            <div class="mt-4 grid gap-3 text-sm">
                @forelse ($lowStockProducts as $product)
                    <div class="rounded-lg bg-[#f7f5f0] p-3">{{ $product->name }} · {{ $product->variants->sum('quantity') }} pcs</div>
                @empty
                    <div class="rounded-lg bg-[#f7f5f0] p-3">No low stock products.</div>
                @endforelse
            </div>
        </div>
    </div>
@endsection
