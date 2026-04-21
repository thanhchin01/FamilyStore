<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Client\AuthController as ClientAuth;
use App\Http\Controllers\Api\Client\ProductController as ClientProduct;
use App\Http\Controllers\Api\Client\OrderController as ClientOrder;
use App\Http\Controllers\Api\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\Api\Admin\OrderManagerController as AdminOrder;
use App\Http\Controllers\Api\Admin\InventoryController as AdminInventory;
use App\Http\Controllers\Api\Admin\DebtManagerController as AdminDebt;
use App\Http\Controllers\Api\Common\ChatController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// --- CLIENT APIS ---
Route::prefix('client')->group(function () {
    
    // Công khai
    Route::post('/login', [ClientAuth::class, 'login']);
    Route::post('/request-register-otp', [ClientAuth::class, 'requestRegisterOtp']);
    Route::post('/register', [ClientAuth::class, 'register']);
    Route::get('/products', [ClientProduct::class, 'index']);
    Route::get('/products/{id}', [ClientProduct::class, 'show']);
    Route::get('/categories', [ClientProduct::class, 'categories']);

    // Yêu cầu đăng nhập (Sanctum)
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/me', [ClientAuth::class, 'me']);
        Route::post('/logout', [ClientAuth::class, 'logout']);
        
        // Orders
        Route::get('/orders', [ClientOrder::class, 'index']);
        Route::get('/orders/{id}', [ClientOrder::class, 'show']);
        Route::post('/orders', [ClientOrder::class, 'store']);

        // Chat
        Route::get('/chat/messages', [ChatController::class, 'getMessages']);
        Route::post('/chat/send', [ChatController::class, 'sendMessage']);
    });
});

// --- ADMIN APIS ---
Route::prefix('admin')->middleware(['auth:sanctum', 'admin'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [AdminDashboard::class, 'index']);
    
    // Order Management
    Route::get('/orders', [AdminOrder::class, 'index']);
    Route::get('/orders/{id}', [AdminOrder::class, 'show']);
    Route::post('/orders/{id}/status', [AdminOrder::class, 'updateStatus']);

    // Inventory Management
    Route::get('/inventory', [AdminInventory::class, 'index']);
    Route::post('/inventory/import', [AdminInventory::class, 'quickImport']);

    // Debt Management
    Route::get('/debts', [AdminDebt::class, 'index']);
    Route::get('/debts/{customerId}/history', [AdminDebt::class, 'history']);
    Route::post('/debts/pay', [AdminDebt::class, 'recordPayment']);

    // Chat Management
    Route::get('/chat/conversations', [ChatController::class, 'adminConversations']);
    Route::get('/chat/conversations/{id}/messages', [ChatController::class, 'getMessages']);
    Route::post('/chat/conversations/{id}/reply', [ChatController::class, 'sendMessage']);
});
