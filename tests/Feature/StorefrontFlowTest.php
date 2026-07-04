<?php

use App\Models\Category;
use App\Models\DeliveryChargeRule;
use App\Models\Product;
use App\Models\Setting;
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
        'shipping_division' => 'Dhaka',
        'shipping_district' => 'Dhaka',
        'shipping_area' => 'Mirpur',
        'shipping_address' => 'Mirpur, Dhaka',
        'payment_method' => 'cod',
    ])->assertRedirect();

    $this->assertDatabaseHas('orders', [
        'customer_phone' => '+8801712345678',
        'shipping_division' => 'Dhaka',
        'shipping_district' => 'Dhaka',
        'shipping_area' => 'Mirpur',
        'payment_method' => 'cod',
        'payment_status' => 'pending',
    ]);
});

test('checkout applies matching area delivery charge', function () {
    $this->seed();

    Setting::query()->updateOrCreate(['key' => 'free_delivery_minimum_amount'], ['value' => '999999']);
    DeliveryChargeRule::query()->create([
        'scope' => 'area',
        'locations' => ['Mirpur'],
        'amount' => 120,
        'is_active' => true,
    ]);

    $product = Product::query()->with('variants')->firstOrFail();

    $this->post(route('cart.store', $product), [
        'product_variant_id' => $product->variants->first()->id,
        'quantity' => 1,
    ]);

    $this->post(route('checkout.store'), [
        'customer_name' => 'Delivery Rule Customer',
        'customer_email' => 'delivery@example.com',
        'customer_phone' => '+8801712345600',
        'shipping_division' => 'Dhaka',
        'shipping_district' => 'Dhaka',
        'shipping_area' => 'Mirpur',
        'shipping_address' => 'Mirpur, Dhaka',
        'payment_method' => 'cod',
    ])->assertRedirect();

    $this->assertDatabaseHas('orders', [
        'customer_phone' => '+8801712345600',
        'delivery_charge' => 120,
    ]);
});

test('checkout applies area delivery charge before district fallback', function () {
    $this->seed();

    Setting::query()->updateOrCreate(['key' => 'free_delivery_minimum_amount'], ['value' => '999999']);
    DeliveryChargeRule::query()->create([
        'scope' => 'district',
        'locations' => ['Dhaka'],
        'amount' => 120,
        'is_active' => true,
    ]);
    DeliveryChargeRule::query()->create([
        'scope' => 'area',
        'locations' => ['Tejgaon'],
        'amount' => 100,
        'is_active' => true,
    ]);

    $product = Product::query()->with('variants')->firstOrFail();

    $this->post(route('cart.store', $product), [
        'product_variant_id' => $product->variants->first()->id,
        'quantity' => 1,
    ]);

    $this->post(route('checkout.store'), [
        'customer_name' => 'Priority Rule Customer',
        'customer_email' => 'priority@example.com',
        'customer_phone' => '+8801712345611',
        'shipping_division' => 'Dhaka',
        'shipping_district' => 'Dhaka',
        'shipping_area' => 'Tejgaon',
        'shipping_address' => 'Tejgaon, Dhaka',
        'payment_method' => 'cod',
    ])->assertRedirect();

    $this->assertDatabaseHas('orders', [
        'customer_phone' => '+8801712345611',
        'delivery_charge' => 100,
    ]);
});

test('checkout does not show delivery charge before area selection', function () {
    $this->seed();

    Setting::query()->updateOrCreate(['key' => 'free_delivery_minimum_amount'], ['value' => '999999']);

    $product = Product::query()->with('variants')->firstOrFail();

    $this->post(route('cart.store', $product), [
        'product_variant_id' => $product->variants->first()->id,
        'quantity' => 1,
    ]);

    $this->get(route('checkout.create'))
        ->assertOk()
        ->assertSee('data-delivery-charge>৳0', false);
});

test('home essentials hide inactive categories', function () {
    $this->seed();

    Category::query()
        ->where('slug', 'organic-oil')
        ->update(['is_active' => false]);

    $this->get('/')
        ->assertOk()
        ->assertDontSee('Organic Oil')
        ->assertSee('Ornaments');
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
