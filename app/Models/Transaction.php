<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    public const STATUS_PENDING = 'pending';
    public const STATUS_PROCESSING = 'processing';
    public const STATUS_PAID = 'paid';
    public const STATUS_FAILED = 'failed';

    protected $fillable = [
        'survey_id',
        'user_id',
        'amount',
        'status',
        'singapay_ref',
        'payment_method',
        'progress',
        'bill_no',
        'payment_ref',
        'trx_id',
        'qr_data',
    ];

    public function survey()
    {
        return $this->belongsTo(Survey::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function getStatusLabel(?string $status): string
    {
        return match ($status) {
            self::STATUS_PAID => 'Dibayar',
            self::STATUS_PROCESSING => 'Verifikasi',
            self::STATUS_PENDING => 'Menunggu Pembayaran',
            self::STATUS_FAILED => 'Gagal',
            default => ucfirst((string) $status),
        };
    }

    public static function getStatusActivityLabel(?string $status): string
    {
        return match ($status) {
            self::STATUS_PAID => 'Pembayaran Berhasil',
            self::STATUS_PROCESSING => 'Verifikasi Pembayaran',
            self::STATUS_PENDING => 'Menunggu Pembayaran',
            self::STATUS_FAILED => 'Pembayaran Gagal',
            default => self::getStatusLabel($status),
        };
    }

    public static function getStatusInfoCard(?string $status): array
    {
        return match ($status) {
            self::STATUS_PENDING => [
                'containerClass' => 'bg-amber-50 border border-amber-200',
                'icon' => 'alert-circle',
                'iconClass' => 'text-amber-600',
                'titleClass' => 'text-amber-900',
                'descriptionClass' => 'text-amber-700',
                'title' => 'Pembayaran Tertunda',
                'description' => 'Transaksi ini masih menunggu pembayaran. Silakan lakukan pembayaran untuk memulai proses survey.',
            ],
            self::STATUS_PROCESSING => [
                'containerClass' => 'bg-blue-50 border border-blue-200',
                'icon' => 'loader',
                'iconClass' => 'text-blue-600',
                'titleClass' => 'text-blue-900',
                'descriptionClass' => 'text-blue-700',
                'title' => 'Pembayaran Sedang Diverifikasi',
                'description' => 'Pembayaran Anda sudah diterima gateway dan sedang menunggu konfirmasi akhir.',
            ],
            self::STATUS_FAILED => [
                'containerClass' => 'bg-red-50 border border-red-200',
                'icon' => 'x-circle',
                'iconClass' => 'text-red-600',
                'titleClass' => 'text-red-900',
                'descriptionClass' => 'text-red-700',
                'title' => 'Pembayaran Gagal',
                'description' => 'Transaksi ini belum berhasil. Silakan coba bayar lagi. Jika tetap gagal, hubungi admin untuk bantuan lebih lanjut.',
            ],
            self::STATUS_PAID => [
                'containerClass' => 'bg-emerald-50 border border-emerald-200',
                'icon' => 'check-circle',
                'iconClass' => 'text-emerald-600',
                'titleClass' => 'text-emerald-900',
                'descriptionClass' => 'text-emerald-700',
                'title' => 'Pembayaran Berhasil',
                'description' => 'Pembayaran telah dikonfirmasi. Survey sedang diproses oleh tim kami.',
            ],
            default => [
                'containerClass' => 'bg-gray-50 border border-gray-200',
                'icon' => 'info',
                'iconClass' => 'text-gray-600',
                'titleClass' => 'text-gray-900',
                'descriptionClass' => 'text-gray-700',
                'title' => self::getStatusLabel($status),
                'description' => 'Status transaksi sedang diperbarui.',
            ],
        };
    }

    public static function getStatusBadgeClass(?string $status): string
    {
        return match ($status) {
            self::STATUS_PAID => 'bg-emerald-100 text-emerald-700',
            self::STATUS_PROCESSING => 'bg-blue-100 text-blue-700',
            self::STATUS_PENDING => 'bg-amber-100 text-amber-700',
            self::STATUS_FAILED => 'bg-red-100 text-red-700',
            default => 'bg-gray-100 text-gray-700',
        };
    }

    public static function getStatusIconBackgroundClass(?string $status): string
    {
        return match ($status) {
            self::STATUS_PAID => 'bg-emerald-100',
            self::STATUS_PROCESSING => 'bg-blue-100',
            self::STATUS_PENDING => 'bg-amber-100',
            self::STATUS_FAILED => 'bg-red-100',
            default => 'bg-gray-100',
        };
    }

    public static function getStatusIconColorClass(?string $status): string
    {
        return match ($status) {
            self::STATUS_PAID => 'text-emerald-600',
            self::STATUS_PROCESSING => 'text-blue-600',
            self::STATUS_PENDING => 'text-amber-600',
            self::STATUS_FAILED => 'text-red-600',
            default => 'text-gray-600',
        };
    }

    public static function isStage1CompletedStatus(?string $status): bool
    {
        return in_array($status, [self::STATUS_PROCESSING, self::STATUS_PAID], true);
    }

    public function statusLabel(): string
    {
        return self::getStatusLabel($this->status);
    }

    public function statusActivityLabel(): string
    {
        return self::getStatusActivityLabel($this->status);
    }

    public function statusBadgeClass(): string
    {
        return self::getStatusBadgeClass($this->status);
    }

    public function statusIconBackgroundClass(): string
    {
        return self::getStatusIconBackgroundClass($this->status);
    }

    public function statusIconColorClass(): string
    {
        return self::getStatusIconColorClass($this->status);
    }

    public function statusInfoCard(): array
    {
        return self::getStatusInfoCard($this->status);
    }

    public function isStage1Completed(): bool
    {
        return self::isStage1CompletedStatus($this->status);
    }

    public function safeProgress(): int
    {
        $value = (int) ($this->progress ?? 0);

        return max(0, min(100, $value));
    }

    public function isStage2Completed(): bool
    {
        return $this->safeProgress() >= 100;
    }
}
