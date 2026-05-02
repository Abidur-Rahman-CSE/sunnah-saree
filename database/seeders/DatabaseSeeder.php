<?php

namespace Database\Seeders;

use App\Models\Banner;
use App\Models\Category;
use App\Models\Collection;
use App\Models\Combo;
use App\Models\FashionAttribute;
use App\Models\Offer;
use App\Models\Product;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::query()->firstOrCreate(
            ['email' => 'admin@sunnahsharee.test'],
            [
                'name' => 'Sunnah Sharee Admin',
                'phone' => '+8801700000000',
                'role' => 'admin',
                'password' => Hash::make('password'),
            ],
        );

        User::query()->firstOrCreate(
            ['email' => 'customer@sunnahsharee.test'],
            [
                'name' => 'Demo Customer',
                'phone' => '+8801711111111',
                'role' => 'customer',
                'address' => 'Dhanmondi, Dhaka',
                'password' => Hash::make('password'),
            ],
        );

        $categories = collect(['Sharee', 'Organic Oil', 'Ornaments', 'Cosmetics', 'Baby Products'])
            ->mapWithKeys(fn (string $name): array => [
                $name => Category::query()->firstOrCreate(
                    ['slug' => Str::slug($name)],
                    ['name' => $name, 'is_featured' => $name === 'Sharee'],
                ),
            ]);

        $collections = collect([
            'Eid Collection',
            'Wedding Collection',
            'Bridal Collection',
            'Gift Collection',
            'Budget Collection',
            'Premium Collection',
        ])->mapWithKeys(fn (string $name): array => [
            $name => Collection::query()->firstOrCreate(
                ['slug' => Str::slug($name)],
                [
                    'name' => $name,
                    'description' => 'Curated pieces for '.strtolower($name).'.',
                    'banner_url' => 'https://images.unsplash.com/photo-1601121141461-9d6647bca1ed?auto=format&fit=crop&w=1400&q=80',
                    'is_featured' => true,
                ],
            ),
        ]);

        collect([
            'sharee_type' => ['name' => 'Fashion Type', 'values' => ['Katan Sharee', 'Chumki Sharee', 'Banarasi Sharee', 'Cotton Sharee', 'Silk Sharee', 'Bridal Sharee', 'Party Wear Sharee', 'Daily Wear Sharee']],
            'fabric' => ['name' => 'Fabric', 'values' => ['Silk blend', 'Georgette', 'Banarasi silk', 'Soft cotton', 'Pure silk', 'Katan silk', 'Chiffon']],
            'work_type' => ['name' => 'Work Type', 'values' => ['Zari border', 'Chumki work', 'Heavy zari', 'Printed', 'Resham work', 'Embroidered zari', 'Stone work']],
            'color' => ['name' => 'Color', 'values' => [
                ['name' => 'Royal Blue', 'code' => '#173b8f'],
                ['name' => 'Maroon', 'code' => '#7a1f2b'],
                ['name' => 'Magenta', 'code' => '#b31972'],
                ['name' => 'Pastel', 'code' => '#f2b7c6'],
                ['name' => 'Purple', 'code' => '#6b3aa8'],
                ['name' => 'Gold', 'code' => '#c9a24a'],
                ['name' => 'Black', 'code' => '#1f1f1f'],
            ]],
            'occasion' => ['name' => 'Occasion', 'values' => ['Wedding', 'Party Wear', 'Bridal', 'Daily Wear', 'Eid', 'Gift']],
        ])->each(fn (array $data, string $key): FashionAttribute => FashionAttribute::query()->updateOrCreate(
            ['key' => $key],
            ['name' => $data['name'], 'values' => $data['values'], 'is_active' => true],
        ));

        $products = collect([
            ['Royal Blue Katan Sharee', 'Katan Sharee', 'Royal Blue', 6850, 5990, 'Silk blend', 'Zari border', 'Wedding', true],
            ['Maroon Chumki Party Sharee', 'Chumki Sharee', 'Maroon', 5250, 4590, 'Georgette', 'Chumki work', 'Party Wear', false],
            ['Magenta Banarasi Bridal Sharee', 'Banarasi Sharee', 'Magenta', 12900, 10900, 'Banarasi silk', 'Heavy zari', 'Bridal', true],
            ['Pastel Cotton Daily Sharee', 'Cotton Sharee', 'Pastel', 2450, null, 'Soft cotton', 'Printed', 'Daily Wear', false],
            ['Purple Silk Premium Sharee', 'Silk Sharee', 'Purple', 7950, 6990, 'Pure silk', 'Resham work', 'Eid', true],
            ['Gold Bridal Katan Sharee', 'Bridal Sharee', 'Gold', 14500, 12490, 'Katan silk', 'Embroidered zari', 'Bridal', true],
            ['Black Party Wear Sharee', 'Party Wear Sharee', 'Black', 5750, 4990, 'Chiffon', 'Stone work', 'Party Wear', false],
            ['Organic Hair Oil Set', null, 'Amber', 950, 790, null, null, 'Gift', false, 'Organic Oil'],
            ['Pearl Ornament Set', null, 'Gold', 1850, 1590, null, null, 'Gift', false, 'Ornaments'],
        ])->map(function (array $item) use ($categories, $collections): Product {
            [$name, $shareeType, $color, $price, $discount, $fabric, $workType, $occasion, $blouseIncluded] = $item;
            $categoryName = $item[9] ?? 'Sharee';
            $product = Product::query()->firstOrCreate(
                ['slug' => Str::slug($name)],
                [
                    'category_id' => $categories[$categoryName]->id,
                    'name' => $name,
                    'product_type' => $categoryName === 'Sharee' ? 'fashion' : 'general',
                    'price' => $price,
                    'discount_price' => $discount,
                    'sku' => Str::upper(Str::slug($name, '-')),
                    'description' => 'A premium boutique piece selected for graceful occasions, gift-ready presentation, and reliable everyday service.',
                    'badge' => $discount ? 'Offer' : 'New',
                    'sharee_type' => $shareeType,
                    'fabric' => $fabric,
                    'work_type' => $workType,
                    'color' => $color,
                    'occasion' => $occasion,
                    'blouse_included' => $blouseIncluded,
                    'length' => $shareeType ? '12 haat with blouse piece' : null,
                    'care_instruction' => $shareeType ? 'Dry wash preferred. Store folded in breathable fabric.' : null,
                    'is_featured' => true,
                    'is_best_seller' => in_array($color, ['Royal Blue', 'Magenta', 'Gold'], true),
                    'is_new_arrival' => in_array($color, ['Pastel', 'Purple', 'Black'], true),
                ],
            );

            $product->images()->firstOrCreate(
                ['sort_order' => 0],
                [
                    'image_url' => 'https://images.unsplash.com/photo-1610030469983-98e550d6193c?auto=format&fit=crop&w=900&q=80',
                    'alt_text' => $name,
                ],
            );

            $product->variants()->firstOrCreate(
                ['sku' => $product->sku.'-'.$color],
                ['color' => $color, 'quantity' => 8, 'stock_alert_quantity' => 3, 'stock_status' => 'in_stock'],
            );

            $product->collections()->syncWithoutDetaching([
                $collections['Gift Collection']->id,
                $collections[$blouseIncluded ? 'Bridal Collection' : 'Premium Collection']->id,
            ]);

            return $product;
        });

        $offer = Offer::query()->firstOrCreate(
            ['slug' => 'boutique-offer-zone'],
            [
                'title' => 'Boutique Offer Zone',
                'description' => 'Selected sharees and gift pieces with limited-time savings.',
                'banner_url' => 'https://images.unsplash.com/photo-1595341595379-cf1cd0fb7fb3?auto=format&fit=crop&w=1400&q=80',
                'starts_at' => now()->subDay(),
                'ends_at' => now()->addMonth(),
            ],
        );
        $offer->products()->syncWithoutDetaching($products->take(5)->pluck('id'));

        $combo = Combo::query()->firstOrCreate(
            ['slug' => 'sharee-ornament-combo'],
            [
                'name' => 'Sharee + Ornament Combo',
                'image_url' => 'https://images.unsplash.com/photo-1594736797933-d0501ba2fe65?auto=format&fit=crop&w=900&q=80',
                'regular_total_price' => 8700,
                'discounted_combo_price' => 7290,
                'combo_stock' => 6,
            ],
        );
        $combo->items()->firstOrCreate(['product_id' => $products->first()->id], ['quantity' => 1]);
        $combo->items()->firstOrCreate(['product_id' => $products->last()->id], ['quantity' => 1]);

        Banner::query()->firstOrCreate(
            ['placement' => 'hero'],
            [
                'title' => 'Homepage Hero',
                'headline' => 'Elegant Sharee Collections for Every Graceful Occasion',
                'cta_label' => 'Shop Sharee',
                'cta_url' => route('products.index'),
                'image_url' => 'https://images.unsplash.com/photo-1601121141461-9d6647bca1ed?auto=format&fit=crop&w=1600&q=80',
            ],
        );

        collect([
            'website_name' => 'Sunnah Sharee Ghar',
            'phone' => '+8801700000000',
            'email' => 'care@sunnahshareeghar.com',
            'address' => 'Dhaka, Bangladesh',
            'delivery_charge' => '80',
            'free_delivery_minimum_amount' => '5000',
            'cod_enabled' => '1',
            'online_payment_enabled' => '1',
        ])->each(fn (string $value, string $key): mixed => Setting::query()->updateOrCreate(['key' => $key], ['value' => $value]));
    }
}
