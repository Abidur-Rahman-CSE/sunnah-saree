<?php

namespace App\Http\Controllers;

use App\Http\Requests\CheckoutRequest;
use App\Models\Cart;
use App\Models\Order;
use App\Models\Payment;
use App\Support\CartPricing;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    public function create(Request $request): View|RedirectResponse
    {
        $cart = $this->cart($request)->load('items.product.images', 'items.variant');

        if ($cart->items->isEmpty()) {
            return to_route('cart.index')->with('status', 'Add products before checkout.');
        }

        $summary = app(CartPricing::class)->summary($cart, $request);

        return view('storefront.checkout', [
            'cart' => $cart,
            ...$summary,
        ]);
    }

    public function store(CheckoutRequest $request): RedirectResponse
    {
        $cart = $this->cart($request)->load('items.product', 'items.variant');

        if ($cart->items->isEmpty()) {
            return to_route('cart.index')->with('status', 'Your cart is empty.');
        }

        foreach ($cart->items as $item) {
            if ($item->quantity > $item->product->quantity) {
                return to_route('cart.index')->withErrors([
                    'quantity' => 'Only '.$item->product->quantity.' '.$item->product->name.' items are available.',
                ]);
            }
        }

        $order = DB::transaction(function () use ($request, $cart): Order {
            $summary = app(CartPricing::class)->summary($cart, $request);

            $order = Order::query()->create([
                ...$request->safe()->only(['customer_name', 'customer_email', 'customer_phone', 'shipping_address', 'payment_method']),
                'user_id' => $request->user()?->id,
                'order_number' => 'SSG-'.now()->format('Ymd').'-'.str()->upper(str()->random(6)),
                'subtotal' => $summary['subtotal'],
                'delivery_charge' => $summary['deliveryCharge'],
                'discount_amount' => $summary['discountAmount'],
                'total' => $summary['total'],
                'payment_status' => 'pending',
                'status' => 'pending',
            ]);

            foreach ($cart->items as $item) {
                $order->items()->create([
                    'product_id' => $item->product_id,
                    'product_name' => $item->product->name,
                    'variant_name' => $item->variant?->color,
                    'unit_price' => $item->product->finalPrice(),
                    'quantity' => $item->quantity,
                    'total' => $item->lineTotal(),
                ]);

                $item->product->decrement('quantity', $item->quantity);
            }

            Payment::query()->create([
                'order_id' => $order->id,
                'method' => $order->payment_method,
                'status' => 'pending',
                'amount' => $order->total,
                'payload' => ['gateway' => $order->payment_method === 'online' ? 'placeholder' : 'cash_on_delivery'],
            ]);

            $cart->items()->delete();
            $request->session()->forget('coupon_id');

            return $order;
        });

        return to_route('checkout.success', ['order' => $order->order_number])->with('status', 'Order placed successfully.');
    }

    public function success(Order $order): View
    {
        return view('storefront.order-success', [
            'order' => $order->load('items', 'payment'),
        ]);
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
