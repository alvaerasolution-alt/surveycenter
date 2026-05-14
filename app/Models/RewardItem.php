<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RewardItem extends Model
{
    use HasFactory;

    public const CATEGORY_TUNAI = 'tunai';
    public const CATEGORY_LAINNYA = 'lainnya';
    public const CATEGORY_SALDO = 'saldo';

    protected $fillable = [
        'name',
        'description',
        'category',
        'points_cost',
        'value',
        'stock',
    ];

    public function redemptions()
    {
        return $this->hasMany(RewardRedemption::class);
    }

    /**
     * Check if item is available for redemption.
     */
    public function isAvailable(): bool
    {
        return $this->stock === -1 || $this->stock > 0;
    }

    public function scopeAvailable($query)
    {
        return $query->where(function ($q) {
            $q->where('stock', '=', -1)->orWhere('stock', '>', 0);
        });
    }

    public static function getCategoryLabel(string $category): string
    {
        return match ($category) {
            self::CATEGORY_TUNAI => 'Uang Tunai',
            self::CATEGORY_LAINNYA => 'Lain-lain',
            self::CATEGORY_SALDO => 'Saldo Deposit',
            default => ucfirst($category),
        };
    }

    public static function getCategoryIcon(string $category): string
    {
        return match ($category) {
            self::CATEGORY_TUNAI => 'banknote',
            self::CATEGORY_LAINNYA => 'gift',
            self::CATEGORY_SALDO => 'wallet',
            default => 'gift',
        };
    }
}
