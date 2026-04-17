<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Client\StoreController;

Route::name('client.')->group(function () {
    Route::get('/', [StoreController::class, 'home'])->name('home');
    Route::get('/product/{slug}', [StoreController::class, 'productDetail'])->name('products.show');
    Route::get('/products', [StoreController::class, 'products'])->name('products.index');
    Route::get('/cart', [StoreController::class, 'cart'])->name('cart');
    Route::get('/checkout', [StoreController::class, 'checkout'])->name('checkout');

    // Protected Client Routes
    Route::middleware(['auth', 'client'])->group(function () {
        Route::get('/profile', [\App\Http\Controllers\Client\ProfileController::class, 'index'])->name('profile');
        Route::put('/profile', [\App\Http\Controllers\Client\ProfileController::class, 'update'])->name('profile.update');
    });
});
