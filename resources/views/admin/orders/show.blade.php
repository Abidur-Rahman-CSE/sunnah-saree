@extends('layouts.admin', ['heading' => $order->order_number])

@section('content')
    <div class="mb-4 flex justify-end">
        <a href="{{ route('admin.orders.invoice', $order) }}" target="_blank" class="rounded-lg border border-[#7a1f55] px-4 py-2 font-semibold text-[#7a1f55]">Print Invoice</a>
    </div>
    <div class="grid gap-6 lg:grid-cols-[1fr_360px]">
        <div class="rounded-lg border border-[#e5ded0] bg-white p-6 shadow-sm">
            <h2 class="font-bold">Items</h2>
            <table class="mt-4 w-full text-left text-sm">
                <thead><tr class="border-b"><th class="py-3">Product</th><th>Variant</th><th>Qty</th><th>Total</th></tr></thead>
                <tbody>@foreach ($order->items as $item)<tr class="border-b"><td class="py-3">{{ $item->product_name }}</td><td>{{ $item->variant_name }}</td><td>{{ $item->quantity }}</td><td>৳{{ number_format((float) $item->total) }}</td></tr>@endforeach</tbody>
            </table>
        </div>
        <form action="{{ route('admin.orders.update', $order) }}" method="POST" class="h-fit rounded-lg border border-[#e5ded0] bg-white p-6 shadow-sm">
            @csrf @method('PATCH')
            <h2 class="font-bold">Manage Order</h2>
            <div class="mt-4 grid gap-4">
                <x-admin.field label="Order status"><select name="status" class="rounded-lg border border-[#ddd4c4] px-4 py-3">@foreach (['pending','confirmed','processing','shipped','delivered','cancelled'] as $status)<option value="{{ $status }}" @selected($order->status === $status)>{{ str($status)->title() }}</option>@endforeach</select></x-admin.field>
                <x-admin.field label="Payment status"><select name="payment_status" class="rounded-lg border border-[#ddd4c4] px-4 py-3">@foreach (['pending','paid','failed','cancelled','refunded'] as $status)<option value="{{ $status }}" @selected($order->payment_status === $status)>{{ str($status)->title() }}</option>@endforeach</select></x-admin.field>
                <x-admin.field label="Admin note"><textarea name="admin_note" rows="4" class="rounded-lg border border-[#ddd4c4] px-4 py-3" placeholder="Admin note">{{ $order->admin_note }}</textarea></x-admin.field>
                <button class="rounded-lg bg-[#7a1f55] px-4 py-3 font-semibold text-white">Update Order</button>
            </div>
        </form>
    </div>
@endsection
