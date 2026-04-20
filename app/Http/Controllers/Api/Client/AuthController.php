<?php

namespace App\Http\Controllers\Api\Client;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Đăng nhập và lấy token
     */
    public function login(Request $request)
    {
        $request->validate([
            'email'       => 'required|email',
            'password'    => 'required',
            'device_name' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['Thông tin đăng nhập không chính xác.'],
            ]);
        }

        // Tạo token mới
        $token = $user->createToken($request->device_name)->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Đăng nhập thành công',
            'token'   => $token,
            'user'    => $user
        ]);
    }

    /**
     * Đăng ký tài khoản mới
     */
    public function register(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => 'client'
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Đăng ký tài khoản thành công',
            'user'    => $user
        ], 201);
    }

    /**
     * Đăng xuất (Xóa token hiện tại)
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Đã đăng xuất thành công'
        ]);
    }

    /**
     * Lấy thông tin user hiện tại
     */
    public function me(Request $request)
    {
        return response()->json([
            'success' => true,
            'user'    => $request->user()
        ]);
    }
}
