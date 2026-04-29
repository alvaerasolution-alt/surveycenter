<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Setting;

class PointTransaction extends Model
{
    use HasFactory;

    public const TYPE_EARN = 'earn';
    public const TYPE_REDEEM = 'redeem';

    protected $fillable = [
        'user_id',
        'transaction_id',
        'type',
        'points',
        'description',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }

    /**
     * Get the point ratio from settings: how many Rupiah per 1 point.
     * Default: 1000 (Rp 1.000 = 1 point).
     */
    public static function getPointRatio(): int
    {
        return max(1, (int) Setting::get('point_ratio', 1000));
    }

    /**
     * Get the cashback percentage from settings.
     * Default: 1 (1%).
     */
    public static function getCashbackPercentage(): float
    {
        return max(0, (float) Setting::get('cashback_percentage', 1.0));
    }

    /**
     * Calculate points from a payment amount.
     * Example: 1% cashback from 8,000,000 = 80,000. 80,000 / 1000 = 80 points.
     */
    public static function calculatePoints(int $amount): int
    {
        $cashbackPercentage = self::getCashbackPercentage();
        $cashbackAmount = $amount * ($cashbackPercentage / 100);
        return (int) floor($cashbackAmount / self::getPointRatio());
    }
}
