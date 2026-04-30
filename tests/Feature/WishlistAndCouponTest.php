<?php

use App\Models\Coupon;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('customer can add and remove wishlist products', function () {
    $this->seed();

    $customer = User::query()->where('role', 'customer')->firstOrFail();
    $product = Product::query()->firstOrFail();

    $this->actingAs($customer)
        ->post(route('wishlist.store', $product))
        ->assertRedirect();

    $this->assertDatabaseHas('wishlists', [
        'user_id' => $customer->id,
        'product_id' => $product->id,
    ]);

    $this->actingAs($customer)
        ->get(route('account.wishlist.index'))
        ->assertOk()
        ->assertSee($product->name);

    $this->actingAs($customer)
        ->delete(route('wishlist.destroy', $product))
        ->assertRedirect();

    $this->assertDatabaseMissing('wishlists', [
        'user_id' => $customer->id,
        'product_id' => $product->id,
    ]);
});

test('customer can apply coupon and order stores discount', function () {
    $this->seed();

    $product = Product::query()->with('variants')->whereNotNull('discount_price')->firstOrFail();

    Coupon::query()->create([
        'code' => 'SAVE20',
        'type' => 'percentage',
        'value' => 20,
        'minimum_order_amount' => 100,
        'is_active' => true,
    ]);

    $this->post(route('cart.store', $product), [
        'product_variant_id' => $product->variants->first()->id,
        'quantity' => 1,
    ])->assertRedirect(route('cart.index'));

    $this->post(route('cart.coupon.apply'), [
        'coupon_code' => 'save20',
    ])->assertRedirect();

    $this->get(route('cart.index'))
        ->assertOk()
        ->assertSee('SAVE20')
        ->assertSee('Coupon discount');

    $this->post(route('checkout.store'), [
        'customer_name' => 'Nusrat Jahan',
        'customer_email' => 'nusrat@example.com',
        'customer_phone' => '+8801712121212',
        'shipping_address' => 'Uttara, Dhaka',
        'payment_method' => 'cod',
    ])->assertRedirect();

    $this->assertDatabaseHas('orders', [
        'customer_phone' => '+8801712121212',
        'discount_amount' => $product->finalPrice() * 0.2,
    ]);
});
