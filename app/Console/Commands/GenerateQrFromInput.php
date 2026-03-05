<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class GenerateQrFromInput extends Command
{
    // Nama command dengan argument input QRIS dan optional nama file
    protected $signature = 'qr:generate-input {data} {filename=qr.png}';
    protected $description = 'Generate QRIS QR code from input data and save as PNG';

    public function handle()
    {
        // Ambil data QRIS dari input user
        $qrisData = $this->argument('data');
        $filename = $this->argument('filename');

        $path = public_path($filename);

        try {
            // Generate QRIS QR code
            QrCode::format('png')
                ->size(300)
                ->generate($qrisData, $path);

            $this->info("QRIS berhasil dibuat: {$path}");
            $this->info("File bisa diakses lewat browser: " . url($filename));
        } catch (\Exception $e) {
            $this->error("Gagal membuat QRIS: " . $e->getMessage());
        }
    }
}
