<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Invoice #{{ $transaction->id }}</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            color: #333;
        }

        .invoice-box {
            max-width: 800px;
            margin: auto;
            padding: 25px;
            border: 1px solid #eee;
            box-shadow: 0 0 10px rgba(0, 0, 0, .15);
        }

        h1 {
            font-size: 22px;
            margin-bottom: 5px;
        }

        h3 {
            margin-top: 20px;
            font-size: 16px;
            color: #444;
        }

        .header,
        .footer {
            text-align: center;
        }

        .company {
            font-size: 14px;
            font-weight: bold;
        }

        .status {
            padding: 4px 10px;
            border-radius: 5px;
            font-weight: bold;
        }

        .paid {
            background: #d4edda;
            color: #155724;
        }

        .pending {
            background: #fff3cd;
            color: #856404;
        }

        .unpaid {
            background: #f8d7da;
            color: #721c24;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        table,
        th,
        td {
            border: 1px solid #ddd;
        }

        th,
        td {
            padding: 8px;
            text-align: left;
        }

        .total-row td {
            font-weight: bold;
        }

        a {
            color: #007bff;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>
    <div class="invoice-box">
        <!-- Header -->
        <div class="header" style="text-align:center; margin-bottom:20px;">
            {{-- <img src="{{ url('assets/logosc.png') }}" alt="Logo" style="height:80px; margin-bottom:10px;"> --}}
            <div class="company" style="font-size:22px; font-weight:bold;">
                Survey Center Indonesia
            </div>
            <p style="margin:5px 0; line-height:1.5;">
                NPWP: 40.443.383.1-419.000 <br>
                Scientia Residences Tower C, Lantai II, Jl. Scientia Square Utara, Kel. Curug Sangereng, Kec. Kelapa Dua, Kab. Tangerang, Banten 15810
                <br>
                Telp: 0851-2478-6980
            </p>
        </div>

        <!-- Invoice Info -->
        <h1>Invoice #{{ $transaction->id }}</h1>
        <p>
            <strong>Status:</strong>
            <span
                class="status 
                {{ $transaction->displayStatus == 'paid' ? 'paid' : ($transaction->displayStatus == 'pending' ? 'pending' : 'unpaid') }}">
                {{ strtoupper($transaction->displayStatus) }}
            </span>
        </p>
        <p><strong>Tanggal Invoice:</strong> {{ $transaction->created_at->format('l, d F Y') }}</p>
        <p><strong>Metode Pembayaran:</strong> {{ strtoupper($transaction->payment_method ?? 'N/A') }}</p>

        <!-- Customer -->
        <h3>Ditagihkan Kepada</h3>
        <p>
            {{ $transaction->user->name ?? 'Guest User' }} <br>
            {{ $transaction->user->email ?? '-' }}
        </p>

        <!-- Informasi Survey -->
        <h3>Detail Pesanan</h3>
        <table>
            <tbody>
                <tr>
                    <th>Judul Survey</th>
                    <td>{{ $transaction->survey->title ?? '-' }}</td>
                </tr>
                <tr>
                    <th>Jumlah Pertanyaan</th>
                    <td>{{ $transaction->survey->question_count ?? 0 }} Pertanyaan</td>
                </tr>
                <tr>
                    <th>Jumlah Responden</th>
                    <td>{{ $transaction->survey->respondent_count ?? ($transaction->survey->responses->first()->respond_count ?? 0) }} Orang</td>
                </tr>
            </tbody>
        </table>

        <!-- Rincian Biaya -->
        <h3>Rincian Pembayaran</h3>
        <table>
            <thead>
                <tr>
                    <th>Deskripsi</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Pembayaran Survey #{{ $transaction->survey_id }}</td>
                    <td>Rp {{ number_format($transaction->amount, 0, ',', '.') }}</td>
                </tr>
                <tr class="total-row">
                    <td>Total Pembayaran</td>
                    <td>Rp {{ number_format($transaction->amount, 0, ',', '.') }}</td>
                </tr>
            </tbody>
        </table>

        <!-- Footer -->
        <div class="footer" style="margin-top: 20px;">
            <p>Terima kasih telah menggunakan layanan kami 🙏</p>
            <p style="color:#b71c1c; font-weight:700; margin:0.2rem 0;">
                Survey yang sudah dijalankan <span style="text-decoration:underline;">tidak dapat dibatalkan</span>.
            </p>
            <p><small>Dokumen ini dibuat otomatis oleh sistem Survey Center Indonesia</small></p>
        </div>
    </div>
</body>

</html>
