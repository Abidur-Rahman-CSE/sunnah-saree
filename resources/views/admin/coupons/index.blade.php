@extends('layouts.admin', ['heading' => 'Coupons'])

@section('content')
    <x-admin.index-toolbar :create-url="route('admin.coupons.create')" create-label="Add Coupon" search-placeholder="Search coupons" />
    <div class="overflow-x-auto rounded-lg border border-[#e5ded0] bg-white shadow-sm">
        <table class="w-full text-left text-sm">
            <thead><tr class="border-b bg-[#faf8f3]"><th class="p-3">Code</th><th>Type</th><th>Value</th><th>Minimum</th><th>Status</th><th></th></tr></thead>
            <tbody>
                @foreach ($coupons as $coupon)
                    <tr class="border-b"><td class="p-3 font-semibold">{{ $coupon->code }}</td><td>{{ str($coupon->type)->title() }}</td><td>{{ $coupon->type === 'percentage' ? $coupon->value.'%' : '৳'.number_format((float) $coupon->value) }}</td><td>৳{{ number_format((float) $coupon->minimum_order_amount) }}</td><td>{{ $coupon->is_active ? 'Active' : 'Inactive' }}</td><td><a class="font-semibold text-[#7a1f55]" href="{{ route('admin.coupons.edit', $coupon) }}">Edit</a></td></tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="mt-5">{{ $coupons->links() }}</div>
@endsection
