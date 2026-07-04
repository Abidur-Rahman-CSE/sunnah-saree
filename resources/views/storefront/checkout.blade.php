@extends('layouts.storefront', ['title' => 'Checkout'])

@section('content')
    @php
        $selectedDivision = old('shipping_division');
        $selectedDistrict = old('shipping_district');
        $selectedArea = old('shipping_area');
        $visibleDeliveryCharge = filled($selectedArea) ? $deliveryCharge : 0;
        $visibleTotal = max(0, $subtotal + $visibleDeliveryCharge - min($discountAmount, $subtotal));
    @endphp

    <section class="mx-auto max-w-7xl px-4 py-8">
        <x-section-title title="Checkout" subtitle="Cash on delivery is ready. Online payment has placeholder structure for gateway integration." />
        <form action="{{ route('checkout.store') }}" method="POST" class="mt-8 grid min-w-0 gap-6 lg:grid-cols-[minmax(0,1fr)_360px]">
            @csrf
            <div class="min-w-0 rounded-lg border border-[#eadcc3] bg-white p-5 shadow-sm" data-location-picker>
                <h2 class="font-serif text-2xl font-bold">Customer Info</h2>
                <div class="mt-5 grid gap-4 md:grid-cols-2">
                    <x-admin.field label="Name"><input name="customer_name" value="{{ old('customer_name', auth()->user()?->name) }}" class="w-full min-w-0 rounded-lg border border-[#dfcda9] px-4 py-3" placeholder="Name"></x-admin.field>
                    <x-admin.field label="Phone"><input name="customer_phone" value="{{ old('customer_phone', auth()->user()?->phone) }}" class="w-full min-w-0 rounded-lg border border-[#dfcda9] px-4 py-3" placeholder="Phone"></x-admin.field>
                    <x-admin.field label="Email" span><input name="customer_email" value="{{ old('customer_email', auth()->user()?->email) }}" class="w-full min-w-0 rounded-lg border border-[#dfcda9] px-4 py-3" placeholder="Email"></x-admin.field>
                    <x-admin.field label="Division">
                        <input name="shipping_division" value="{{ $selectedDivision }}" list="checkout-division-options" class="w-full min-w-0 rounded-lg border border-[#dfcda9] bg-white px-4 py-3" placeholder="Search division" autocomplete="off" data-division-select>
                        <datalist id="checkout-division-options">
                            @foreach (array_keys($locations['districts']) as $division)
                                <option value="{{ $division }}"></option>
                            @endforeach
                        </datalist>
                    </x-admin.field>
                    <x-admin.field label="District">
                        <input name="shipping_district" value="{{ $selectedDistrict }}" list="checkout-district-options" class="w-full min-w-0 rounded-lg border border-[#dfcda9] bg-white px-4 py-3" placeholder="Search district" autocomplete="off" data-district-select data-selected="{{ $selectedDistrict }}">
                        <datalist id="checkout-district-options"></datalist>
                    </x-admin.field>
                    <x-admin.field label="Area" span>
                        <input name="shipping_area" value="{{ $selectedArea }}" list="checkout-area-options" class="w-full min-w-0 rounded-lg border border-[#dfcda9] bg-white px-4 py-3" placeholder="Search area" autocomplete="off" data-area-select data-selected="{{ $selectedArea }}">
                        <datalist id="checkout-area-options"></datalist>
                    </x-admin.field>
                    <x-admin.field label="Detailed address" span><textarea name="shipping_address" class="w-full min-w-0 rounded-lg border border-[#dfcda9] px-4 py-3" rows="4" placeholder="House, road, landmark">{{ old('shipping_address', auth()->user()?->address) }}</textarea></x-admin.field>
                </div>
                @if ($errors->any())
                    <div class="mt-4 rounded-lg bg-red-50 p-4 text-sm text-red-700">{{ $errors->first() }}</div>
                @endif
                <h2 class="mt-8 font-serif text-2xl font-bold">Payment Method</h2>
                <div class="mt-4 grid gap-3">
                    <label class="rounded-lg border border-[#dfcda9] p-4"><input type="radio" name="payment_method" value="cod" checked> Cash on Delivery</label>
                    <label class="rounded-lg border border-[#dfcda9] p-4"><input type="radio" name="payment_method" value="online"> Online payment gateway placeholder</label>
                </div>
            </div>
            <aside class="h-fit rounded-lg border border-[#eadcc3] bg-white p-5 shadow-sm">
                <h2 class="font-serif text-2xl font-bold">Summary</h2>
                <div class="mt-4 space-y-3 text-sm">
                    @foreach ($cart->items as $item)
                        <div class="flex justify-between gap-3"><span class="min-w-0">{{ $item->product->name }} x {{ $item->quantity }}</span><span class="shrink-0">৳{{ number_format($item->lineTotal()) }}</span></div>
                    @endforeach
                </div>
                <div class="mt-5 grid gap-3 border-t border-[#eadcc3] pt-4 text-sm">
                    <div class="flex justify-between"><span>Subtotal</span><span>৳{{ number_format($subtotal) }}</span></div>
                    <div class="flex justify-between"><span>Delivery</span><span data-delivery-charge>৳{{ number_format($visibleDeliveryCharge) }}</span></div>
                    <div class="flex justify-between"><span>Discount{{ $coupon ? ' · '.$coupon->code : '' }}</span><span>-৳{{ number_format($discountAmount) }}</span></div>
                    <div class="flex justify-between text-lg font-bold" data-order-summary data-subtotal="{{ $subtotal }}" data-discount="{{ $discountAmount }}" data-default-delivery="{{ $deliveryCharge }}"><span>Total</span><span data-order-total>৳{{ number_format($visibleTotal) }}</span></div>
                </div>
                <button class="mt-6 w-full rounded-lg bg-[#7a1f55] px-4 py-3 font-semibold text-white">Place Order</button>
            </aside>
        </form>
    </section>
    <script>
        const checkoutLocations = {{ Illuminate\Support\Js::from($locations) }};
        const checkoutDeliveryRules = {{ Illuminate\Support\Js::from($deliveryChargeRules->map(fn ($rule): array => [
            'scope' => $rule->scope,
            'locations' => $rule->locations,
            'amount' => (float) $rule->amount,
        ])->values()) }};

        document.querySelectorAll('[data-location-picker]').forEach((picker) => {
            const locations = checkoutLocations;
            const divisionSelect = picker.querySelector('[data-division-select]');
            const districtSelect = picker.querySelector('[data-district-select]');
            const areaSelect = picker.querySelector('[data-area-select]');
            const districtOptions = picker.querySelector('#checkout-district-options');
            const areaOptions = picker.querySelector('#checkout-area-options');
            const summary = document.querySelector('[data-order-summary]');
            const deliveryCharge = document.querySelector('[data-delivery-charge]');
            const orderTotal = document.querySelector('[data-order-total]');
            const formatTaka = (amount) => `৳${Math.round(amount).toLocaleString()}`;

            const matchingDeliveryCharge = () => {
                if (! areaSelect.value) {
                    return 0;
                }

                const targets = {
                    area: areaSelect.value,
                    district: districtSelect.value,
                    division: divisionSelect.value,
                };

                for (const scope of ['area', 'district', 'division']) {
                    const target = targets[scope];
                    const rule = checkoutDeliveryRules.find((item) => item.scope === scope && item.locations.includes(target));

                    if (rule) {
                        return Number(rule.amount);
                    }
                }

                return Number(summary?.dataset.defaultDelivery || 0);
            };

            const refreshSummary = () => {
                if (! summary || ! deliveryCharge || ! orderTotal) {
                    return;
                }

                const subtotal = Number(summary.dataset.subtotal || 0);
                const discount = Number(summary.dataset.discount || 0);
                const charge = matchingDeliveryCharge();

                deliveryCharge.textContent = formatTaka(charge);
                orderTotal.textContent = formatTaka(Math.max(0, subtotal + charge - Math.min(discount, subtotal)));
            };

            const fillOptions = (list, options) => {
                if (! list) {
                    return;
                }

                list.innerHTML = '';

                options.forEach((option) => {
                    list.append(new Option(option));
                });
            };

            const syncDistricts = () => {
                const districts = locations.districts?.[divisionSelect.value] || [];

                fillOptions(districtOptions, districts);
                syncAreas();
                refreshSummary();
            };

            const syncAreas = () => {
                const areas = locations.areas?.[districtSelect.value] || [];

                fillOptions(areaOptions, areas);
                refreshSummary();
            };

            divisionSelect?.addEventListener('input', () => {
                districtSelect.value = '';
                areaSelect.value = '';
                syncDistricts();
            });

            districtSelect?.addEventListener('input', () => {
                areaSelect.value = '';
                syncAreas();
            });

            areaSelect?.addEventListener('input', refreshSummary);

            syncDistricts();
        });
    </script>
@endsection
