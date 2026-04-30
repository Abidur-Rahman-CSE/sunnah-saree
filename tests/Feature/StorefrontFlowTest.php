<?php

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('customer can browse products and place a cash on delivery order', function () {
    $this->seed();

    $product = Product::query()->with('variants')->firstOrFail();

    $this->get('/')
        ->assertOk()
        ->assertSee('Sunnah Sharee Ghar')
        ->assertSee('Best Sellers');

    $this->get(route('products.index', ['sharee_type' => $product->sharee_type]))
        ->assertOk()
        ->assertSee($product->name);

    $this->post(route('cart.store', $product), [
        'product_variant_id' => $product->variants->first()->id,
        'quantity' => 1,
    ])->assertRedirect(route('cart.index'));

    $this->get(route('cart.index'))
        ->assertOk()
        ->assertSee($product->name);

    $this->post(route('checkout.store'), [
        'customer_name' => 'Ayesha Rahman',
        'customer_email' => 'ayesha@example.com',
        'customer_phone' => '+8801712345678',
        'shipping_address' => 'Mirpur, Dhaka',
        'payment_method' => 'cod',
    ])->assertRedirect();

    $this->assertDatabaseHas('orders', [
        'customer_phone' => '+8801712345678',
        'payment_method' => 'cod',
        'payment_status' => 'pending',
    ]);
});

test('admin can access the dashboard', function () {
    $this->seed();

    $admin = User::query()->where('role', 'admin')->firstOrFail();

    $this->actingAs($admin)
        ->get(route('admin.dashboard'))
        ->assertOk()
        ->assertSee('Dashboard');
});
