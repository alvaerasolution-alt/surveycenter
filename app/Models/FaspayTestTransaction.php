<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class FaspayTestTransaction extends Model
{
    public const STATUS_UNPAID = 'unpaid';
    public const STATUS_PROCESSING = 'processing';
    public const STATUS_PAID = 'paid';
    public const STATUS_FAILED = 'failed';
    public const STATUS_EXPIRED = 'expired';
    public const STATUS_CANCELLED = 'cancelled';

    protected $fillable = [
        'user_id',
        'bill_no',
        'bill_description',
        'amount',
        'currency',
        'customer_name',
        'customer_email',
        'customer_phone',
        'status',
        'trx_id',
        'payment_reff',
        'payment_channel',
        'payment_date',
        'bank_user_name',
        'payment_response',
        'notes',
        'metadata',
        'expires_at',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'payment_date' => 'datetime',
        'expires_at' => 'datetime',
        'metadata' => 'json',
    ];

    /**
     * Get the user that owns this test transaction
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Check if transaction is expired
     */
    public function isExpired(): bool
    {
        if (!$this->expires_at) {
            return false;
        }

        return Carbon::now()->isAfter($this->expires_at);
    }

    /**
     * Check if payment is completed
     */
    public function isPaid(): bool
    {
        return $this->status === self::STATUS_PAID;
    }

    /**
     * Mark transaction as paid
     */
    public function markAsPaid(array $paymentData = []): void
    {
        $this->update([
            'status' => self::STATUS_PAID,
            'payment_date' => now(),
            'payment_response' => json_encode($paymentData),
            'trx_id' => $paymentData['trx_id'] ?? $this->trx_id,
            'payment_channel' => $paymentData['payment_channel'] ?? $this->payment_channel,
            'bank_user_name' => $paymentData['bank_user_name'] ?? $this->bank_user_name,
        ]);
    }

    /**
     * Mark transaction as failed
     */
    public function markAsFailed(string $reason = ''): void
    {
        $this->update([
            'status' => self::STATUS_FAILED,
            'notes' => $reason,
        ]);
    }

    public static function getStatusLabel(?string $status): string
    {
        return match ($status) {
            self::STATUS_PAID => 'Paid',
            self::STATUS_PROCESSING => 'Processing',
            self::STATUS_UNPAID => 'Unpaid',
            self::STATUS_FAILED => 'Failed',
            self::STATUS_EXPIRED => 'Expired',
            self::STATUS_CANCELLED => 'Cancelled',
            default => ucfirst((string) $status),
        };
    }

    public static function getStatusBadgeClass(?string $status): string
    {
        return match ($status) {
            self::STATUS_PAID => 'bg-green-100 text-green-800',
            self::STATUS_PROCESSING => 'bg-blue-100 text-blue-800',
            self::STATUS_FAILED => 'bg-red-100 text-red-800',
            self::STATUS_EXPIRED => 'bg-gray-100 text-gray-800',
            self::STATUS_CANCELLED => 'bg-gray-100 text-gray-800',
            default => 'bg-yellow-100 text-yellow-800',
        };
    }

    public function statusLabel(): string
    {
        return self::getStatusLabel($this->status);
    }

    public function statusBadgeClass(): string
    {
        return self::getStatusBadgeClass($this->status);
    }

    /**
     * Get formatted amount
     */
    public function getFormattedAmountAttribute(): string
    {
        return 'IDR ' . number_format($this->amount, 0, ',', '.');
    }

    /**
     * Scope: Get unpaid transactions
     */
    public function scopeUnpaid($query)
    {
        return $query->where('status', self::STATUS_UNPAID);
    }

    /**
     * Scope: Get paid transactions
     */
    public function scopePaid($query)
    {
        return $query->where('status', self::STATUS_PAID);
    }

    /**
     * Scope: Get expired transactions
     */
    public function scopeExpired($query)
    {
        return $query->where('expires_at', '<', now());
    }

    /**
     * Scope: Get active (not expired, not completed) transactions
     */
    public function scopeActive($query)
    {
        return $query->where('status', '!=', self::STATUS_PAID)
            ->where('status', '!=', self::STATUS_EXPIRED)
            ->where(function ($q) {
                $q->whereNull('expires_at')
                  ->orWhere('expires_at', '>', now());
            });
    }
}
