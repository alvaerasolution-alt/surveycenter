<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureEmailIsVerified
{
    /**
     * Handle an incoming request.
     *
     * Redirect to email verification page if the user's email is not verified.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->user() && !$request->user()->hasVerifiedEmail()) {
            // Store email in session for the verification form
            session(['verify_email' => $request->user()->email]);

            return redirect()->route('verification.notice', [
                'email' => $request->user()->email,
            ])->with('status', 'Silakan verifikasi email Anda terlebih dahulu.');
        }

        return $next($request);
    }
}
