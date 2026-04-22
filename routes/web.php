<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Client\ClientAuthController;
use App\Http\Controllers\Admin\AdminAuthController;

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
Route::get('/login', function () {
    return redirect()->route('client.home')->with('info', 'Vui lòng đăng nhập để tiếp tục.');
})->name('login');

Route::post('/login', [ClientAuthController::class, 'login']);
Route::post('/otp/request', [ClientAuthController::class, 'requestOtp'])->name('otp.request');
Route::post('/register', [ClientAuthController::class, 'register'])->name('register');

Route::get('/admin/login', [AdminAuthController::class, 'showLoginForm'])->name('admin.login');
Route::post('/admin/login', [AdminAuthController::class, 'login']);

// Forgot Password Flow
Route::get('/forgot-password', [ClientAuthController::class, 'showForgotPasswordForm'])->name('password.request');
Route::post('/forgot-password/otp', [ClientAuthController::class, 'sendResetOtp'])->name('password.otp');
Route::post('/forgot-password/verify', [ClientAuthController::class, 'verifyResetOtp'])->name('password.verify');
Route::post('/forgot-password/reset', [ClientAuthController::class, 'resetPassword'])->name('password.update');

Route::post('/logout', [ClientAuthController::class, 'logout'])->name('logout');
Route::post('/admin/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');




// Admin routes
require __DIR__ . '/admin.php';
