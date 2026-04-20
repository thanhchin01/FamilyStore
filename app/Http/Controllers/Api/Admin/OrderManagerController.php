<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\Client\OrderService;
use Illuminate\Http\Request;

class OrderManagerController extends Controller
{
    protected $orderService;

    public function __construct(OrderService $orderService) // Dùng lại Service đã có
    {
        $this->orderService = $orderService;
    }

    /**
     * Danh sách tất cả đơn hàng online
     */
    public function index(Request $request)
    {
        $orders = Order::with(['items', 'address'])
            ->when($request->status, function($q) use ($request) {
                $q->where('status', $request->status);
            })
            ->latest()
            ->paginate($request->get('per_page', 15));

        return response()->json([
            'success' => true,
            'data'    => $orders
        ]);
    }

    /**
     * Cập nhật trạng thái đơn hàng (Duyệt, Hủy, Giao hàng...)
     */
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,processing,shipping,completed,cancelled',
            'note'   => 'nullable|string'
        ]);

        try {
            $order = $this->orderService->updateStatus($id, $request->status, $request->note);
            
            return response()->json([
                'success' => true,
                'message' => 'Cập nhật trạng thái đơn hàng thành công',
                'data'    => $order
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Chi tiết đơn hàng cho Admin
     */
    public function show($id)
    {
        $order = Order::with(['items', 'items.product', 'address', 'payments', 'statusHistories', 'statusHistories.causer'])
            ->findOrFail($id);

        return response()->json([
            'success' => true,
            'data'    => $order
        ]);
    }
}
