<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Survey Report - {{ $survey->title }}</title>
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
        }
        .header {
            background-color: #f97316;
            color: white;
            padding: 20px;
            margin-bottom: 30px;
            border-radius: 5px;
        }
        .header h1 {
            font-size: 24px;
            margin-bottom: 10px;
        }
        .header p {
            font-size: 12px;
            opacity: 0.9;
        }
        .section {
            margin-bottom: 30px;
            page-break-inside: avoid;
        }
        .section-title {
            font-size: 16px;
            font-weight: bold;
            color: #f97316;
            border-bottom: 2px solid #f97316;
            padding-bottom: 10px;
            margin-bottom: 15px;
        }
        .info-grid {
            display: table;
            width: 100%;
            margin-bottom: 15px;
        }
        .info-row {
            display: table-row;
        }
        .info-label {
            display: table-cell;
            width: 30%;
            padding: 8px;
            background-color: #f3f4f6;
            font-weight: bold;
            border: 1px solid #e5e7eb;
        }
        .info-value {
            display: table-cell;
            width: 70%;
            padding: 8px;
            border: 1px solid #e5e7eb;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .table th {
            background-color: #f97316;
            color: white;
            padding: 10px;
            text-align: left;
            font-weight: bold;
        }
        .table td {
            padding: 10px;
            border-bottom: 1px solid #e5e7eb;
        }
        .table tr:nth-child(even) {
            background-color: #f9fafb;
        }
        .badge {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: bold;
        }
        .badge-success {
            background-color: #d1fae5;
            color: #065f46;
        }
        .badge-warning {
            background-color: #fef3c7;
            color: #92400e;
        }
        .badge-pending {
            background-color: #e0e7ff;
            color: #312e81;
        }
        .progress-bar {
            width: 100%;
            height: 20px;
            background-color: #e5e7eb;
            border-radius: 10px;
            overflow: hidden;
            margin: 10px 0;
        }
        .progress-fill {
            height: 100%;
            background-color: #10b981;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 12px;
            font-weight: bold;
        }
        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #e5e7eb;
            font-size: 12px;
            color: #6b7280;
            text-align: center;
        }
        .empty-state {
            padding: 20px;
            background-color: #f9fafb;
            border-radius: 5px;
            text-align: center;
            color: #6b7280;
        }
    </style>
</head>
<body>
    {{-- Header --}}
    <div class="header">
        <h1>{{ $survey->title }}</h1>
        <p>Laporan Survey - Dibuat pada {{ $generatedAt->format('d F Y H:i') }}</p>
    </div>

    {{-- Survey Information --}}
    <div class="section">
        <h2 class="section-title">Informasi Survey</h2>
        <div class="info-grid">
            <div class="info-row">
                <div class="info-label">ID Survey</div>
                <div class="info-value">#{{ $survey->id }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Judul</div>
                <div class="info-value">{{ $survey->title }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Deskripsi</div>
                <div class="info-value">{{ $survey->description ?: '-' }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Jumlah Pertanyaan</div>
                <div class="info-value">{{ $survey->question_count }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Tanggal Dibuat</div>
                <div class="info-value">{{ $survey->created_at->format('d F Y H:i') }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Tanggal Diperbarui</div>
                <div class="info-value">{{ $survey->updated_at->format('d F Y H:i') }}</div>
            </div>
        </div>
    </div>

    {{-- Transaction Information --}}
    @if($transactions->isNotEmpty())
    <div class="section">
        <h2 class="section-title">Informasi Transaksi</h2>
        <table class="table">
            <thead>
                <tr>
                    <th>No. Transaksi</th>
                    <th>Jumlah</th>
                    <th>Status</th>
                    <th>Progress</th>
                    <th>Tanggal</th>
                </tr>
            </thead>
            <tbody>
                @foreach($transactions as $transaction)
                <tr>
                    <td>#{{ $transaction->id }}</td>
                    <td>Rp {{ number_format($transaction->amount, 0, ',', '.') }}</td>
                    <td>
                        @php
                            $statusClass = match($transaction->status) {
                                'paid' => 'badge-success',
                                'pending' => 'badge-warning',
                                'processing' => 'badge-warning',
                                default => 'badge-pending'
                            };
                            $statusLabel = match($transaction->status) {
                                'paid' => 'Dibayar',
                                'pending' => 'Pending',
                                'processing' => 'Diproses',
                                'failed' => 'Gagal',
                                default => ucfirst($transaction->status)
                            };
                        @endphp
                        <span class="badge {{ $statusClass }}">{{ $statusLabel }}</span>
                    </td>
                    <td>
                        <div class="progress-bar">
                            <div class="progress-fill" style="width: {{ $transaction->progress }}%;">
                                {{ $transaction->progress }}%
                            </div>
                        </div>
                    </td>
                    <td>{{ $transaction->created_at->format('d/m/Y') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif

    {{-- Summary Statistics --}}
    <div class="section">
        <h2 class="section-title">Ringkasan</h2>
        <div class="info-grid">
            <div class="info-row">
                <div class="info-label">Total Respons</div>
                <div class="info-value">{{ $responses->count() }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Total Transaksi</div>
                <div class="info-value">{{ $transactions->count() }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Total Biaya</div>
                <div class="info-value">Rp {{ number_format($transactions->sum('amount'), 0, ',', '.') }}</div>
            </div>
        </div>
    </div>

    {{-- Footer --}}
    <div class="footer">
        <p>Laporan ini dibuat secara otomatis oleh Survey Center</p>
        <p>© 2026 Survey Center. Semua hak dilindungi.</p>
    </div>
</body>
</html>
