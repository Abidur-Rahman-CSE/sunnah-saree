@extends('layouts.admin', ['heading' => $customer->name])

@section('content')
    <div class="grid gap-6 lg:grid-cols-[320px_1fr]">
        <div class="h-fit rounded-lg border border-[#e5ded0] bg-white p-6 shadow-sm">
            <h2 class="font-bold">Contact Details</h2>
            <div class="mt-4 grid gap-2 text-sm">
                <p>Email: {{ $customer->email }}</p>
                <p>Phone: {{ $customer->phone ?? 'Not provided' }}</p>
                <p>Address: {{ $customer->address ?? 'Not provided' }}</p>
            </div>
        </div>
        <div class="rounded-lg border border-[#e5ded0] bg-white p-6 shadow-sm">
            <h2 class="font-bold">Order History</h2>
            <table class="mt-4 w-full text-left text-sm">
                <thead><tr class="border-b"><th class="py-3">Order</th><th>Status</th><th>Payment</th><th>Total</th></tr></thead>
                <tbody>
                    @foreach ($orders as $order)
                        <tr class="border-b"><td class="py-3"><a class="font-semibold text-[#7a1f55]" href="{{ route('admin.orders.show', $order) }}">{{ $order->order_number }}</a></td><td>{{ str($order->status)->title() }}</td><td>{{ str($order->payment_status)->title() }}</td><td>৳{{ number_format((float) $order->total) }}</td></tr>
                    @endforeach
                </tbody>
            </table>
            <div class="mt-5">{{ $orders->links() }}</div>
        </div>
    </div>
@endsection
