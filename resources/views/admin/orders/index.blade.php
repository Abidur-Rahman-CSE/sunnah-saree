@extends('layouts.admin', ['heading' => 'Orders'])

@section('content')
    <div class="mb-4">
        <form class="grid gap-2 md:grid-cols-[1fr_180px_auto]">
            <input name="search" value="{{ request('search') }}" class="rounded-lg border border-[#ddd4c4] px-4 py-2" placeholder="Search order, customer, phone">
            <select name="status" class="rounded-lg border border-[#ddd4c4] px-4 py-2"><option value="">Any status</option>@foreach (['pending','confirmed','processing','shipped','delivered','cancelled'] as $status)<option value="{{ $status }}" @selected(request('status') === $status)>{{ str($status)->title() }}</option>@endforeach</select>
            <button class="rounded-lg bg-[#7a1f55] px-4 py-2 font-semibold text-white">Filter</button>
        </form>
    </div>
    <div class="overflow-x-auto rounded-lg border border-[#e5ded0] bg-white shadow-sm">
        <table class="w-full text-left text-sm">
            <thead><tr class="border-b bg-[#faf8f3]"><th class="p-3">Order</th><th>Customer</th><th>Status</th><th>Payment</th><th>Total</th><th></th></tr></thead>
            <tbody>
            @foreach ($orders as $order)
                <tr class="border-b"><td class="p-3 font-semibold">{{ $order->order_number }}</td><td>{{ $order->customer_name }}</td><td>{{ str($order->status)->title() }}</td><td>{{ str($order->payment_status)->title() }}</td><td>৳{{ number_format((float) $order->total) }}</td><td><a class="font-semibold text-[#7a1f55]" href="{{ route('admin.orders.show', $order) }}">View</a></td></tr>
            @endforeach
            </tbody>
        </table>
    </div>
    <div class="mt-5">{{ $orders->links() }}</div>
@endsection
