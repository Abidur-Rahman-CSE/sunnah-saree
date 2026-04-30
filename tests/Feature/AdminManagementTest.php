<?php

use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

uses(RefreshDatabase::class);

test('admin can open remaining management modules', function () {
    $this->seed();

    $admin = User::query()->where('role', 'admin')->firstOrFail();
    $product = Product::query()->firstOrFail();

    $this->actingAs($admin);

    foreach ([
        route('admin.collections.index') => 'Collections',
        route('admin.offers.index') => 'Offers',
        route('admin.combos.index') => 'Combos',
        route('admin.coupons.index') => 'Coupons',
        route('admin.banners.index') => 'Banners',
        route('admin.customers.index') => 'Customers',
        route('admin.payments.index') => 'Payments',
        route('admin.settings.edit') => 'Website Settings',
    ] as $route => $text) {
        $this->get($route)->assertOk()->assertSee($text);
    }

    $this->post(route('admin.collections.store'), [
        'name' => 'Test Collection',
        'product_ids' => [$product->id],
        'is_active' => '1',
    ])->assertRedirect(route('admin.collections.index'));

    $this->post(route('admin.offers.store'), [
        'title' => 'Test Offer',
        'product_ids' => [$product->id],
        'is_active' => '1',
    ])->assertRedirect(route('admin.offers.index'));

    $this->post(route('admin.combos.store'), [
        'name' => 'Test Combo',
        'regular_total_price' => 1000,
        'discounted_combo_price' => 800,
        'combo_stock' => 4,
        'product_ids' => [$product->id],
        'quantities' => [$product->id => 1],
        'is_active' => '1',
    ])->assertRedirect(route('admin.combos.index'));

    $this->post(route('admin.coupons.store'), [
        'code' => 'save10',
        'type' => 'percentage',
        'value' => 10,
        'minimum_order_amount' => 500,
        'is_active' => '1',
    ])->assertRedirect(route('admin.coupons.index'));

    $this->post(route('admin.banners.store'), [
        'title' => 'Promo Banner',
        'placement' => 'promotional',
        'headline' => 'Fresh picks',
        'is_active' => '1',
    ])->assertRedirect(route('admin.banners.index'));

    $this->assertDatabaseHas('collections', ['slug' => 'test-collection']);
    $this->assertDatabaseHas('offers', ['slug' => 'test-offer']);
    $this->assertDatabaseHas('combos', ['slug' => 'test-combo']);
    $this->assertDatabaseHas('coupons', ['code' => 'SAVE10']);
    $this->assertDatabaseHas('banners', ['title' => 'Promo Banner']);
});

test('admin can upload category image and print invoice', function () {
    Storage::fake('public');
    $this->seed();

    $admin = User::query()->where('role', 'admin')->firstOrFail();
    $customer = User::query()->where('role', 'customer')->firstOrFail();
    $product = Product::query()->with('variants')->firstOrFail();

    $this->actingAs($admin)
        ->post(route('admin.categories.store'), [
            'name' => 'Uploaded Category',
            'image_file' => UploadedFile::fake()->image('category.jpg'),
            'is_active' => '1',
        ])
        ->assertRedirect(route('admin.categories.index'));

    $uploadedCategory = Category::query()->where('name', 'Uploaded Category')->firstOrFail();
    expect($uploadedCategory->image_url)->toStartWith('/storage/categories/');

    $this->actingAs($customer)
        ->post(route('cart.store', $product), [
            'product_variant_id' => $product->variants->first()->id,
            'quantity' => 1,
        ]);

    $this->actingAs($customer)
        ->post(route('checkout.store'), [
            'customer_name' => 'Invoice Customer',
            'customer_email' => 'invoice@example.com',
            'customer_phone' => '+8801700000011',
            'shipping_address' => 'Dhaka',
            'payment_method' => 'cod',
        ]);

    $order = Order::query()->where('customer_phone', '+8801700000011')->firstOrFail();

    $this->actingAs($admin)
        ->get(route('admin.orders.invoice', $order))
        ->assertOk()
        ->assertSee($order->order_number)
        ->assertSee('Invoice Customer');
});
