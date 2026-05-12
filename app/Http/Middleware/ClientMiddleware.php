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
        // Sử dụng guard 'web' cho khách hàng (Client)
        if (Auth::guard('web')->check()) {
            return $next($request);
        }

        // Nếu không phải khách hàng, xóa session và yêu cầu đăng nhập
        Auth::guard('web')->logout();
        return redirect()->route('login')->with('error', 'Vui lòng đăng nhập tài khoản khách hàng.');
    }

}
