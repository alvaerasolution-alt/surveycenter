<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('admin.auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string',
        ]);

        if (Auth::attempt($credentials)) {
            if (Auth::user()->is_admin) {
                $request->session()->regenerate();

                ActivityLog::log('login', 'Admin logged in: ' . Auth::user()->email, [
                    'role' => 'admin',
                ]);

                return redirect()->intended(route('pilih-dashboard'));
            }

            Auth::logout();
            return back()->withErrors([
                'email' => 'Anda tidak memiliki akses sebagai admin.',
            ]);
        }

        ActivityLog::log('login_failed', 'Failed admin login attempt: ' . $request->email, [
            'email' => $request->email,
        ]);

        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ]);
    }

    public function logout(Request $request)
    {
        $user = Auth::user();

        ActivityLog::log('logout', 'Admin logged out: ' . ($user?->email ?? 'unknown'), [
            'role' => 'admin',
        ]);

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('admin.login');
    }
}

