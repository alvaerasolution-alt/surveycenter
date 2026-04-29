<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SingaPayTestTransaction extends Model
{
    use HasFactory;

    public const STATUS_PENDING = 'pending';
    public const STATUS_PROCESSING = 'processing';
    public const STATUS_PAID = 'paid';
    public const STATUS_FAILED = 'failed';
    public const STATUS_EXPIRED = 'expired';

    protected $fillable = [
        'user_id',
        'bill_no',
        'amount',
        'customer_name',
        'customer_email',
        'customer_phone',
        'bill_description',
        'notes',
        'status',
        'singapay_ref',
        'payment_method',
        'qr_data',
        'payment_url',
        'webhook_payload',
        'paid_at',
        'expires_at',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'webhook_payload' => 'array',
            'paid_at' => 'datetime',
            'expires_at' => 'datetime',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function isPaid(): bool
    {
        return $this->status === self::STATUS_PAID;
    }

    public function isExpired(): bool
    {
        return $this->status === self::STATUS_EXPIRED
            || ($this->expires_at && $this->expires_at->isPast() && !$this->isPaid());
    }

    public function scopeActive($query)
    {
        return $query->whereIn('status', [self::STATUS_PENDING, self::STATUS_PROCESSING]);
    }

    public function scopePaid($query)
    {
        return $query->where('status', self::STATUS_PAID);
    }

    public function markAsPaid(array $webhookData = []): void
    {
        $this->update([
            'status' => self::STATUS_PAID,
            'paid_at' => now(),
            'webhook_payload' => $webhookData,
            'payment_method' => $webhookData['payment_method'] ?? $this->payment_method,
        ]);
    }
}
