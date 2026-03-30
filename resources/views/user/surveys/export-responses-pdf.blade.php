<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Survey Responses - {{ $survey->title }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: Arial, sans-serif;
            color: #333;
            line-height: 1.6;
            font-size: 11px;
        }
        .header {
            background-color: #f97316;
            color: white;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 5px;
        }
        .header h1 {
            font-size: 20px;
            margin-bottom: 5px;
        }
        .header p {
            font-size: 11px;
            opacity: 0.9;
        }
        .section {
            margin-bottom: 20px;
            page-break-inside: avoid;
        }
        .response-item {
            background-color: #f9fafb;
            border: 1px solid #e5e7eb;
            border-radius: 5px;
            padding: 12px;
            margin-bottom: 15px;
        }
        .response-header {
            font-weight: bold;
            color: #1f2937;
            margin-bottom: 8px;
            border-bottom: 1px solid #e5e7eb;
            padding-bottom: 8px;
        }
        .response-number {
            display: inline-block;
            background-color: #f97316;
            color: white;
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 10px;
            font-weight: bold;
            margin-right: 8px;
        }
        .response-date {
            font-size: 10px;
            color: #6b7280;
        }
        .response-details {
            margin-top: 8px;
        }
        .detail-row {
            display: flex;
            margin-bottom: 6px;
            border-bottom: 1px solid #f3f4f6;
            padding-bottom: 6px;
        }
        .detail-label {
            width: 25%;
            font-weight: bold;
            color: #374151;
        }
        .detail-value {
            width: 75%;
            color: #1f2937;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            font-size: 10px;
        }
        .table th {
            background-color: #f97316;
            color: white;
            padding: 8px;
            text-align: left;
            font-weight: bold;
        }
        .table td {
            padding: 6px 8px;
            border-bottom: 1px solid #e5e7eb;
        }
        .table tr:nth-child(even) {
            background-color: #f9fafb;
        }
        .empty-state {
            padding: 20px;
            background-color: #f9fafb;
            border-radius: 5px;
            text-align: center;
            color: #6b7280;
        }
        .footer {
            margin-top: 30px;
            padding-top: 15px;
            border-top: 1px solid #e5e7eb;
            font-size: 10px;
            color: #6b7280;
            text-align: center;
        }
    </style>
</head>
<body>
    {{-- Header --}}
    <div class="header">
        <h1>{{ $survey->title }} - Respons Survei</h1>
        <p>Laporan Respons - Dibuat pada {{ $generatedAt->format('d F Y H:i') }}</p>
    </div>

    {{-- Survey Stats --}}
    <table class="table" style="margin-bottom: 25px;">
        <thead>
            <tr>
                <th>Total Respons</th>
                <th>Survey ID</th>
                <th>Jumlah Pertanyaan</th>
                <th>Tanggal Pembuatan Survey</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>{{ $responses->sum('respond_count') }}</td>
                <td>#{{ $survey->id }}</td>
                <td>{{ $survey->question_count }}</td>
                <td>{{ $survey->created_at->format('d/m/Y H:i') }}</td>
            </tr>
        </tbody>
    </table>

    {{-- Responses List --}}
    @if($responses->isNotEmpty())
        <h2 style="color: #f97316; border-bottom: 2px solid #f97316; padding-bottom: 10px; margin-bottom: 20px; font-size: 14px;">Detail Respons</h2>
        
        @foreach($responses as $index => $response)
        <div class="response-item">
            <div class="response-header">
                <span class="response-number">Respons {{ $index + 1 }}</span>
                <span class="response-date">{{ $response->created_at->format('d F Y H:i') }}</span>
            </div>
            <div class="response-details">
                <div class="detail-row">
                    <div class="detail-label">ID Respons</div>
                    <div class="detail-value">#{{ $response->id }}</div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">User</div>
                    <div class="detail-value">{{ $response->user->name ?? 'Anonymous' }}</div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Email</div>
                    <div class="detail-value">{{ $response->user->email ?? 'N/A' }}</div>
                </div>
                @if($response->response_data)
                <div class="detail-row" style="border: none; padding-top: 10px;">
                    <div class="detail-label" style="font-weight: bold;">Data Respons:</div>
                </div>
                @foreach(json_decode($response->response_data, true) ?? [] as $key => $value)
                    <div class="detail-row">
                        <div class="detail-label">{{ ucfirst(str_replace('_', ' ', $key)) }}</div>
                        <div class="detail-value">{{ is_array($value) ? implode(', ', $value) : $value }}</div>
                    </div>
                @endforeach
                @endif
            </div>
        </div>
        @endforeach
    @else
    <div class="empty-state">
        <p>Belum ada respons untuk survey ini</p>
    </div>
    @endif

    {{-- Footer --}}
    <div class="footer">
        <p>Laporan ini dibuat secara otomatis oleh Survey Center</p>
        <p>© 2026 Survey Center. Semua hak dilindungi.</p>
    </div>
</body>
</html>
