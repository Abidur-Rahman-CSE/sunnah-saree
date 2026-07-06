@extends('layouts.admin', ['heading' => $order->order_number])

@section('content')
    <div class="mb-4 flex justify-end">
        <a href="{{ route('admin.orders.invoice', $order) }}" target="_blank" class="rounded-lg border border-[#7a1f55] px-4 py-2 font-semibold text-[#7a1f55]">Print Invoice</a>
    </div>
    <div class="grid gap-6 lg:grid-cols-[1fr_360px]">
        <div class="grid gap-6">
            <div class="rounded-lg border border-[#e5ded0] bg-white p-6 shadow-sm">
                <h2 class="font-bold">Customer Info</h2>
                <div class="mt-4 grid gap-4 text-sm md:grid-cols-2">
                    <div>
                        <p class="text-xs font-bold uppercase tracking-wide text-[#8d786d]">Name</p>
                        <p class="mt-1 font-semibold">{{ $order->customer_name }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-bold uppercase tracking-wide text-[#8d786d]">Phone</p>
                        <p class="mt-1 font-semibold">{{ $order->customer_phone }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-bold uppercase tracking-wide text-[#8d786d]">Email</p>
                        <p class="mt-1 font-semibold">{{ $order->customer_email ?: 'Not provided' }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-bold uppercase tracking-wide text-[#8d786d]">Account</p>
                        <p class="mt-1 font-semibold">{{ $order->user?->email ?: 'Guest checkout' }}</p>
                    </div>
                    <div class="md:col-span-2">
                        <p class="text-xs font-bold uppercase tracking-wide text-[#8d786d]">Shipping Address</p>
                        <p class="mt-1 font-semibold">{{ collect([$order->shipping_area, $order->shipping_district, $order->shipping_division])->filter()->join(', ') }}</p>
                        <p class="mt-1 font-semibold">{{ $order->shipping_address }}</p>
                    </div>
                </div>
            </div>

            <div class="rounded-lg border border-[#e5ded0] bg-white p-6 shadow-sm">
                <h2 class="font-bold">Items</h2>
                <div class="mt-4 overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead>
                            <tr class="border-b text-xs font-bold uppercase tracking-wide text-[#8d786d]">
                                <th class="py-3">Product</th>
                                <th>Variant</th>
                                <th>Qty</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($order->items as $item)
                                <tr class="border-b last:border-0">
                                    <td class="py-3">
                                        <div class="flex min-w-64 items-center gap-3">
                                            @if ($item->product)
                                                <img src="{{ $item->product->primaryImage() }}" alt="{{ $item->product_name }}" class="h-14 w-14 rounded-lg border border-[#eadcc3] object-cover">
                                            @else
                                                <span class="grid h-14 w-14 place-items-center rounded-lg border border-dashed border-[#d8c7a8] bg-[#fffaf4] text-xs font-bold text-[#8d786d]">No image</span>
                                            @endif
                                            <div>
                                                <p class="font-semibold text-[#2f1f1a]">{{ $item->product_name }}</p>
                                                <p class="mt-1 text-xs font-semibold text-[#8d786d]">Product ID: {{ $item->product_id ?? 'Deleted' }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $item->variant_name ?: 'Default' }}</td>
                                    <td>{{ $item->quantity }}</td>
                                    <td class="font-semibold">৳{{ number_format((float) $item->total) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
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
