<?php

use App\Models\Banner;
use App\Models\Category;
use App\Models\Collection;
use App\Models\DeliveryChargeRule;
use App\Models\FashionAttribute;
use App\Models\Offer;
use App\Models\Order;
use App\Models\Product;
use App\Models\Setting;
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
        route('admin.delivery-charge-rules.index') => 'Delivery Rules',
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

    $this->assertDatabaseHas('collections', ['slug' => 'test-collection']);
    $this->assertDatabaseHas('offers', ['slug' => 'test-offer']);
    $this->assertDatabaseHas('combos', ['slug' => 'test-combo']);
    $this->assertDatabaseHas('coupons', ['code' => 'SAVE10']);
});

test('banner placement uses available dropdown and locks after creation', function () {
    $this->seed();

    $admin = User::query()->where('role', 'admin')->firstOrFail();
    $heroBanner = Banner::query()->where('placement', 'hero')->firstOrFail();

    $this->actingAs($admin)
        ->get(route('admin.banners.create'))
        ->assertOk()
        ->assertSee('No placement available')
        ->assertSee('All single-use banner placements already have banners')
        ->assertDontSee('<option value="hero"', false);

    $this->actingAs($admin)
        ->get(route('admin.banners.edit', $heroBanner))
        ->assertOk()
        ->assertSee('Homepage hero banner')
        ->assertSee('Placement is locked after creation')
        ->assertSee('name="placement" value="hero"', false)
        ->assertSee('disabled class=', false);

    Banner::query()->delete();

    $this->actingAs($admin)
        ->get(route('admin.banners.create'))
        ->assertOk()
        ->assertSee('<option value="hero"', false)
        ->assertSee('Only one banner can use this placement');
});

test('admin can manage announcement bar without default delivery charge field', function () {
    $this->seed();

    $admin = User::query()->where('role', 'admin')->firstOrFail();

    $this->actingAs($admin)
        ->get(route('admin.settings.edit'))
        ->assertOk()
        ->assertSee('Announcement bar text')
        ->assertDontSee('Default delivery charge');

    $this->actingAs($admin)
        ->put(route('admin.settings.update'), [
            'website_name' => 'Sunnah Sharee Ghar',
            'phone' => '+8801700000000',
            'email' => 'care@sunnahshareeghar.com',
            'facebook_page_link' => 'https://facebook.com/sunnahshareeghar',
            'announcement_bar_text' => 'Fast delivery in Dhaka • Cash on delivery • Easy exchange support',
            'free_delivery_minimum_amount' => '5000',
            'cod_enabled' => '1',
            'online_payment_enabled' => '1',
            'address' => 'Dhaka, Bangladesh',
        ])
        ->assertRedirect(route('admin.settings.edit'));

    expect(Setting::valueFor('announcement_bar_text'))->toBe('Fast delivery in Dhaka • Cash on delivery • Easy exchange support');

    $this->get(route('home'))
        ->assertOk()
        ->assertSee('Fast delivery in Dhaka')
        ->assertSee('Cash on delivery')
        ->assertSee('Easy exchange support');
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
            'shipping_division' => 'Dhaka',
            'shipping_district' => 'Dhaka',
            'shipping_area' => 'Uttara',
            'shipping_address' => 'Dhaka',
            'payment_method' => 'cod',
        ]);

    $order = Order::query()
        ->with('items.product.images')
        ->where('customer_phone', '+8801700000011')
        ->firstOrFail();
    $orderItem = $order->items->first();

    $this->actingAs($admin)
        ->get(route('admin.orders.show', $order))
        ->assertOk()
        ->assertSee('Customer Info')
        ->assertSee('Invoice Customer')
        ->assertSee('+8801700000011')
        ->assertSee('invoice@example.com')
        ->assertSee('Uttara, Dhaka, Dhaka')
        ->assertSee('Dhaka')
        ->assertSee('Product ID: '.$orderItem->product_id)
        ->assertSee($orderItem->product->primaryImage())
        ->assertSee('data-product-details-trigger', false)
        ->assertSee('Go to product')
        ->assertSee('Go to Edit')
        ->assertSee(route('products.show', $orderItem->product), false)
        ->assertSee(route('admin.products.edit', $orderItem->product), false);

    $this->actingAs($admin)
        ->get(route('admin.orders.invoice', $order))
        ->assertOk()
        ->assertSee($order->order_number)
        ->assertSee('Invoice Customer');
});

