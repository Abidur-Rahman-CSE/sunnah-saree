<?php

use App\Models\Cart;
use App\Models\Category;
use App\Models\DeliveryChargeRule;
use App\Models\Order;
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

test('contact page shows configured phone and whatsapp numbers', function () {
    $this->seed();

    $this->get(route('pages.show', 'contact-us'))
        ->assertOk()
        ->assertSee('01985902350')
        ->assertSee('WhatsApp')
        ->assertSee('https://wa.me/8801985902350', false);
});

test('footer links to customer policy pages and pages show saved policy text', function () {
    $this->seed();

    Setting::query()->updateOrCreate(
        ['key' => 'return_policy_text'],
        ['value' => 'Return within 3 days after receiving the parcel.'],
    );

    $this->get('/')
        ->assertOk()
        ->assertSee(route('pages.show', 'return-policy'), false)
        ->assertSee(route('pages.show', 'shipping-policy'), false)
        ->assertSee(route('pages.show', 'terms-conditions'), false)
        ->assertSee(route('pages.show', 'privacy-policy'), false);

    $this->get(route('pages.show', 'return-policy'))
        ->assertOk()
        ->assertSee('Return within 3 days after receiving the parcel.');
});

test('storefront shows floating chat widget with messenger whatsapp and phone links', function () {
    $this->seed();

    $this->get('/')
        ->assertOk()
        ->assertSee('সরাসরি কথা বলুন')
        ->assertSee('https://m.me/sunnah.saree', false)
        ->assertSee('https://wa.me/8801985902350', false)
        ->assertSee('tel:01985902350', false)
        ->assertSee('https://www.facebook.com/sunnah.saree', false);
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

test('new customer account claims guest orders with matching phone number', function () {
    $this->seed();

    $product = Product::query()->with('variants')->firstOrFail();

    $this->post(route('cart.store', $product), [
        'product_variant_id' => $product->variants->first()->id,
        'quantity' => 1,
    ]);

    $this->post(route('checkout.store'), [
        'customer_name' => 'Guest Buyer',
        'customer_email' => 'guest-buyer@example.com',
        'customer_phone' => '+8801985902350',
        'shipping_division' => 'Dhaka',
        'shipping_district' => 'Dhaka',
        'shipping_area' => 'Mirpur',
        'shipping_address' => 'Mirpur, Dhaka',
        'payment_method' => 'cod',
    ])->assertRedirect();

    $order = Order::query()->where('customer_phone', '+8801985902350')->firstOrFail();

    expect($order->user_id)->toBeNull();

    $this->post(route('register.store'), [
        'name' => 'Guest Buyer',
        'phone' => '01985902350',
        'email' => 'claimed-buyer@example.com',
        'address' => 'Mirpur, Dhaka',
        'password' => 'password123',
        'password_confirmation' => 'password123',
    ])->assertRedirect(route('account.dashboard'));

    $user = User::query()->where('email', 'claimed-buyer@example.com')->firstOrFail();

    expect($order->refresh()->user_id)->toBe($user->id);

    $this->actingAs($user)
        ->get(route('account.dashboard'))
        ->assertOk()
        ->assertSee($order->order_number)
        ->assertSee('Logout');
});

test('navbar cart count uses the current logged in session cart after reload', function () {
    $this->seed();

    $user = User::query()->where('role', 'customer')->firstOrFail();
    $product = Product::query()->firstOrFail();

    $oldCart = Cart::query()->create([
        'user_id' => $user->id,
        'session_id' => 'old-session',
    ]);
    $oldCart->items()->create([
        'product_id' => $product->id,
        'product_variant_id' => null,
        'quantity' => 1,
    ]);

    $currentCart = Cart::query()->create([
        'user_id' => $user->id,
        'session_id' => 'current-session',
    ]);
    $currentCart->items()->create([
        'product_id' => $product->id,
        'product_variant_id' => null,
        'quantity' => 3,
    ]);

    $this->actingAs($user)
        ->withSession(['cart_session_id' => 'current-session'])
        ->get('/')
        ->assertOk()
        ->assertSee('data-cart-count>3</span>', false)
        ->assertDontSee('data-cart-count>1</span>', false);
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

test('category page can apply product filters', function () {
    $this->seed();

    $category = Category::query()->where('slug', 'sharee')->firstOrFail();
    $product = Product::query()
        ->where('category_id', $category->id)
        ->whereNotNull('sharee_type')
        ->firstOrFail();

    $this->get(route('categories.show', [
        'category' => $category,
        'sharee_type' => $product->sharee_type,
    ]))
        ->assertOk()
        ->assertSee($product->name);
});
