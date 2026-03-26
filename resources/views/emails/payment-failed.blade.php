<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembayaran Gagal</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f9fafb;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }
        .header {
            background: linear-gradient(135deg, #ef4444 0%, #f87171 100%);
            color: #ffffff;
            padding: 40px 20px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
            font-weight: 700;
        }
        .header .icon {
            font-size: 48px;
            margin-bottom: 15px;
        }
        .content {
            padding: 40px;
        }
        .section {
            margin-bottom: 30px;
        }
        .section-title {
            font-size: 14px;
            font-weight: 600;
            color: #6b7280;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 10px;
        }
        .section-content {
            font-size: 16px;
            color: #1f2937;
        }
        .transaction-info {
            background-color: #f3f4f6;
            padding: 20px;
            border-radius: 6px;
            margin: 15px 0;
        }
        .info-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            font-size: 14px;
        }
        .info-row:last-child {
            margin-bottom: 0;
        }
        .info-label {
            color: #6b7280;
            font-weight: 500;
        }
        .info-value {
            color: #1f2937;
            font-weight: 600;
        }
        .amount {
            font-size: 32px;
            font-weight: 700;
            color: #1f2937;
            margin: 20px 0;
        }
        .button {
            display: inline-block;
            padding: 12px 30px;
            background-color: #f97316;
            color: #ffffff;
            text-decoration: none;
            border-radius: 6px;
            font-weight: 600;
            text-align: center;
            margin: 20px 0;
        }
        .button:hover {
            background-color: #ea580c;
        }
        .error-box {
            background-color: #fef2f2;
            border-left: 4px solid #ef4444;
            padding: 15px;
            margin: 20px 0;
            border-radius: 4px;
        }
        .error-title {
            font-weight: 600;
            color: #991b1b;
            margin-bottom: 10px;
        }
        .error-list {
            margin: 10px 0;
            padding-left: 20px;
        }
        .error-list li {
            margin-bottom: 5px;
            color: #7f1d1d;
            font-size: 14px;
        }
        .next-steps {
            background-color: #eff6ff;
            border-left: 4px solid #3b82f6;
            padding: 15px;
            margin: 20px 0;
            border-radius: 4px;
        }
        .next-steps ol {
            margin: 10px 0;
            padding-left: 20px;
        }
        .next-steps li {
            margin-bottom: 8px;
            color: #1f2937;
            font-size: 14px;
        }
        .survey-box {
            background-color: #fef3c7;
            border-left: 4px solid #f59e0b;
            padding: 15px;
            border-radius: 4px;
            margin: 15px 0;
        }
        .survey-title {
            font-weight: 600;
            color: #1f2937;
            font-size: 16px;
        }
        .survey-desc {
            color: #6b7280;
            font-size: 14px;
            margin-top: 5px;
        }
        .footer {
            background-color: #f9fafb;
            padding: 30px;
            text-align: center;
            border-top: 1px solid #e5e7eb;
            font-size: 13px;
            color: #6b7280;
        }
        .footer a {
            color: #f97316;
            text-decoration: none;
        }
        .footer a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        {{-- Header --}}
        <div class="header">
            <div class="icon">✕</div>
            <h1>Pembayaran Gagal</h1>
            <p>Transaksi tidak dapat diproses</p>
        </div>

        {{-- Content --}}
        <div class="content">
            <p>Halo <strong>{{ $user->name }}</strong>,</p>
            <p>Sayangnya, pembayaran untuk survey Anda tidak dapat diproses. Mohon coba lagi dengan metode pembayaran yang berbeda atau hubungi bank Anda untuk informasi lebih lanjut.</p>

            {{-- Error Details --}}
            <div class="error-box">
                <div class="error-title">Penyebab Kemungkinan:</div>
                <ul class="error-list">
                    <li>Saldo tidak mencukupi di rekening atau e-wallet Anda</li>
                    <li>Transaksi dibatalkan atau timeout</li>
                    <li>Metode pembayaran tidak didukung</li>
                    <li>Batas transaksi harian sudah tercapai</li>
                    <li>Masalah koneksi internet</li>
                </ul>
            </div>

            {{-- Transaction Details --}}
            <div class="section">
                <h3 class="section-title">Rincian Transaksi</h3>
                <div class="transaction-info">
                    <div class="info-row">
                        <span class="info-label">No. Transaksi</span>
                        <span class="info-value">#{{ $transaction->id }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Tanggal</span>
                        <span class="info-value">{{ $transaction->created_at->format('d F Y H:i') }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Status</span>
                        <span class="info-value" style="color: #dc2626;">✕ Gagal</span>
                    </div>
                </div>
                <div class="amount">Rp {{ number_format($transaction->amount, 0, ',', '.') }}</div>
            </div>

            {{-- Survey Info --}}
            <div class="section">
                <h3 class="section-title">Informasi Survey</h3>
                <div class="survey-box">
                    <div class="survey-title">{{ $survey->title }}</div>
                    <div class="survey-desc">
                        <strong>{{ $survey->question_count }}</strong> pertanyaan
                        @if($survey->description)
                            • {{ Str::limit($survey->description, 50) }}
                        @endif
                    </div>
                </div>
            </div>

            {{-- What to Do Next --}}
            <div class="next-steps">
                <strong style="color: #1f2937;">Apa yang Bisa Anda Lakukan:</strong>
                <ol>
                    <li>Cek saldo Anda dan pastikan cukup untuk pembayaran</li>
                    <li>Coba gunakan metode pembayaran lain (e-wallet, transfer VA, QRIS)</li>
                    <li>Hubungi bank atau provider e-wallet untuk bantuan</li>
                    <li>Hubungi tim support kami jika masalah berlanjut</li>
                </ol>
            </div>

            {{-- CTA Button --}}
            <div style="text-align: center;">
                <a href="{{ route('user.payments.show', $transaction) }}" class="button">Coba Pembayaran Lagi</a>
            </div>

            <p>Jika Anda memiliki pertanyaan atau membutuhkan bantuan, jangan ragu untuk <a href="{{ route('contact') }}" style="color: #f97316; text-decoration: none;">menghubungi tim support kami</a>.</p>

            <p>Terima kasih,<br><strong>Tim Survey Center</strong></p>
        </div>

        {{-- Footer --}}
        <div class="footer">
            <p>© 2026 Survey Center. Semua hak dilindungi.</p>
            <p>
                <a href="{{ route('about') }}">Tentang Kami</a> |
                <a href="{{ route('contact') }}">Hubungi Kami</a> |
                <a href="{{ route('login') }}">Login</a>
            </p>
            <p style="margin-top: 15px; font-size: 12px;">
                Email ini dikirim ke <strong>{{ $user->email }}</strong> karena Anda memiliki akun Survey Center.
            </p>
        </div>
    </div>
</body>
</html>
