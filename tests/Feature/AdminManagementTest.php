<?php

use App\Models\Category;
use App\Models\Collection;
use App\Models\FashionAttribute;
use App\Models\Offer;
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

test('admin can upload multiple product images and storefront shows gallery', function () {
    Storage::fake('public');
    $this->seed();

    $admin = User::query()->where('role', 'admin')->firstOrFail();
    $category = Category::query()->firstOrFail();

    $this->actingAs($admin)
        ->post(route('admin.products.store'), [
            'category_id' => $category->id,
            'name' => 'Admin Gallery Product',
            'product_type' => 'Sharee',
            'price' => 5500,
            'sku' => 'ADMIN-GALLERY-001',
            'description' => 'A product with multiple gallery images.',
            'image_file' => UploadedFile::fake()->image('primary.jpg'),
            'image_files' => [
                UploadedFile::fake()->image('gallery-one.jpg'),
                UploadedFile::fake()->image('gallery-two.jpg'),
            ],
            'is_active' => '1',
        ])
        ->assertRedirect(route('admin.products.index'));

    $product = Product::query()
        ->with('images')
        ->where('sku', 'ADMIN-GALLERY-001')
        ->firstOrFail();

    expect($product->images)->toHaveCount(3)
        ->and($product->images->pluck('image_url')->every(fn (string $imageUrl): bool => str_starts_with($imageUrl, '/storage/products/')))->toBeTrue();

    $this->actingAs($admin)
        ->get(route('admin.products.index'))
        ->assertOk()
        ->assertSee('Admin Gallery Product')
        ->assertSee('/storage/products/', false);

    $this->get(route('products.show', $product))
        ->assertOk()
        ->assertSee('product-main-image')
        ->assertSee('data-gallery-image', false);
});

test('admin product selection forms render searchable picker modal', function () {
    $this->seed();

    $admin = User::query()->where('role', 'admin')->firstOrFail();

    $this->actingAs($admin);

    foreach ([route('admin.collections.create'), route('admin.offers.create'), route('admin.combos.create')] as $route) {
        $this->get($route)
            ->assertOk()
            ->assertSee('Choose Products')
            ->assertSee('data-product-picker', false)
            ->assertSee('data-picker-search', false)
            ->assertSee('data-picker-category', false);
    }
});

test('admin can manage fashion attributes and duplicate a product', function () {
    $this->seed();

    $admin = User::query()->where('role', 'admin')->firstOrFail();
    $product = Product::query()->with(['collections', 'images', 'variants'])->firstOrFail();

    $this->actingAs($admin)
        ->get(route('admin.fashion-attributes.index'))
        ->assertOk()
        ->assertSee('Fashion Attributes');

    $attribute = FashionAttribute::query()->where('key', 'color')->firstOrFail();

    $this->actingAs($admin)
        ->put(route('admin.fashion-attributes.update', $attribute), [
            'name' => 'Colors',
            'key' => 'color',
            'color_names' => ['Ivory', 'Wine'],
            'color_codes' => ['#fffff0', '#8b1d46'],
            'is_active' => '1',
        ])
        ->assertRedirect(route('admin.fashion-attributes.index'));

    $colors = FashionAttribute::query()->where('key', 'color')->firstOrFail()->values;

    expect($colors)->toContain(['name' => 'Ivory', 'code' => '#fffff0'])
        ->and(FashionAttribute::valuesFor('color'))->toContain('Ivory', 'Wine');

    $this->get(route('home'))
        ->assertOk()
        ->assertSee('#fffff0', false);

    $this->actingAs($admin)
        ->post(route('admin.products.duplicate', $product))
        ->assertRedirect();

    $copy = Product::query()
        ->where('name', $product->name.' Copy')
        ->with(['collections', 'images', 'variants'])
        ->firstOrFail();

    expect($copy->is_active)->toBeFalse()
        ->and($copy->sku)->not->toBe($product->sku)
        ->and($copy->slug)->not->toBe($product->slug)
        ->and($copy->images)->toHaveCount($product->images->count())
        ->and($copy->variants)->toHaveCount($product->variants->count());
});

test('offer and collection storefront pages show cover carousel and scoped filters', function () {
    $this->seed();

    $product = Product::query()->firstOrFail();

    $collection = Collection::query()->create([
        'name' => 'Seasonal Collection',
        'slug' => 'seasonal-collection',
        'banner_url' => '/images/collection-cover.jpg',
        'description' => 'Curated seasonal saree picks.',
        'is_active' => true,
    ]);
    $collection->products()->sync([$product->id]);

    $offer = Offer::query()->create([
        'title' => 'Festival Offer',
        'slug' => 'festival-offer',
        'banner_url' => '/images/offer-cover.jpg',
        'description' => 'Special boutique savings.',
        'is_active' => true,
    ]);
    $offer->products()->sync([$product->id]);

    $this->get(route('offers.index'))
        ->assertOk()
        ->assertSee('aspect-[3/1]', false)
        ->assertSee('View More')
        ->assertSee(route('offers.show', $offer), false);

    $this->get(route('offers.show', $offer))
        ->assertOk()
        ->assertSee('All Offer Products')
        ->assertSee('Apply Filters')
        ->assertSee('aspect-[3/1]', false);

    $this->get(route('collections.show', $collection))
        ->assertOk()
        ->assertSee('All Collection Products')
        ->assertSee('Apply Filters')
        ->assertSee('aspect-[3/1]', false);
});
