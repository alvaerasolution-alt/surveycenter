<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;
use Exception;

class GoogleAuthController extends Controller
{
    /**
     * Redirect the user to the Google authentication page.
     *
     * @return \Illuminate\Http\Response
     */
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Obtain the user information from Google.
     *
     * @return \Illuminate\Http\Response
     */
    public function handleGoogleCallback(Request $request)
    {
        try {
            $googleUser = Socialite::driver('google')->user();
            
            // Check if user already exists based on google_id or email
            $user = User::where('google_id', $googleUser->id)
                        ->orWhere('email', $googleUser->email)
                        ->first();

            if ($user) {
                // Update google_id if it was null (meaning the user registered via email previously)
                if (empty($user->google_id)) {
                    $user->update([
                        'google_id' => $googleUser->id,
                        'google_avatar' => $googleUser->avatar
                    ]);
                }
                
                // Process Login
                Auth::login($user);
                $request->session()->regenerate();
                
                ActivityLog::log('login', 'User logged in via Google: ' . $user->email, [
                    'role' => 'user',
                    'method' => 'google'
                ]);

                return redirect()->intended(route('user.dashboard'));
            } else {
                // Register a new user
                // As the phone is required in the DB, we can either set a placeholder or make it nullable in DB.
                // Since this system requires a phone, we might generate a dummy one or redirect to a form to complete profile.
                // For simplicity here, we create the user and they might need to update their profile later.
                
                $newUser = User::create([
                    'name' => $googleUser->name,
                    'email' => $googleUser->email,
                    'google_id' => $googleUser->id,
                    'google_avatar' => $googleUser->avatar,
                    'password' => Hash::make(Str::random(16)), // set a random password
                    'phone' => '08000000000', // Placeholder phone number
                ]);
                
                $newUser->forceFill([
                    'email_verified_at' => now(), // Google emails are already verified
                ])->save();

                Auth::login($newUser);
                $request->session()->regenerate();
                
                ActivityLog::log('register', 'User registered via Google: ' . $newUser->email, [
                    'role' => 'user',
                    'method' => 'google'
                ]);

                return redirect()->route('user.dashboard')
                    ->with('success', 'Registrasi dengan Google berhasil! Silakan perbarui nomor telepon Anda di menu Profil.');
            }

        } catch (Exception $e) {
            ActivityLog::log('login_failed', 'Failed Google login attempt', [
                'error' => $e->getMessage(),
            ]);
            
            return redirect()->route('login')->withErrors(['email' => 'Gagal login menggunakan Google. Silakan coba lagi.']);
        }
    }
}
