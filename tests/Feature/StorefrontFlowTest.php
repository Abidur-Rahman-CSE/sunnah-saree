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

test('add to cart can update the navbar count without leaving the page', function () {
    $this->seed();

    $product = Product::query()->with('variants')->firstOrFail();

    $this->get(route('products.show', $product))
        ->assertOk()
        ->assertSee('data-add-to-cart-form', false)
        ->assertSee('data-cart-count', false)
        ->assertSee('data-cart-target', false);

    $this->postJson(route('cart.store', $product), [
        'product_variant_id' => $product->variants->first()->id,
        'quantity' => 2,
    ])
        ->assertOk()
        ->assertJson([
            'message' => 'Product added to cart.',
            'added_quantity' => 2,
            'cart_count' => 2,
        ]);

    $this->assertDatabaseHas('cart_items', [
        'product_id' => $product->id,
        'quantity' => 2,
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

test('product filters are scoped by selected category attributes', function () {
    $this->seed();

    $this->get(route('products.index', ['category' => 'sharee']))
        ->assertOk()
        ->assertSee('Sharee Type')
        ->assertSee('Fabric')
        ->assertSee('Color');

    $this->get(route('products.index', ['category' => 'organic-oil']))
        ->assertOk()
        ->assertDontSee('Sharee Type')
        ->assertDontSee('Fabric')
        ->assertDontSee('Work Type');
});
