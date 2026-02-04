<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;

Route::get('/', function () {
    return view('welcome');
});

Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('dashboard');
    Route::get('/inventory', [AdminController::class, 'inventory'])->name('inventory');
    Route::get('/sale', [AdminController::class, 'sale'])->name('sale');
    Route::get('/debt', [AdminController::class, 'debt'])->name('debt');
    Route::get('/stock', [AdminController::class, 'stock'])->name('stock');
    Route::get('/products', [AdminController::class, 'products'])->name('products');

    // Route::get('/users', [AdminController::class, 'users'])->name('users');stock
});

// Authentication routes (simple custom)
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login')->middleware('guest');
Route::post('/login', [AuthController::class, 'login'])->middleware('guest');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');
