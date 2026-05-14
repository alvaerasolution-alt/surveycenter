<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TopupTransaction extends Model
{
    public const STATUS_PENDING = 'pending';
    public const STATUS_PROCESSING = 'processing';
    public const STATUS_PAID = 'paid';
    public const STATUS_FAILED = 'failed';

    protected $fillable = [
        'user_id',
        'amount',
        'status',
        'payment_method',
        'singapay_ref',
        'bill_no',
        'payment_ref',
        'trx_id',
        'qr_data',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function statusLabel(): string
    {
        return match ($this->status) {
            self::STATUS_PAID => 'Berhasil',
            self::STATUS_PROCESSING => 'Diproses',
            self::STATUS_PENDING => 'Menunggu Pembayaran',
            self::STATUS_FAILED => 'Gagal',
            default => ucfirst((string) $this->status),
        };
    }

    public function statusBadgeClass(): string
    {
        return match ($this->status) {
            self::STATUS_PAID => 'bg-emerald-100 text-emerald-700',
            self::STATUS_PROCESSING => 'bg-blue-100 text-blue-700',
            self::STATUS_PENDING => 'bg-amber-100 text-amber-700',
            self::STATUS_FAILED => 'bg-red-100 text-red-700',
            default => 'bg-gray-100 text-gray-700',
        };
    }
}
