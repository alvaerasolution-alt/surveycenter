<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RewardRedemption extends Model
{
    use HasFactory;

    public const STATUS_PENDING = 'pending';
    public const STATUS_PROCESSING = 'processing';
    public const STATUS_COMPLETED = 'completed';
    public const STATUS_REJECTED = 'rejected';

    protected $fillable = [
        'user_id',
        'reward_item_id',
        'point_transaction_id',
        'points_spent',
        'status',
        'phone_number',
        'admin_notes',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function rewardItem()
    {
        return $this->belongsTo(RewardItem::class);
    }

    public function pointTransaction()
    {
        return $this->belongsTo(PointTransaction::class);
    }

    public static function getStatusLabel(string $status): string
    {
        return match ($status) {
            self::STATUS_PENDING => 'Menunggu',
            self::STATUS_PROCESSING => 'Diproses',
            self::STATUS_COMPLETED => 'Selesai',
            self::STATUS_REJECTED => 'Ditolak',
            default => ucfirst($status),
        };
    }

    public static function getStatusBadgeClass(string $status): string
    {
        return match ($status) {
            self::STATUS_PENDING => 'bg-amber-100 text-amber-700',
            self::STATUS_PROCESSING => 'bg-blue-100 text-blue-700',
            self::STATUS_COMPLETED => 'bg-emerald-100 text-emerald-700',
            self::STATUS_REJECTED => 'bg-red-100 text-red-700',
            default => 'bg-gray-100 text-gray-700',
        };
    }
}
