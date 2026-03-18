<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class FaspayTestTransaction extends Model
{
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
        return $this->status === 'paid';
    }

    /**
     * Mark transaction as paid
     */
    public function markAsPaid(array $paymentData = []): void
    {
        $this->update([
            'status' => 'paid',
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
            'status' => 'failed',
            'notes' => $reason,
        ]);
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
        return $query->where('status', 'unpaid');
    }

    /**
     * Scope: Get paid transactions
     */
    public function scopePaid($query)
    {
        return $query->where('status', 'paid');
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
        return $query->where('status', '!=', 'paid')
            ->where('status', '!=', 'expired')
            ->where(function ($q) {
                $q->whereNull('expires_at')
                  ->orWhere('expires_at', '>', now());
            });
    }
}

