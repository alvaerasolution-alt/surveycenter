<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CaptureReferral
{
    /**
     * Capture ?ref= query parameter and store it in the session.
     * This persists across pages until the user registers.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->has('ref') && !auth()->check()) {
            $request->session()->put('referral_code', $request->query('ref'));
        }

        return $next($request);
    }
}