test('admin can manage delivery charge rules', function () {
    $this->seed();

    $admin = User::query()->where('role', 'admin')->firstOrFail();

    $this->actingAs($admin)
        ->put(route('admin.delivery-charge-rules.update'), [
            'delivery_charge_rules' => [
                [
                    'scope' => 'division',
                    'locations' => ['Chattogram'],
                    'amount' => 100,
                    'is_active' => '1',
                ],
                [
                    'scope' => 'area',
                    'locations' => ['Savar', 'Mirpur'],
                    'amount' => 150,
                    'is_active' => '1',
                ],
            ],
        ])
        ->assertRedirect(route('admin.delivery-charge-rules.index'));

    expect(DeliveryChargeRule::query()->count())->toBe(2);

    $this->assertDatabaseHas('delivery_charge_rules', [
        'scope' => 'area',
        'amount' => 150,
        'is_active' => true,
    ]);
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
            'quantity' => 9,
            'stock_alert_quantity' => 3,
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

    $image = $product->images->first();
    $storedPath = str_replace('/storage/', '', $image->image_url);

    Storage::disk('public')->assertExists($storedPath);

    $this->actingAs($admin)
        ->delete(route('admin.product-images.destroy', $image))
        ->assertRedirect(route('admin.products.edit', $product));

    Storage::disk('public')->assertMissing($storedPath);
    $this->assertDatabaseMissing('product_images', ['id' => $image->id]);

    $product->refresh()->load('images');

    expect($product->images)->toHaveCount(2);

    $this->actingAs($admin)
        ->get(route('admin.products.index'))
        ->assertOk()
        ->assertSee('Admin Gallery Product')
        ->assertSee('#'.$product->id)
        ->assertSee('/storage/products/', false);

    $this->actingAs($admin)
        ->get(route('admin.products.index', ['search' => (string) $product->id]))
        ->assertOk()
        ->assertSee('Admin Gallery Product');

    $this->get(route('products.show', $product))
        ->assertOk()
        ->assertSee('product-main-image')
        ->assertSee('data-gallery-image', false);
});

test('admin product list defaults to active products', function () {
    $this->seed();

    $admin = User::query()->where('role', 'admin')->firstOrFail();
    $inactiveProduct = Product::query()->firstOrFail();
    $inactiveProduct->update(['is_active' => false]);

    $this->actingAs($admin)
        ->get(route('admin.products.index'))
        ->assertOk()
        ->assertSee('value="active" selected', false)
        ->assertDontSee($inactiveProduct->name);

    $this->actingAs($admin)
        ->get(route('admin.products.index', ['status' => '']))
        ->assertOk()
        ->assertSee($inactiveProduct->name);
});

test('admin product slug and sku are generated from name when left blank', function () {
    $this->seed();

    $admin = User::query()->where('role', 'admin')->firstOrFail();
    $category = Category::query()->firstOrFail();

    $this->actingAs($admin)
        ->post(route('admin.products.store'), [
            'category_id' => $category->id,
            'name' => 'Auto SKU Sharee',
            'product_type' => 'fashion',
            'price' => 3200,
            'quantity' => 5,
            'stock_alert_quantity' => 2,
            'description' => 'A product with generated slug and sku.',
            'is_active' => '1',
        ])
        ->assertRedirect(route('admin.products.index'));

    $this->actingAs($admin)
        ->post(route('admin.products.store'), [
            'category_id' => $category->id,
            'name' => 'Auto SKU Sharee',
            'product_type' => 'fashion',
            'price' => 3300,
            'quantity' => 6,
            'stock_alert_quantity' => 2,
            'description' => 'Another product with generated slug and sku.',
            'is_active' => '1',
        ])
        ->assertRedirect(route('admin.products.index'));

    $this->assertDatabaseHas('products', [
        'name' => 'Auto SKU Sharee',
        'slug' => 'auto-sku-sharee',
        'sku' => 'AUTO-SKU-SHAREE',
    ]);

    $this->assertDatabaseHas('products', [
        'name' => 'Auto SKU Sharee',
        'slug' => 'auto-sku-sharee-2',
        'sku' => 'AUTO-SKU-SHAREE-2',
    ]);
});

test('admin can link separate products as storefront product variants', function () {
    $this->seed();

    $admin = User::query()->where('role', 'admin')->firstOrFail();
    $product = Product::query()->whereNotNull('color')->firstOrFail();
    $variantProduct = Product::query()
        ->whereKeyNot($product->id)
        ->where('category_id', $product->category_id)
        ->firstOrFail();

    $this->actingAs($admin)
        ->put(route('admin.products.update', $product), [
            'category_id' => $product->category_id,
            'name' => $product->name,
            'slug' => $product->slug,
            'product_type' => $product->product_type,
            'price' => $product->price,
            'discount_price' => $product->discount_price,
            'sku' => $product->sku,
            'quantity' => $product->quantity,
            'stock_alert_quantity' => $product->stock_alert_quantity,
            'description' => $product->description,
            'sharee_type' => $product->sharee_type,
            'fabric' => $product->fabric,
            'work_type' => $product->work_type,
            'color' => $product->color,
            'occasion' => $product->occasion,
            'product_ids' => [$variantProduct->id],
            'is_active' => '1',
        ])
        ->assertRedirect(route('admin.products.index'));

    $this->assertDatabaseHas('product_variant_links', [
        'product_id' => $product->id,
        'variant_product_id' => $variantProduct->id,
    ]);

    $this->get(route('products.index'))
        ->assertOk()
        ->assertSee($product->name)
        ->assertSee($variantProduct->name);

    $this->get(route('products.show', $product))
        ->assertOk()
        ->assertSee('Product Variants')
        ->assertSee(route('products.show', $variantProduct), false);

    $this->get(route('products.show', $variantProduct))
        ->assertOk()
        ->assertSee(route('products.show', $product), false);
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
