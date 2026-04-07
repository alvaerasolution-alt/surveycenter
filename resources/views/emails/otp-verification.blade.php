<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi Email</title>
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
        .otp-container {
            text-align: center;
            margin: 30px 0;
        }
        .otp-code {
            display: inline-block;
            font-size: 36px;
            font-weight: 800;
            letter-spacing: 12px;
            color: #1f2937;
            background-color: #f3f4f6;
            padding: 16px 32px;
            border-radius: 10px;
            border: 2px dashed #d1d5db;
        }
        .info-box {
            background-color: #fffbeb;
            border-left: 4px solid #f59e0b;
            padding: 15px;
            border-radius: 4px;
            margin: 20px 0;
            font-size: 13px;
            color: #92400e;
        }
        .info-box strong {
            color: #78350f;
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
    </style>
</head>
<body>
    <div class="container">
        {{-- Header --}}
        <div class="header">
            <div class="icon">✉️</div>
            <h1>Verifikasi Email Anda</h1>
            <p style="margin-top: 8px; opacity: 0.9;">Satu langkah lagi untuk menyelesaikan registrasi</p>
        </div>

        {{-- Content --}}
        <div class="content">
            <p>Halo <strong>{{ $userName }}</strong>,</p>
            <p>Terima kasih telah mendaftar di SurveyCenter! Gunakan kode OTP di bawah ini untuk memverifikasi alamat email Anda:</p>

            <div class="otp-container">
                <div class="otp-code">{{ $otp }}</div>
            </div>

            <div class="info-box">
                <strong>⏰ Penting:</strong>
                <ul style="margin: 8px 0 0 0; padding-left: 18px;">
                    <li>Kode ini berlaku selama <strong>5 menit</strong></li>
                    <li>Jangan bagikan kode ini kepada siapapun</li>
                    <li>Jika Anda tidak mendaftar, abaikan email ini</li>
                </ul>
            </div>

            <p style="font-size: 13px; color: #6b7280;">Jika Anda tidak merasa mendaftar di SurveyCenter, Anda bisa mengabaikan email ini dengan aman.</p>

            <p>Salam,<br><strong>Tim SurveyCenter</strong></p>
        </div>

        {{-- Footer --}}
        <div class="footer">
            <p>© {{ date('Y') }} SurveyCenter. Semua hak dilindungi.</p>
            <p>
                <a href="{{ url('/about') }}">Tentang Kami</a> |
                <a href="{{ url('/contact') }}">Hubungi Kami</a>
            </p>
        </div>
    </div>
</body>
</html>
