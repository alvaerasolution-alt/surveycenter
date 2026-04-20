<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\PasswordResetOtpMail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Models\Article;

class ForgotPasswordController extends Controller
{
    /**
     * Show the forgot password form (email input).
     */
    public function showForgotForm()
    {
        $articles = Article::published()->latest()->take(4)->get();
        return view('auth.forgot-password', compact('articles'));
    }

    /**
     * Generate OTP, store in cache, and send via Email.
     */
    public function sendOtp(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->withErrors(['email' => 'Email tidak terdaftar.'])->onlyInput('email');
        }

        if (Cache::has('otp_cooldown_email_' . $request->email)) {
            return back()->withErrors(['email' => 'Tunggu 60 detik sebelum mengirim ulang OTP.']);
        }

        $otp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        Cache::put('otp_email_' . $request->email, $otp, now()->addMinutes(5));
        Cache::put('otp_email_attempts_' . $request->email, 0, now()->addMinutes(5));
        Cache::put('otp_cooldown_email_' . $request->email, true, now()->addSeconds(60));

        Mail::to($request->email)->send(new PasswordResetOtpMail($otp, $user->name));

        return redirect()->route('password.otp.form', ['email' => $request->email])
            ->with('status', 'Kode OTP telah dikirim ke email Anda.');
    }

    /**
     * Show the OTP verification form.
     */
    public function showOtpForm(Request $request)
    {
        $email = $request->query('email');

        if (!$email) {
            return redirect()->route('password.request');
        }

        $articles = Article::published()->latest()->take(4)->get();
        return view('auth.verify-otp', [
            'phone' => null,
            'email' => $email,
            'method' => 'email',
            'articles' => $articles,
        ]);
    }

    /**
     * Verify the OTP.
     */
    public function verifyOtp(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
            'otp' => ['required', 'string', 'size:6'],
        ]);

        $identifier = $request->email;
        $cachePrefix = 'otp_email_';
        $attemptsPrefix = 'otp_email_attempts_';

        $cachedOtp = Cache::get($cachePrefix . $identifier);
        $attempts = Cache::get($attemptsPrefix . $identifier, 0);

        if ($attempts >= 5) {
            Cache::forget($cachePrefix . $identifier);
            Cache::forget($attemptsPrefix . $identifier);
            return back()->withErrors(['otp' => 'Terlalu banyak percobaan. Silakan minta OTP baru.']);
        }

        if (!$cachedOtp || $cachedOtp !== $request->otp) {
            Cache::increment($attemptsPrefix . $identifier);
            return back()->withErrors(['otp' => 'Kode OTP salah atau sudah kedaluwarsa.']);
        }

        // OTP valid - create a temporary token for password reset
        $resetToken = Hash::make($identifier . now()->timestamp);
        Cache::put('reset_token_' . $identifier, $resetToken, now()->addMinutes(10));
        Cache::forget($cachePrefix . $identifier);
        Cache::forget($attemptsPrefix . $identifier);

        $params = ['token' => urlencode($resetToken), 'method' => 'email', 'email' => $request->email];

        return redirect()->route('password.reset', $params);
    }

    /**
     * Show the reset password form.
     */
    public function showResetForm(Request $request)
    {
        $email = $request->query('email');
        $token = $request->query('token');
        $identifier = $email;

        if (!$identifier || !$token) {
            return redirect()->route('password.request');
        }

        // Verify reset token
        $cachedToken = Cache::get('reset_token_' . $identifier);
        if (!$cachedToken || $cachedToken !== urldecode($token)) {
            return redirect()->route('password.request')
                ->withErrors(['email' => 'Sesi reset password tidak valid. Silakan ulangi.']);
        }

        $articles = Article::published()->latest()->take(4)->get();
        return view('auth.reset-password', [
            'email' => $email,
            'token' => $token,
            'method' => 'email',
            'articles' => $articles,
        ]);
    }

    /**
     * Reset the user's password.
     */
    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
            'token' => ['required', 'string'],
            'password' => ['required', 'min:8', 'confirmed'],
        ]);

        $identifier = $request->email;
        $user = User::where('email', $request->email)->first();

        // Verify reset token
        $cachedToken = Cache::get('reset_token_' . $identifier);
        if (!$cachedToken || $cachedToken !== urldecode($request->token)) {
            return redirect()->route('password.request')
                ->withErrors(['email' => 'Sesi reset password tidak valid. Silakan ulangi.']);
        }

        if (!$user) {
            return back()->withErrors(['email' => 'User tidak ditemukan.']);
        }

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        // Cleanup
        Cache::forget('reset_token_' . $identifier);

        return redirect()->route('login')->with('status', 'Password berhasil direset. Silakan login dengan password baru.');
    }

    /**
     * Resend OTP.
     */
    public function resendOtp(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->withErrors(['email' => 'Email tidak terdaftar.']);
        }

        if (Cache::has('otp_cooldown_email_' . $request->email)) {
            return back()->withErrors(['email' => 'Tunggu 60 detik sebelum mengirim ulang OTP.']);
        }

        $otp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        Cache::put('otp_email_' . $request->email, $otp, now()->addMinutes(5));
        Cache::put('otp_email_attempts_' . $request->email, 0, now()->addMinutes(5));
        Cache::put('otp_cooldown_email_' . $request->email, true, now()->addSeconds(60));

        Mail::to($request->email)->send(new PasswordResetOtpMail($otp, $user->name));

        return back()->with('status', 'Kode OTP baru telah dikirim ke email Anda.');
    }
}
