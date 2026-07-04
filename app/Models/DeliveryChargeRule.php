<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['scope', 'locations', 'amount', 'is_active'])]
class DeliveryChargeRule extends Model
{
    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'locations' => 'array',
            'amount' => 'decimal:2',
            'is_active' => 'boolean',
        ];
    }

    public static function amountFor(?string $division, ?string $district, ?string $area): ?float
    {
        $targets = [
            'area' => $area,
            'district' => $district,
            'division' => $division,
        ];
        $priorities = [
            'area' => 3,
            'district' => 2,
            'division' => 1,
        ];

        $rule = static::query()
            ->where('is_active', true)
            ->latest('id')
            ->get()
            ->sortByDesc(fn (self $rule): int => $priorities[$rule->scope] ?? 0)
            ->first(function (self $rule) use ($targets): bool {
                $target = $targets[$rule->scope] ?? null;

                return filled($target) && in_array($target, $rule->locations ?? [], true);
            });

        return $rule ? (float) $rule->amount : null;
    }
}
