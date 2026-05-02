@extends('layouts.admin', ['heading' => $coupon->exists ? 'Edit Coupon' : 'Add Coupon'])

@section('content')
    <form action="{{ $coupon->exists ? route('admin.coupons.update', $coupon) : route('admin.coupons.store') }}" method="POST" class="max-w-2xl rounded-lg border border-[#e5ded0] bg-white p-6 shadow-sm">
        @csrf
        @if ($coupon->exists) @method('PUT') @endif
        <div class="grid gap-4 md:grid-cols-2">
            <x-admin.field label="Coupon code"><input name="code" value="{{ old('code', $coupon->code) }}" class="rounded-lg border border-[#ddd4c4] px-4 py-3" placeholder="Coupon code"></x-admin.field>
            <x-admin.field label="Discount type"><select name="type" class="rounded-lg border border-[#ddd4c4] px-4 py-3"><option value="percentage" @selected(old('type', $coupon->type) === 'percentage')>Percentage</option><option value="fixed" @selected(old('type', $coupon->type) === 'fixed')>Fixed amount</option></select></x-admin.field>
            <x-admin.field label="Value"><input name="value" value="{{ old('value', $coupon->value) }}" class="rounded-lg border border-[#ddd4c4] px-4 py-3" placeholder="Value"></x-admin.field>
            <x-admin.field label="Minimum order"><input name="minimum_order_amount" value="{{ old('minimum_order_amount', $coupon->minimum_order_amount ?? 0) }}" class="rounded-lg border border-[#ddd4c4] px-4 py-3" placeholder="Minimum order"></x-admin.field>
            <x-admin.field label="Starts at"><input type="datetime-local" name="starts_at" value="{{ old('starts_at', $coupon->starts_at?->format('Y-m-d\\TH:i')) }}" class="rounded-lg border border-[#ddd4c4] px-4 py-3"></x-admin.field>
            <x-admin.field label="Ends at"><input type="datetime-local" name="ends_at" value="{{ old('ends_at', $coupon->ends_at?->format('Y-m-d\\TH:i')) }}" class="rounded-lg border border-[#ddd4c4] px-4 py-3"></x-admin.field>
            <x-admin.check label="Active"><input type="checkbox" name="is_active" value="1" @checked(old('is_active', $coupon->is_active ?? true))></x-admin.check>
            @if ($errors->any())<p class="text-sm text-red-700 md:col-span-2">{{ $errors->first() }}</p>@endif
            <button class="rounded-lg bg-[#7a1f55] px-4 py-3 font-semibold text-white md:col-span-2">Save Coupon</button>
        </div>
    </form>
@endsection
