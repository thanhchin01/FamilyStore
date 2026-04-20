<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

use App\Http\Requests\Client\Auth\RegisterRequest;
use App\Http\Requests\Client\Auth\ClientLoginRequest;
use App\Http\Requests\Admin\Auth\AdminLoginRequest;

class AuthController extends Controller
{
    // --- Client Auth ---
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function showRegisterForm()
    {
        return view('auth.register');
    }

    public function register(RegisterRequest $request)
    {
        $user = \App\Models\User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => \Illuminate\Support\Facades\Hash::make($request->password),
            'role' => 'client',
            'status' => 'active',
        ]);

        Auth::login($user);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Đăng ký tài khoản thành công!',
                'redirect' => route('client.home')
            ]);
        }

        return redirect()->route('client.home')->with('success', 'Đăng ký tài khoản thành công!');
    }

    // --- Admin Auth ---
    public function showAdminLoginForm()
    {
        return view('auth.admin-login');
    }

    // --- Shared Logic ---
    public function login(ClientLoginRequest $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials, $request->filled('remember'))) {
            $user = Auth::user();

            // Ngăn chặn Admin đăng nhập qua trang Client
            if ($user->role === 'admin') {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                if ($request->ajax()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Tài khoản quản trị vui lòng đăng nhập tại trang quản lý.'
                    ], 403);
                }

                throw ValidationException::withMessages([
                    'email' => ['Tài khoản quản trị vui lòng đăng nhập tại trang quản lý.'],
                ]);
            }

            $request->session()->regenerate();

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Đăng nhập thành công!',
                    'redirect' => redirect()->intended(route('client.home'))->getTargetUrl()
                ]);
            }

            return redirect()->intended(route('client.home'))->with('client_success', 'Đăng nhập thành công.');
        }

        if ($request->ajax()) {
            $userExists = \App\Models\User::where('email', $request->email)->exists();
            $message = $userExists ? 'Mật khẩu không chính xác.' : 'Email này chưa được đăng ký.';

            return response()->json([
                'success' => false,
                'message' => $message
            ], 422);
        }

        throw ValidationException::withMessages([
            'email' => [trans('auth.failed')],
        ]);
    }

    // --- Admin Login Processor ---
    public function adminLogin(AdminLoginRequest $request)
    {
        $credentials = $request->only('username', 'password');

        if (Auth::attempt($credentials, $request->filled('remember'))) {
            $user = Auth::user();

            // Chỉ cho phép role admin đăng nhập tại đây
            if ($user->role !== 'admin') {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();
                throw ValidationException::withMessages([
                    'username' => ['Tài khoản này không có quyền truy cập quản trị.'],
                ]);
            }

            $request->session()->regenerate();
            return redirect()->intended(route('admin.dashboard'))->with('admin_success', 'Chào mừng Admin quay trở lại.');
        }

        // Tùy chỉnh thông báo lỗi chi tiết
        $userExists = \App\Models\User::where('username', $request->username)->exists();
        $message = $userExists ? 'Mật khẩu quản trị không chính xác.' : 'Tên đăng nhập không tồn tại trong hệ thống.';

        throw ValidationException::withMessages([
            'username' => [$message],
        ]);
    }

    public function logout(Request $request)
    {
        $isAdmin = Auth::check() && Auth::user()->role === 'admin';

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        if ($isAdmin) {
            return redirect()->route('admin.login')->with('admin_success', 'Đã đăng xuất khỏi hệ thống quản trị.');
        }

        return redirect('/')->with('client_success', 'Đã đăng xuất.');
    }
}
