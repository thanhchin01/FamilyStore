<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use App\Http\Requests\Admin\Auth\AdminLoginRequest;

class AdminAuthController extends Controller
{
    /**
     * Hiển thị trang đăng nhập admin
     */
    public function showLoginForm()
    {
        return view('auth.admin-login');
    }

    /**
     * Xử lý đăng nhập admin
     */
    public function login(AdminLoginRequest $request)
    {
        $credentials = $request->only('username', 'password');

        if (Auth::guard('admin')->attempt($credentials, $request->filled('remember'))) {
            $request->session()->regenerate();
            return redirect()->intended(route('admin.dashboard'))
                ->with('admin_success', 'Chào mừng Admin quay trở lại.');
        }

        throw ValidationException::withMessages([
            'username' => ['Thông tin đăng nhập quản trị không chính xác.'],
        ]);
    }

    /**
     * Đăng xuất admin
     */
    public function logout(Request $request)
    {
        Auth::guard('admin')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login')
            ->with('admin_success', 'Đã đăng xuất khỏi hệ thống quản trị.');
    }
}
