<?php

namespace App\Console\Commands;

use App\Mail\OtpVerificationMail;
use App\Mail\PasswordResetOtpMail;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class TestEmail extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'mail:test
                            {to? : Email tujuan (default: MAIL_FROM_ADDRESS)}
                            {--type=raw : Jenis email: raw, otp, reset, all}';

    /**
     * The console command description.
     */
    protected $description = 'Kirim test email ke Mailtrap untuk memverifikasi konfigurasi SMTP/API';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $to = $this->argument('to') ?: config('mail.from.address');
        $type = $this->option('type');
        $mailer = config('mail.default');

        $this->info('');
        $this->info('╔══════════════════════════════════════════╗');
        $this->info('║       📧 SurveyCenter Mail Tester        ║');
        $this->info('╚══════════════════════════════════════════╝');
        $this->info('');

        // Tampilkan konfigurasi saat ini
        $configRows = [
            ['MAIL_MAILER', $mailer],
            ['MAIL_FROM', config('mail.from.address')],
            ['TO', $to],
        ];

        if ($mailer === 'mailtrap-sdk') {
            $apiKey = config('services.mailtrap-sdk.apiKey');
            $inboxId = config('services.mailtrap-sdk.inboxId');

            $configRows[] = ['MAILTRAP_API_KEY', $apiKey ? '✓ (' . substr($apiKey, 0, 8) . '...)' : '✗ (kosong)'];
            $configRows[] = ['MAILTRAP_INBOX_ID', $inboxId ?: '✗ (kosong — diperlukan untuk testing sandbox)'];
            $configRows[] = ['MODE', $inboxId ? 'Sandbox (Testing)' : 'Production (Sending)'];
        } else {
            $configRows[] = ['MAIL_HOST', config('mail.mailers.smtp.host')];
            $configRows[] = ['MAIL_PORT', config('mail.mailers.smtp.port')];
            $configRows[] = ['MAIL_USERNAME', config('mail.mailers.smtp.username') ? '✓ (set)' : '✗ (kosong)'];
            $configRows[] = ['MAIL_PASSWORD', config('mail.mailers.smtp.password') ? '✓ (set)' : '✗ (kosong)'];
        }

        $this->table(['Setting', 'Value'], $configRows);

        // Validasi config
        if ($mailer === 'mailtrap-sdk') {
            $apiKey = config('services.mailtrap-sdk.apiKey');
            if (!$apiKey) {
                $this->error('');
                $this->error(' ⚠ MAILTRAP_API_KEY belum diisi di .env!');
                $this->error(' Dapatkan API key dari https://mailtrap.io/api-tokens');
                $this->error('');
                return self::FAILURE;
            }
        } elseif ($mailer === 'smtp') {
            $username = config('mail.mailers.smtp.username');
            $password = config('mail.mailers.smtp.password');
            if (!$username || !$password) {
                $this->error('');
                $this->error(' ⚠ Kredensial SMTP belum diisi di .env!');
                $this->error('');
                return self::FAILURE;
            }
        }

        $this->info("Mengirim email test ke: {$to}");
        $this->info('');

        $results = [];

        try {
            if (in_array($type, ['raw', 'all'])) {
                $this->sendRaw($to, $results);
            }

            if (in_array($type, ['otp', 'all'])) {
                $this->sendOtp($to, $results);
            }

            if (in_array($type, ['reset', 'all'])) {
                $this->sendReset($to, $results);
            }

            if (!in_array($type, ['raw', 'otp', 'reset', 'all'])) {
                $this->sendRaw($to, $results);
            }
        } catch (\Exception $e) {
            $this->error('');
            $this->error(' ✗ Gagal mengirim email!');
            $this->error(' Error: ' . $e->getMessage());
            $this->error('');
            $this->line('<fg=yellow> Cek konfigurasi di .env Anda.</>');
            return self::FAILURE;
        }

        // Tampilkan ringkasan
        $this->info('');
        $this->info('═══════════════════════════════════════════');
        $this->table(
            ['Email', 'Status'],
            $results
        );
        $this->info('');

        if ($mailer === 'mailtrap-sdk' && config('services.mailtrap-sdk.inboxId')) {
            $this->info(' 🎉 Selesai! Email dikirim via Mailtrap SDK (inbox ID terdeteksi).');
        } elseif ($mailer === 'mailtrap-sdk') {
            $this->info(' 🎉 Selesai! Email dikirim via Mailtrap Sending API (production).');
        } else {
            $this->info(' 🎉 Selesai! Cek inbox di mailtrap.io');
        }
        $this->info('');

        return self::SUCCESS;
    }

    /**
     * Kirim raw text email (selalu sinkron).
     */
    private function sendRaw(string $to, array &$results): void
    {
        $this->components->task('Mengirim Raw Text Email', function () use ($to) {
            Mail::raw(
                "Ini adalah test email dari SurveyCenter.\n\n"
                . "Jika Anda menerima email ini, berarti konfigurasi email sudah benar.\n\n"
                . "Mailer: " . config('mail.default') . "\n"
                . "Waktu kirim: " . now()->format('d M Y H:i:s') . "\n"
                . "Environment: " . config('app.env'),
                function ($message) use ($to) {
                    $message->to($to)
                        ->subject('✓ Test Email — SurveyCenter (' . now()->format('H:i:s') . ')');
                }
            );
        });
        $results[] = ['Raw Text', '✓ Terkirim'];
    }

    /**
     * Kirim OTP verification email (sinkron, bypass queue).
     */
    private function sendOtp(string $to, array &$results): void
    {
        $otp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        $this->components->task("Mengirim OTP Verification Email (kode: {$otp})", function () use ($to, $otp) {
            $mailable = (new OtpVerificationMail($otp, 'Test User'))
                ->onQueue(null); // bypass queue

            // Send synchronously regardless of ShouldQueue
            Mail::to($to)->sendNow($mailable);
        });
        $results[] = ["OTP Verifikasi (kode: {$otp})", '✓ Terkirim'];
    }

    /**
     * Kirim password reset OTP email (sinkron, bypass queue).
     */
    private function sendReset(string $to, array &$results): void
    {
        $otp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        $this->components->task("Mengirim Password Reset OTP Email (kode: {$otp})", function () use ($to, $otp) {
            $mailable = (new PasswordResetOtpMail($otp, 'Test User'))
                ->onQueue(null); // bypass queue

            // Send synchronously regardless of ShouldQueue
            Mail::to($to)->sendNow($mailable);
        });
        $results[] = ["Reset Password OTP (kode: {$otp})", '✓ Terkirim'];
    }
}
