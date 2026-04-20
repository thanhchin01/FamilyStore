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
Route::post('/register', [AuthController::class, 'register'])->name('register');

Route::get('/admin/login', [AuthController::class, 'showAdminLoginForm'])->name('admin.login');
Route::post('/admin/login', [AuthController::class, 'adminLogin']);

Route::post('/logout', [AuthController::class, 'logout'])
    ->name('logout')
    ->middleware('auth');

// Admin routes
require __DIR__ . '/admin.php';
