<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class ClientMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check() && Auth::user()->role === 'client') {
            return $next($request);
        }

        Auth::logout();
        return redirect()->route('login')->with('error', 'Vui lòng đăng nhập tài khoản khách hàng.');
    }
}
