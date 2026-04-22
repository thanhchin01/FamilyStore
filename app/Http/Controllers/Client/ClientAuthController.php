<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Services\Common\OtpService;
use App\Http\Requests\Client\Auth\RegisterRequest;
use App\Http\Requests\Client\Auth\ClientLoginRequest;

class ClientAuthController extends Controller
{
    protected $otpService;

    public function __construct(OtpService $otpService)
    {
        $this->otpService = $otpService;
    }

    /**
     * Gửi mã OTP đăng ký qua AJAX
     */
    public function requestOtp(Request $request)
    {
        $request->validate([
            'identifier' => 'required',
            'type'       => 'required|in:email,phone'
        ]);

        $identifier = $request->identifier;
        $field = $request->type === 'email' ? 'email' : 'phone';

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

    /**
     * Xử lý đăng ký tài khoản mới (AJAX)
     */
    public function register(RegisterRequest $request)
    {
        $identifier = $request->email ?? $request->phone;

        if (!$this->otpService->verifyOtp($identifier, $request->otp)) {
            return response()->json(['success' => false, 'message' => 'Mã OTP không chính xác hoặc đã hết hạn.'], 422);
        }

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'phone'    => $request->phone,
            'password' => Hash::make($request->password),
            'role'     => 'client',
            'status'   => 'active',
        ]);

        Auth::guard('web')->login($user);

        return response()->json([
            'success' => true,
            'message' => 'Đăng ký tài khoản thành công!',
            'redirect' => route('client.home')
        ]);
    }

    /**
     * Xử lý đăng nhập khách hàng (AJAX)
     */
    public function login(ClientLoginRequest $request)
    {
        $user = User::where('email', $request->email)
            ->orWhere('phone', $request->email)
            ->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['success' => false, 'message' => 'Thông tin đăng nhập không chính xác.'], 422);
        }

        if ($user->status === 'blocked') {
            return response()->json(['success' => false, 'message' => 'Tài khoản của bạn đã bị vô hiệu hóa.'], 403);
        }

        Auth::guard('web')->login($user, $request->filled('remember'));
        $request->session()->regenerate();

        $intendedUrl = redirect()->intended(route('client.home'))->getTargetUrl();
        if (str_contains($intendedUrl, '/admin')) {
            $intendedUrl = route('client.home');
        }

        return response()->json([
            'success' => true,
            'message' => 'Đăng nhập thành công!',
            'redirect' => $intendedUrl
        ]);
    }

    /**
     * Đăng xuất khách hàng
     */
    public function logout(Request $request)
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('client_success', 'Đã đăng xuất.');
    }

    /**
     * --- Quên mật khẩu ---
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
            return response()->json(['success' => false, 'message' => 'Thông tin này chưa được đăng ký.'], 404);
        }

        $otp = $this->otpService->generateOtp($request->identifier);

        return response()->json([
            'success' => true,
            'message' => 'Mã xác thực đã được gửi!',
            'otp_preview' => config('app.debug') ? $otp : null
        ]);
    }

    public function verifyResetOtp(Request $request)
    {
        $request->validate(['identifier' => 'required', 'otp' => 'required|string|size:6']);

        if ($this->otpService->verifyOtp($request->identifier, $request->otp)) {
            session(['reset_verified_identifier' => $request->identifier]);
            return response()->json(['success' => true, 'message' => 'Xác minh thành công.']);
        }

        return response()->json(['success' => false, 'message' => 'Mã OTP không chính xác.'], 422);
    }

    public function resetPassword(Request $request)
    {
        $request->validate(['password' => 'required|string|min:8|confirmed']);

        $identifier = session('reset_verified_identifier');
        if (!$identifier) {
            return response()->json(['success' => false, 'message' => 'Phiên hết hạn.'], 403);
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
