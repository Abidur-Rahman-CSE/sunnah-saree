<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['title', 'placement', 'image_url', 'headline', 'cta_label', 'cta_url', 'is_active'])]
class Banner extends Model
{
    /**
     * @return array<string, array{label: string, description: string, multiple: bool}>
     */
    public static function placements(): array
    {
        return [
            'hero' => [
                'label' => 'Homepage hero banner',
                'description' => 'Full-width banner at the top of the homepage.',
                'multiple' => false,
            ],
        ];
    }

    public static function placementLabel(string $placement): string
    {
        return self::placements()[$placement]['label'] ?? str($placement)->headline()->toString();
    }

    public function placementLabelText(): string
    {
        return self::placementLabel($this->placement);
    }

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }
}
