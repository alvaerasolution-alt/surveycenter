<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\OtpVerificationMail;
use App\Models\User;
use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;

class EmailVerificationController extends Controller
{
    /**
     * Show the email OTP verification form.
     */
    public function showVerifyForm(Request $request)
    {
        $email = $request->query('email', session('verify_email'));

        if (!$email) {
            return redirect()->route('login');
        }

        $articles = Article::latest()->take(4)->get();
        return view('auth.verify-email-otp', compact('email', 'articles'));
    }

    /**
     * Send OTP to user's email.
     */
    public function sendOtp(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email', 'exists:users,email'],
        ]);

        $user = User::where('email', $request->email)->first();

        // Rate limit
        if (Cache::has('email_otp_cooldown_' . $request->email)) {
            return back()->withErrors(['email' => 'Tunggu 60 detik sebelum mengirim ulang kode OTP.']);
        }

        // Generate 6 digit OTP
        $otp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        // Store OTP in cache for 5 minutes
        Cache::put('email_otp_' . $request->email, $otp, now()->addMinutes(5));
        Cache::put('email_otp_attempts_' . $request->email, 0, now()->addMinutes(5));
        Cache::put('email_otp_cooldown_' . $request->email, true, now()->addSeconds(60));

        // Send via Mailtrap (or configured SMTP)
        Mail::to($request->email)->send(new OtpVerificationMail($otp, $user->name));

        return redirect()->route('verification.notice', ['email' => $request->email])
            ->with('status', 'Kode OTP telah dikirim ke email Anda.');
    }

    /**
     * Verify the email OTP.
     */
    public function verifyOtp(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
            'otp' => ['required', 'string', 'size:6'],
        ]);

        $cachedOtp = Cache::get('email_otp_' . $request->email);
        $attempts = Cache::get('email_otp_attempts_' . $request->email, 0);

        if ($attempts >= 5) {
            Cache::forget('email_otp_' . $request->email);
            Cache::forget('email_otp_attempts_' . $request->email);
            return back()->withErrors(['otp' => 'Terlalu banyak percobaan. Silakan minta kode OTP baru.']);
        }

        if (!$cachedOtp || $cachedOtp !== $request->otp) {
            Cache::increment('email_otp_attempts_' . $request->email);
            return back()->withErrors(['otp' => 'Kode OTP salah atau sudah kedaluwarsa.']);
        }

        // OTP valid - verify the user's email
        $user = User::where('email', $request->email)->first();

        if ($user) {
            $user->email_verified_at = now();
            $user->save();

            // Cleanup
            Cache::forget('email_otp_' . $request->email);
            Cache::forget('email_otp_attempts_' . $request->email);

            // Send welcome notification
            $user->notify(new \App\Notifications\WelcomeNotification());

            // Login the user
            Auth::login($user);

            return redirect()->route('user.dashboard')
                ->with('success', 'Email berhasil diverifikasi. Selamat datang di SurveyCenter!');
        }

        return back()->withErrors(['email' => 'User tidak ditemukan.']);
    }

    /**
     * Resend OTP to email.
     */
    public function resendOtp(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email', 'exists:users,email'],
        ]);

        // Rate limit
        if (Cache::has('email_otp_cooldown_' . $request->email)) {
            return back()->withErrors(['email' => 'Tunggu 60 detik sebelum mengirim ulang kode OTP.']);
        }

        $user = User::where('email', $request->email)->first();

        $otp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        Cache::put('email_otp_' . $request->email, $otp, now()->addMinutes(5));
        Cache::put('email_otp_attempts_' . $request->email, 0, now()->addMinutes(5));
        Cache::put('email_otp_cooldown_' . $request->email, true, now()->addSeconds(60));

        Mail::to($request->email)->send(new OtpVerificationMail($otp, $user->name));

        return back()->with('status', 'Kode OTP baru telah dikirim ke email Anda.');
    }
}
