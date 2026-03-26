<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembayaran Berhasil</title>
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
            background: linear-gradient(135deg, #f97316 0%, #fb923c 100%);
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
        .success-badge {
            background-color: #ecfdf5;
            border: 2px solid #10b981;
            border-radius: 50%;
            width: 60px;
            height: 60px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            font-size: 32px;
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
            <div class="icon">✓</div>
            <h1>Pembayaran Berhasil!</h1>
            <p>Transaksi Anda telah dikonfirmasi</p>
        </div>

        {{-- Content --}}
        <div class="content">
            <p>Halo <strong>{{ $user->name }}</strong>,</p>
            <p>Terima kasih telah melakukan pembayaran. Transaksi Anda telah berhasil diproses dan kami segera akan memulai pengerjaan survey Anda.</p>

            {{-- Transaction Details --}}
            <div class="section">
                <h3 class="section-title">Rincian Transaksi</h3>
                <div class="transaction-info">
                    <div class="info-row">
                        <span class="info-label">No. Referensi</span>
                        <span class="info-value">{{ $transaction->singapay_ref ?? '#' . $transaction->id }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Tanggal</span>
                        <span class="info-value">{{ $transaction->created_at->format('d F Y H:i') }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Status</span>
                        <span class="info-value" style="color: #10b981;">✓ Dibayar</span>
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

            {{-- Next Steps --}}
            <div class="next-steps">
                <strong style="color: #1f2937;">Langkah Selanjutnya:</strong>
                <ol>
                    <li>Kami akan memproses survey Anda dalam waktu kurang lebih 24 jam</li>
                    <li>Anda akan menerima notifikasi email ketika survey siap untuk dikerjakan</li>
                    <li>Pantau progres survey di halaman "Survey Saya" di dashboard kami</li>
                </ol>
            </div>

            {{-- CTA Button --}}
            <div style="text-align: center;">
                <a href="{{ route('user.surveys.show', $survey) }}" class="button">Lihat Survey Anda</a>
            </div>

            <p>Jika Anda memiliki pertanyaan, jangan ragu untuk <a href="{{ route('contact') }}" style="color: #f97316; text-decoration: none;">menghubungi kami</a>.</p>

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
