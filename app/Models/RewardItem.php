<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RewardItem extends Model
{
    use HasFactory;

    public const CATEGORY_PULSA = 'pulsa';
    public const CATEGORY_VOUCHER = 'voucher';

    protected $fillable = [
        'name',
        'description',
        'category',
        'points_cost',
        'value',
        'stock',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    public function redemptions()
    {
        return $this->hasMany(RewardRedemption::class);
    }

    /**
     * Check if item is available for redemption.
     */
    public function isAvailable(): bool
    {
        if (!$this->is_active) {
            return false;
        }

        // stock = -1 means unlimited
        return $this->stock === -1 || $this->stock > 0;
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', 1);
    }

    public function scopeAvailable($query)
    {
        return $query->active()->where(function ($q) {
            $q->where('stock', -1)->orWhere('stock', '>', 0);
        });
    }

    public static function getCategoryLabel(string $category): string
    {
        return match ($category) {
            self::CATEGORY_PULSA => 'Pulsa All Operator',
            self::CATEGORY_VOUCHER => 'Voucher Diskon',
            default => ucfirst($category),
        };
    }

    public static function getCategoryIcon(string $category): string
    {
        return match ($category) {
            self::CATEGORY_PULSA => 'smartphone',
            self::CATEGORY_VOUCHER => 'ticket',
            default => 'gift',
        };
    }
}
