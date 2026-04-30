<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Coupon;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Support\CartPricing;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index(Request $request): View
    {
        $cart = $this->cart($request);
        $cart->load('items.product.images', 'items.variant');

        $summary = app(CartPricing::class)->summary($cart, $request);

        return view('storefront.cart', [
            'cart' => $cart,
            ...$summary,
        ]);
    }

    public function store(Request $request, Product $product): RedirectResponse
    {
        $validated = $request->validate([
            'product_variant_id' => ['nullable', 'exists:product_variants,id'],
            'quantity' => ['required', 'integer', 'min:1', 'max:20'],
        ]);

        $cart = $this->cart($request);
        $variantId = $validated['product_variant_id'] ?? $product->variants()->first()?->id;
        $variant = $variantId ? ProductVariant::query()->where('product_id', $product->id)->findOrFail($variantId) : null;

        $item = $cart->items()->firstOrNew([
            'product_id' => $product->id,
            'product_variant_id' => $variant?->id,
        ]);

        $item->quantity = $item->exists ? $item->quantity + (int) $validated['quantity'] : (int) $validated['quantity'];
        $item->save();

        return to_route('cart.index')->with('status', 'Product added to cart.');
    }

    public function update(Request $request, int $cartItem): RedirectResponse
    {
        $validated = $request->validate([
            'quantity' => ['required', 'integer', 'min:1', 'max:20'],
        ]);

        $this->cart($request)->items()->findOrFail($cartItem)->update($validated);

        return back()->with('status', 'Cart updated.');
    }

    public function destroy(Request $request, int $cartItem): RedirectResponse
    {
        $this->cart($request)->items()->findOrFail($cartItem)->delete();

        return back()->with('status', 'Item removed.');
    }

    public function applyCoupon(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'coupon_code' => ['required', 'string', 'max:255'],
        ]);

        $coupon = Coupon::query()
            ->where('code', str($validated['coupon_code'])->upper()->toString())
            ->where('is_active', true)
            ->where(fn ($query) => $query->whereNull('starts_at')->orWhere('starts_at', '<=', now()))
            ->where(fn ($query) => $query->whereNull('ends_at')->orWhere('ends_at', '>=', now()))
            ->first();

        if (! $coupon) {
            return back()->withErrors(['coupon_code' => 'This coupon is not available.']);
        }

        $cart = $this->cart($request)->load('items.product');
        $discountAmount = app(CartPricing::class)->discountAmount($coupon, $cart->subtotal());

        if ($discountAmount <= 0) {
            return back()->withErrors(['coupon_code' => 'This coupon needs a higher order subtotal.']);
        }

        $request->session()->put('coupon_id', $coupon->id);

        return back()->with('status', 'Coupon applied.');
    }

    public function removeCoupon(Request $request): RedirectResponse
    {
        $request->session()->forget('coupon_id');

        return back()->with('status', 'Coupon removed.');
    }

    private function cart(Request $request): Cart
    {
        if (! $request->session()->has('cart_session_id')) {
            $request->session()->put('cart_session_id', (string) str()->uuid());
        }

        return Cart::query()->firstOrCreate([
            'user_id' => $request->user()?->id,
            'session_id' => $request->session()->get('cart_session_id'),
        ]);
    }
}
