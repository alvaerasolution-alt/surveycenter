<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Survey Selesai</title>
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
            background: linear-gradient(135deg, #10b981 0%, #34d399 100%);
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
        .survey-card {
            background-color: #f0fdf4;
            border: 1px solid #bbf7d0;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
        }
        .survey-title {
            font-size: 18px;
            font-weight: 700;
            color: #1f2937;
            margin-bottom: 8px;
        }
        .progress-bar {
            width: 100%;
            height: 8px;
            background-color: #e5e7eb;
            border-radius: 999px;
            overflow: hidden;
            margin: 12px 0;
        }
        .progress-fill {
            height: 100%;
            background: linear-gradient(90deg, #10b981, #34d399);
            border-radius: 999px;
            width: 100%;
        }
        .button {
            display: inline-block;
            padding: 12px 30px;
            background-color: #10b981;
            color: #ffffff;
            text-decoration: none;
            border-radius: 6px;
            font-weight: 600;
            text-align: center;
            margin: 20px 0;
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
            <div class="icon">🎉</div>
            <h1>Survey Anda Telah Selesai!</h1>
            <p style="margin-top: 8px; opacity: 0.9;">Progres telah mencapai 100%</p>
        </div>

        {{-- Content --}}
        <div class="content">
            <p>Halo <strong>{{ $notifiable->name }}</strong>,</p>
            <p>{{ $messageText }}</p>

            <div class="survey-card">
                <div class="survey-title">{{ $survey->title }}</div>
                @if($survey->description)
                    <p style="color: #6b7280; font-size: 14px; margin: 4px 0;">{{ Str::limit($survey->description, 100) }}</p>
                @endif
                <div class="progress-bar">
                    <div class="progress-fill"></div>
                </div>
                <p style="text-align: right; font-size: 13px; font-weight: 600; color: #10b981;">100% Selesai</p>
            </div>

            <div style="text-align: center;">
                <a href="{{ route('user.surveys.show', $survey->id) }}" class="button">Lihat Detail Survey</a>
            </div>

            <p>Terima kasih telah menggunakan SurveyCenter.</p>

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
