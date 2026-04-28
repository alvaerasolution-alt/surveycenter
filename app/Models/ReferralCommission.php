<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Setting;

class ReferralCommission extends Model
{
    use HasFactory;

    public const DEFAULT_COMMISSION_PERCENT = 10; // 10%

    /**
     * Get the commission percentage from settings.
     */
    public static function getCommissionPercent(): float
    {
        return max(0, (float) Setting::get('affiliate_commission_percent', self::DEFAULT_COMMISSION_PERCENT));
    }

    /**
     * Calculate commission amount in Rupiah from an order total.
     */
    public static function calculateCommission(int $orderAmount): int
    {
        $percent = self::getCommissionPercent();
        return (int) floor($orderAmount * $percent / 100);
    }

    protected $fillable = [
        'referrer_id',
        'referred_user_id',
        'transaction_id',
        'commission_amount',
        'commission_percent',
        'point_transaction_id',
        'points_earned',
    ];

    public function referrer()
    {
        return $this->belongsTo(User::class, 'referrer_id');
    }

    public function referredUser()
    {
        return $this->belongsTo(User::class, 'referred_user_id');
    }

    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }
}
