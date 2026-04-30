@extends('layouts.admin', ['heading' => 'Website Settings'])

@section('content')
    <form action="{{ route('admin.settings.update') }}" method="POST" class="rounded-lg border border-[#e5ded0] bg-white p-6 shadow-sm">
        @csrf @method('PUT')
        <div class="grid gap-4 md:grid-cols-2">
            <input name="website_name" value="{{ old('website_name', $settings['website_name'] ?? 'Sunnah Sharee Ghar') }}" class="rounded-lg border border-[#ddd4c4] px-4 py-3" placeholder="Website name">
            <input name="phone" value="{{ old('phone', $settings['phone'] ?? '') }}" class="rounded-lg border border-[#ddd4c4] px-4 py-3" placeholder="Phone">
            <input name="email" value="{{ old('email', $settings['email'] ?? '') }}" class="rounded-lg border border-[#ddd4c4] px-4 py-3" placeholder="Email">
            <input name="facebook_page_link" value="{{ old('facebook_page_link', $settings['facebook_page_link'] ?? '') }}" class="rounded-lg border border-[#ddd4c4] px-4 py-3" placeholder="Facebook page link">
            <input name="delivery_charge" value="{{ old('delivery_charge', $settings['delivery_charge'] ?? 80) }}" class="rounded-lg border border-[#ddd4c4] px-4 py-3" placeholder="Delivery charge">
            <input name="free_delivery_minimum_amount" value="{{ old('free_delivery_minimum_amount', $settings['free_delivery_minimum_amount'] ?? 5000) }}" class="rounded-lg border border-[#ddd4c4] px-4 py-3" placeholder="Free delivery minimum">
            <label><input type="checkbox" name="cod_enabled" value="1" @checked(old('cod_enabled', ($settings['cod_enabled'] ?? '1') === '1'))> Cash on Delivery enabled</label>
            <label><input type="checkbox" name="online_payment_enabled" value="1" @checked(old('online_payment_enabled', ($settings['online_payment_enabled'] ?? '1') === '1'))> Online payment placeholder enabled</label>
            <textarea name="address" rows="3" class="rounded-lg border border-[#ddd4c4] px-4 py-3 md:col-span-2" placeholder="Address">{{ old('address', $settings['address'] ?? '') }}</textarea>
            <textarea name="return_policy_text" rows="4" class="rounded-lg border border-[#ddd4c4] px-4 py-3" placeholder="Return policy">{{ old('return_policy_text', $settings['return_policy_text'] ?? '') }}</textarea>
            <textarea name="shipping_policy_text" rows="4" class="rounded-lg border border-[#ddd4c4] px-4 py-3" placeholder="Shipping policy">{{ old('shipping_policy_text', $settings['shipping_policy_text'] ?? '') }}</textarea>
            <textarea name="terms_and_conditions" rows="4" class="rounded-lg border border-[#ddd4c4] px-4 py-3" placeholder="Terms and conditions">{{ old('terms_and_conditions', $settings['terms_and_conditions'] ?? '') }}</textarea>
            <textarea name="privacy_policy" rows="4" class="rounded-lg border border-[#ddd4c4] px-4 py-3" placeholder="Privacy policy">{{ old('privacy_policy', $settings['privacy_policy'] ?? '') }}</textarea>
            @if ($errors->any())<p class="text-sm text-red-700 md:col-span-2">{{ $errors->first() }}</p>@endif
            <button class="rounded-lg bg-[#7a1f55] px-4 py-3 font-semibold text-white md:col-span-2">Save Settings</button>
        </div>
    </form>
@endsection
