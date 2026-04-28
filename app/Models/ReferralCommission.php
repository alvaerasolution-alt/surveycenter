<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Setting;

class ReferralCommission extends Model
{
    use HasFactory;

    /**
     * Default points awarded to the referrer (used when no DB setting exists).
     */
    public const DEFAULT_COMMISSION_POINTS = 500;

    /**
     * Get the commission points from settings.
     */
    public static function getCommissionPoints(): int
    {
        return max(0, (int) Setting::get('affiliate_commission_points', self::DEFAULT_COMMISSION_POINTS));
    }

    protected $fillable = [
        'referrer_id',
        'referred_user_id',
        'transaction_id',
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

    public function pointTransaction()
    {
        return $this->belongsTo(PointTransaction::class);
    }
}
