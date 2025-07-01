<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class Authenticate
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return redirect()->route('users.login')->with('error', 'Please login first.');
        }

        if (is_null(Auth::user()->email_verified_at)) {
            return redirect()->route('verification.notice')->with('error', 'Please verify your email address.');
        }

        return $next($request);
    }
}
