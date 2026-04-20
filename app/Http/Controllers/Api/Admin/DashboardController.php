<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Sales;
use App\Models\Products;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Thống kê tổng quan cho mobile
     */
    public function index()
    {
        $today = Carbon::today();

        // 1. Doanh thu hôm nay (Từ Sales - Bán tại quầy)
        $posRevenue = Sales::whereDate('sold_at', $today)->sum(DB::raw('price * quantity'));
        
        // 2. Doanh thu hôm nay (Từ Order - Online)
        $onlineRevenue = Order::whereDate('placed_at', $today)
            ->where('payment_status', 'paid')
            ->sum('grand_total');

        // 3. Số đơn hàng mới (Online)
        $newOrders = Order::where('status', 'pending')->count();

        // 4. Sản phẩm sắp hết hàng
        $lowStockCount = Products::where('stock', '<=', 5)->count();

        return response()->json([
            'success' => true,
            'data'    => [
                'revenue_today'    => $posRevenue + $onlineRevenue,
                'new_orders_count' => $newOrders,
                'low_stock_count'  => $lowStockCount,
                'pos_revenue'      => $posRevenue,
                'online_revenue'   => $onlineRevenue
            ]
        ]);
    }
}
