<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
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
        ])->each(function (array $data, string $key): void {
            DB::table('fashion_attributes')->updateOrInsert(
                ['key' => $key],
                [
                    'name' => $data['name'],
                    'values' => json_encode($data['values']),
                    'is_active' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
            );
        });

        DB::table('products')
            ->where(function ($query): void {
                $query->whereNotNull('sharee_type')
                    ->orWhereNotNull('fabric')
                    ->orWhereNotNull('work_type')
                    ->orWhereNotNull('color')
                    ->orWhereNotNull('occasion');
            })
            ->update(['product_type' => 'fashion']);

        DB::table('products')
            ->whereNull('sharee_type')
            ->whereNull('fabric')
            ->whereNull('work_type')
            ->whereNull('color')
            ->whereNull('occasion')
            ->update(['product_type' => 'general']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('fashion_attributes')->whereIn('key', ['sharee_type', 'fabric', 'work_type', 'color', 'occasion'])->delete();
    }
};
