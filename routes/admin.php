<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\SaleController;
use App\Http\Controllers\Admin\ImportController;

Route::prefix('admin')
    ->name('admin.')
    ->middleware(['auth', 'admin'])
    ->group(function () {
        Route::get('/', [AdminController::class, 'index'])->name('dashboard');
        Route::get('/inventory', [AdminController::class, 'inventory'])->name('inventory');

        Route::get('/sale', [AdminController::class, 'sale'])->name('sale');
        Route::post('/sale', [SaleController::class, 'store'])
            ->name('sale.store')
            ->middleware('auth');
        Route::get('/sales/history', [AdminController::class, 'saleHistory']);
        Route::get('/customers/find-by-phone', [AdminController::class, 'findCustomerByPhone']);
        Route::post('/debt/pay', [AdminController::class, 'payDebt'])->name('debt.pay');

        Route::get('/history', [AdminController::class, 'history'])->name('history');
        Route::get('/debt', [AdminController::class, 'debt'])->name('debt');
        Route::get('/stock', [AdminController::class, 'stock'])->name('stock');
        Route::post('/stock', [ImportController::class, 'store'])->name('stock.store');

        // Product
        Route::get('/products', [AdminController::class, 'products'])->name('products');
        Route::post('/products', [ProductController::class, 'store'])->name('products.store');
        Route::put('/products/{slug}', [ProductController::class, 'update'])->name('products.update');
        Route::post('/products/{slug}/disable', [ProductController::class, 'disable'])->name('products.disable');
        Route::delete('/products/{slug}', [ProductController::class, 'destroy'])->name('products.destroy');

        Route::get('/statistics', [AdminController::class, 'statistics'])->name('statistics');
    });
