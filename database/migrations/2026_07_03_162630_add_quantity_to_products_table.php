<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table): void {
            $table->unsignedInteger('quantity')->default(0)->after('sku');
        });

        if (DB::connection()->pretending()) {
            return;
        }

        DB::table('product_variants')
            ->select('product_id', DB::raw('SUM(quantity) as variant_quantity'))
            ->groupBy('product_id')
            ->orderBy('product_id')
            ->cursor()
            ->each(function (object $product): void {
                DB::table('products')
                    ->where('id', $product->product_id)
                    ->update(['quantity' => (int) $product->variant_quantity]);
            });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table): void {
            $table->dropColumn('quantity');
        });
    }
};
