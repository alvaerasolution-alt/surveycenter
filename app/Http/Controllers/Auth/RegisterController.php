<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\OtpVerificationMail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class RegisterController extends Controller
{
    // Menampilkan form register
    public function showRegisterForm()
    {
        return view('auth.register');
    }

    // Proses registrasi user
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'required|string|regex:/^08[0-9]{8,13}$/|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
        ]);

        // Generate 6 digit OTP
        $otp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        // Store OTP in cache for 5 minutes
        Cache::put('email_otp_' . $user->email, $otp, now()->addMinutes(5));
        Cache::put('email_otp_attempts_' . $user->email, 0, now()->addMinutes(5));
        Cache::put('email_otp_cooldown_' . $user->email, true, now()->addSeconds(60));

        // Send OTP via email (Mailtrap)
        Mail::to($user->email)->send(new OtpVerificationMail($otp, $user->name));

        // Store email in session for verification page
        session(['verify_email' => $user->email]);

        return redirect()->route('verification.notice', ['email' => $user->email])
            ->with('status', 'Registrasi berhasil! Kode OTP telah dikirim ke email Anda.');
    }
}
