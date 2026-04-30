<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['name', 'slug', 'image_url', 'regular_total_price', 'discounted_combo_price', 'combo_stock', 'is_active'])]
class Combo extends Model
{
    public function items(): HasMany
    {
        return $this->hasMany(ComboItem::class);
    }
}
