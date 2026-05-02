<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

#[Fillable(['name', 'key', 'values', 'is_active'])]
class FashionAttribute extends Model
{
    protected function casts(): array
    {
        return [
            'values' => 'array',
            'is_active' => 'boolean',
        ];
    }

    /**
     * @return Collection<int, string>
     */
    public static function valuesFor(string $key): Collection
    {
        return collect(static::query()
            ->where('key', $key)
            ->where('is_active', true)
            ->value('values') ?? [])
            ->map(fn (mixed $value): mixed => is_array($value) ? ($value['name'] ?? null) : $value)
            ->filter()
            ->values();
    }

    /**
     * @return Collection<int, array{name: string, code: string}>
     */
    public static function colorOptions(): Collection
    {
        return collect(static::query()
            ->where('key', 'color')
            ->where('is_active', true)
            ->value('values') ?? [])
            ->map(function (mixed $value): ?array {
                if (is_array($value)) {
                    $name = trim((string) ($value['name'] ?? ''));

                    if ($name === '') {
                        return null;
                    }

                    return [
                        'name' => $name,
                        'code' => (string) ($value['code'] ?? '#c9a24a'),
                    ];
                }

                $name = trim((string) $value);

                return $name === '' ? null : ['name' => $name, 'code' => '#c9a24a'];
            })
            ->filter()
            ->values();
    }

    public static function colorCodeFor(?string $name): ?string
    {
        if (! $name) {
            return null;
        }

        return static::colorOptions()->firstWhere('name', $name)['code'] ?? null;
    }
}
