<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may register web routes for your application.
|
*/

// Client routes
require __DIR__ . '/client.php';

// Authentication routes
Route::get('/login', function() {
    return redirect()->route('client.home')->with('info', 'Vui lòng đăng nhập để tiếp tục.');
})->name('login');

Route::post('/login', [AuthController::class, 'login']);
Route::post('/otp/request', [AuthController::class, 'requestOtp'])->name('otp.request');
Route::post('/register', [AuthController::class, 'register'])->name('register');

Route::get('/admin/login', [AuthController::class, 'showAdminLoginForm'])->name('admin.login');
Route::post('/admin/login', [AuthController::class, 'adminLogin']);

// Forgot Password Flow
Route::get('/forgot-password', [AuthController::class, 'showForgotPasswordForm'])->name('password.request');
Route::post('/forgot-password/otp', [AuthController::class, 'sendResetOtp'])->name('password.otp');
Route::post('/forgot-password/verify', [AuthController::class, 'verifyResetOtp'])->name('password.verify');
Route::post('/forgot-password/reset', [AuthController::class, 'resetPassword'])->name('password.update');

Route::post('/logout', [AuthController::class, 'logout'])
    ->name('logout')
    ->middleware('auth');

// Admin routes
require __DIR__ . '/admin.php';
