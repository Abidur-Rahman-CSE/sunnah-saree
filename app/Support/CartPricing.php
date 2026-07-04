<?php

namespace App\Support;

use App\Models\Cart;
use App\Models\Coupon;
use App\Models\DeliveryChargeRule;
use App\Models\Setting;
use Illuminate\Http\Request;

class CartPricing
{
    /**
     * @return array{subtotal: float, deliveryCharge: float, discountAmount: float, total: float, coupon: Coupon|null}
     */
    public function summary(Cart $cart, Request $request): array
    {
        $subtotal = $cart->subtotal();
        $coupon = $this->activeCoupon($request);
        $discountAmount = $coupon ? $this->discountAmount($coupon, $subtotal) : 0.0;
        $deliveryCharge = 0.0;

        if ($cart->items->isNotEmpty() && $subtotal < (float) Setting::valueFor('free_delivery_minimum_amount', 5000)) {
            $deliveryCharge = DeliveryChargeRule::amountFor(
                $request->input('shipping_division'),
                $request->input('shipping_district'),
                $request->input('shipping_area'),
            ) ?? (float) Setting::valueFor('delivery_charge', 80);
        }

        return [
            'subtotal' => $subtotal,
            'deliveryCharge' => $deliveryCharge,
            'discountAmount' => min($discountAmount, $subtotal),
            'total' => max(0, $subtotal + $deliveryCharge - min($discountAmount, $subtotal)),
            'coupon' => $coupon,
        ];
    }

    public function activeCoupon(Request $request): ?Coupon
    {
        $couponId = $request->session()->get('coupon_id');

        if (! $couponId) {
            return null;
        }

        return Coupon::query()
            ->whereKey($couponId)
            ->where('is_active', true)
            ->where(fn ($query) => $query->whereNull('starts_at')->orWhere('starts_at', '<=', now()))
            ->where(fn ($query) => $query->whereNull('ends_at')->orWhere('ends_at', '>=', now()))
            ->first();
    }

    public function discountAmount(Coupon $coupon, float $subtotal): float
    {
        if ($subtotal < (float) $coupon->minimum_order_amount) {
            return 0.0;
        }

        if ($coupon->type === 'percentage') {
            return $subtotal * ((float) $coupon->value / 100);
        }

        return (float) $coupon->value;
    }
}
