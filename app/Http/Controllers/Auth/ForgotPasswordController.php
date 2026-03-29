<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\FonnteService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use App\Models\Article;

class ForgotPasswordController extends Controller
{
    /**
     * Show the forgot password form (phone input).
     */
    public function showForgotForm()
    {
        $articles = Article::latest()->take(4)->get();
        return view('auth.forgot-password', compact('articles'));
    }

    /**
     * Generate OTP, store in cache, and send via Fonnte WhatsApp.
     */
    public function sendOtp(Request $request)
    {
        $request->validate([
            'phone' => ['required', 'string', 'regex:/^08[0-9]{8,13}$/'],
        ]);

        $user = User::where('phone', $request->phone)->first();

        if (!$user) {
            return back()->withErrors(['phone' => 'Nomor HP tidak terdaftar.'])->onlyInput('phone');
        }

        // Generate 6 digit OTP
        $otp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        // Store OTP in cache for 5 minutes
        Cache::put('otp_' . $request->phone, $otp, now()->addMinutes(5));
        Cache::put('otp_attempts_' . $request->phone, 0, now()->addMinutes(5));

        // Send via Fonnte
        $fonnte = new FonnteService();
        $result = $fonnte->sendOtp($request->phone, $otp);

        return redirect()->route('password.otp.form', ['phone' => $request->phone])
            ->with('status', 'Kode OTP telah dikirim ke WhatsApp Anda.');
    }

    /**
     * Show the OTP verification form.
     */
    public function showOtpForm(Request $request)
    {
        $phone = $request->query('phone');

        if (!$phone) {
            return redirect()->route('password.request');
        }

        $articles = Article::latest()->take(4)->get();
        return view('auth.verify-otp', compact('phone', 'articles'));
    }

    /**
     * Verify the OTP.
     */
    public function verifyOtp(Request $request)
    {
        $request->validate([
            'phone' => ['required', 'string'],
            'otp' => ['required', 'string', 'size:6'],
        ]);

        $cachedOtp = Cache::get('otp_' . $request->phone);
        $attempts = Cache::get('otp_attempts_' . $request->phone, 0);

        if ($attempts >= 5) {
            Cache::forget('otp_' . $request->phone);
            Cache::forget('otp_attempts_' . $request->phone);
            return back()->withErrors(['otp' => 'Terlalu banyak percobaan. Silakan minta OTP baru.']);
        }

        if (!$cachedOtp || $cachedOtp !== $request->otp) {
            Cache::increment('otp_attempts_' . $request->phone);
            return back()->withErrors(['otp' => 'Kode OTP salah atau sudah kedaluwarsa.']);
        }

        // OTP valid - create a temporary token for password reset
        $resetToken = Hash::make($request->phone . now()->timestamp);
        Cache::put('reset_token_' . $request->phone, $resetToken, now()->addMinutes(10));
        Cache::forget('otp_' . $request->phone);
        Cache::forget('otp_attempts_' . $request->phone);

        return redirect()->route('password.reset', [
            'phone' => $request->phone,
            'token' => urlencode($resetToken),
        ]);
    }

    /**
     * Show the reset password form.
     */
    public function showResetForm(Request $request)
    {
        $phone = $request->query('phone');
        $token = $request->query('token');

        if (!$phone || !$token) {
            return redirect()->route('password.request');
        }

        // Verify reset token
        $cachedToken = Cache::get('reset_token_' . $phone);
        if (!$cachedToken || $cachedToken !== urldecode($token)) {
            return redirect()->route('password.request')
                ->withErrors(['phone' => 'Sesi reset password tidak valid. Silakan ulangi.']);
        }

        $articles = Article::latest()->take(4)->get();
        return view('auth.reset-password', compact('phone', 'token', 'articles'));
    }

    /**
     * Reset the user's password.
     */
    public function resetPassword(Request $request)
    {
        $request->validate([
            'phone' => ['required', 'string'],
            'token' => ['required', 'string'],
            'password' => ['required', 'min:8', 'confirmed'],
        ]);

        // Verify reset token
        $cachedToken = Cache::get('reset_token_' . $request->phone);
        if (!$cachedToken || $cachedToken !== urldecode($request->token)) {
            return redirect()->route('password.request')
                ->withErrors(['phone' => 'Sesi reset password tidak valid. Silakan ulangi.']);
        }

        $user = User::where('phone', $request->phone)->first();

        if (!$user) {
            return back()->withErrors(['phone' => 'User tidak ditemukan.']);
        }

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        // Cleanup
        Cache::forget('reset_token_' . $request->phone);

        return redirect()->route('login')->with('status', 'Password berhasil direset. Silakan login dengan password baru.');
    }

    /**
     * Resend OTP.
     */
    public function resendOtp(Request $request)
    {
        $request->validate([
            'phone' => ['required', 'string', 'regex:/^08[0-9]{8,13}$/'],
        ]);

        $user = User::where('phone', $request->phone)->first();

        if (!$user) {
            return back()->withErrors(['phone' => 'Nomor HP tidak terdaftar.']);
        }

        // Rate limit: check if OTP was sent recently
        if (Cache::has('otp_cooldown_' . $request->phone)) {
            return back()->withErrors(['phone' => 'Tunggu 60 detik sebelum mengirim ulang OTP.']);
        }

        $otp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        Cache::put('otp_' . $request->phone, $otp, now()->addMinutes(5));
        Cache::put('otp_attempts_' . $request->phone, 0, now()->addMinutes(5));
        Cache::put('otp_cooldown_' . $request->phone, true, now()->addSeconds(60));

        $fonnte = new FonnteService();
        $fonnte->sendOtp($request->phone, $otp);

        return back()->with('status', 'Kode OTP baru telah dikirim ke WhatsApp Anda.');
    }
}
