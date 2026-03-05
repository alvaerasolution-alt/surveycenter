<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class GenerateQrCode extends Command
{
    protected $signature = 'qr:generate {filename=qr.png}';
    protected $description = 'Generate QRIS QR code from raw data';

    public function handle()
    {
        // Data QRIS mentah
        $qrisData = '00020101021226570015ID.SINGAPAY.WWW01110508270004702123102392181230303UMI51440014ID.CO.QRIS.WWW02159084119095120580303UMI520489995303360540550000550202560410005802ID5931Firma Sevendream Grapadi Realty6013Jakarta Pusat6105321326253051017587924010703C0108286601K6034G9M21V658WEEXGJ3AW163042C2B';

        $filename = $this->argument('filename');
        $path = public_path($filename);

        try {
            // Generate QRIS menggunakan PNG (GD atau Imagick)
            QrCode::format('png')->size(300)->generate($qrisData, $path);

            $this->info("QRIS berhasil dibuat: {$path}");
        } catch (\Exception $e) {
            $this->error("Gagal membuat QRIS: " . $e->getMessage());
        }
    }
}
