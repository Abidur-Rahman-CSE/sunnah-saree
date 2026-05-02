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
        $codes = [
            'Royal Blue' => '#173b8f',
            'Maroon' => '#7a1f2b',
            'Magenta' => '#b31972',
            'Pastel' => '#f2b7c6',
            'Pastel Pink' => '#f2b7c6',
            'Purple' => '#6b3aa8',
            'Gold' => '#c9a24a',
            'Black' => '#1f1f1f',
            'Amber' => '#c7822b',
            'Bottle Green' => '#08734c',
            'Teal' => '#108d86',
            'Mustard' => '#d79b2f',
            'Wine' => '#8b1d46',
        ];

        $attribute = DB::table('fashion_attributes')->where('key', 'color')->first();

        if (! $attribute) {
            return;
        }

        $values = collect(json_decode($attribute->values, true) ?: [])
            ->map(function (mixed $value) use ($codes): ?array {
                $name = is_array($value) ? (string) ($value['name'] ?? '') : (string) $value;

                if ($name === '') {
                    return null;
                }

                return [
                    'name' => $name,
                    'code' => is_array($value) ? ($value['code'] ?? ($codes[$name] ?? '#c9a24a')) : ($codes[$name] ?? '#c9a24a'),
                ];
            })
            ->filter()
            ->values()
            ->all();

        DB::table('fashion_attributes')
            ->where('key', 'color')
            ->update(['values' => json_encode($values), 'updated_at' => now()]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $attribute = DB::table('fashion_attributes')->where('key', 'color')->first();

        if (! $attribute) {
            return;
        }

        $values = collect(json_decode($attribute->values, true) ?: [])
            ->map(fn (mixed $value): mixed => is_array($value) ? ($value['name'] ?? null) : $value)
            ->filter()
            ->values()
            ->all();

        DB::table('fashion_attributes')
            ->where('key', 'color')
            ->update(['values' => json_encode($values), 'updated_at' => now()]);
    }
};
