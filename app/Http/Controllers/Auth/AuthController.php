<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use App\Http\Requests\Client\Auth\RegisterRequest;
use App\Http\Requests\Client\Auth\ClientLoginRequest;
use App\Http\Requests\Admin\Auth\AdminLoginRequest;
use App\Models\User;
use App\Services\Common\OtpService;

class AuthController extends Controller
{
    protected $otpService;

    public function __construct(OtpService $otpService)
    {
        $this->otpService = $otpService;
    }

    // --- Client Auth ---
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function showRegisterForm()
    {
        return view('auth.register');
    }

    /**
     * Gửi mã OTP qua AJAX
     */
    public function requestOtp(Request $request)
    {
        $request->validate([
            'identifier' => 'required',
            'type'       => 'required|in:email,phone'
        ]);

        $identifier = $request->identifier;
        $field = $request->type === 'email' ? 'email' : 'phone';

        // Kiểm tra trùng
        if (User::where($field, $identifier)->exists()) {
            return response()->json(['success' => false, 'message' => 'Thông tin này đã được đăng ký tài khoản.'], 422);
        }

        $otp = $this->otpService->generateOtp($identifier);

        return response()->json([
            'success' => true,
            'message' => 'Mã OTP đã được gửi!',
            'otp_preview' => config('app.debug') ? $otp : null
        ]);
    }

    public function register(RegisterRequest $request)
    {
        $identifier = $request->email ?? $request->phone;

        // 1. Xác minh OTP
        if (!$this->otpService->verifyOtp($identifier, $request->otp)) {
            return response()->json(['success' => false, 'message' => 'Mã OTP không chính xác hoặc đã hết hạn.'], 422);
        }

        // 2. Tạo User
        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'phone'    => $request->phone,
            'password' => Hash::make($request->password),
            'role'     => 'client',
            'status'   => 'active',
        ]);

        Auth::login($user);

        return response()->json([
            'success' => true,
            'message' => 'Đăng ký tài khoản thành công!',
            'redirect' => route('client.home')
        ]);
    }

    // --- Shared Login (Client) ---
    public function login(ClientLoginRequest $request)
    {
        // Thử tìm user trước để xác minh identifier
        $user = User::where('email', $request->email)
            ->orWhere('phone', $request->email)
            ->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Thông tin đăng nhập không chính xác.'
            ], 422);
        }

        // Kiểm tra tài khoản bị khóa
        if ($user->status === 'blocked') {
            return response()->json([
                'success' => false,
                'message' => 'Tài khoản của bạn đã bị vô hiệu hóa. Vui lòng thử lại sau hoặc liên hệ Cửa hàng.'
            ], 403);
        }


        Auth::guard('web')->login($user, $request->filled('remember'));
        $request->session()->regenerate();

        $intendedUrl = redirect()->intended(route('client.home'))->getTargetUrl();
        // Nếu URL định chuyển hướng tới có chứa /admin, bắt buộc quay về trang chủ khách hàng
        if (str_contains($intendedUrl, '/admin')) {
            $intendedUrl = route('client.home');
        }

        return response()->json([
            'success' => true,
            'message' => 'Đăng nhập thành công!',
            'redirect' => $intendedUrl
        ]);

    }

    // --- Admin Auth ---
    public function showAdminLoginForm()
    {
        return view('auth.admin-login');
    }

    public function adminLogin(AdminLoginRequest $request)
    {
        $credentials = $request->only('username', 'password');

        if (Auth::guard('admin')->attempt($credentials, $request->filled('remember'))) {
            $request->session()->regenerate();
            return redirect()->intended(route('admin.dashboard'))->with('admin_success', 'Chào mừng Admin quay trở lại.');
        }

        throw ValidationException::withMessages([
            'username' => ['Thông tin đăng nhập quản trị không chính xác.'],
        ]);
    }

    public function logout(Request $request)
    {
        $isAdmin = Auth::guard('admin')->check();

        if ($isAdmin) {
            Auth::guard('admin')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            return redirect()->route('admin.login')->with('admin_success', 'Đã đăng xuất khỏi hệ thống quản trị.');
        }

        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('client_success', 'Đã đăng xuất.');
    }


    /**
     * --- Forgot Password Flow ---
     */
    public function showForgotPasswordForm()
    {
        return view('auth.forgot-password');
    }

    public function sendResetOtp(Request $request)
    {
        $request->validate(['identifier' => 'required']);

        $user = User::where('email', $request->identifier)
            ->orWhere('phone', $request->identifier)
            ->first();

        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Thông tin này chưa được đăng ký tài khoản.'], 404);
        }

        // Gửi OTP qua email hoặc phone (dùng chung OtpService)
        $otp = $this->otpService->generateOtp($request->identifier);

        return response()->json([
            'success' => true,
            'message' => 'Mã xác thực đã được gửi!',
            'otp_preview' => config('app.debug') ? $otp : null
        ]);
    }

    public function verifyResetOtp(Request $request)
    {
        $request->validate([
            'identifier' => 'required',
            'otp'        => 'required|string|size:6'
        ]);

        if ($this->otpService->verifyOtp($request->identifier, $request->otp)) {
            // Lưu trạng thái đã xác minh để bước đổi mật khẩu an toàn hơn
            session(['reset_verified_identifier' => $request->identifier]);
            return response()->json(['success' => true, 'message' => 'Xác minh thành công. Vui lòng nhập mật khẩu mới.']);
        }

        return response()->json(['success' => false, 'message' => 'Mã OTP không chính xác.'], 422);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'password' => 'required|string|min:8|confirmed'
        ]);

        $identifier = session('reset_verified_identifier');
        if (!$identifier) {
            return response()->json(['success' => false, 'message' => 'Phiên làm việc hết hạn. Vui lòng bắt đầu lại.'], 403);
        }

        $user = User::where('email', $identifier)->orWhere('phone', $identifier)->first();
        if ($user) {
            $user->update(['password' => Hash::make($request->password)]);
            session()->forget('reset_verified_identifier');

            return response()->json(['success' => true, 'message' => 'Đổi mật khẩu thành công.']);
        }

        return response()->json(['success' => false, 'message' => 'Có lỗi xảy ra.'], 500);
    }
}
