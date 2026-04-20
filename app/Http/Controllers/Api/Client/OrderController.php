<?php

namespace App\Http\Controllers\Api\Client;

use App\Http\Controllers\Controller;
use App\Services\Client\OrderService;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    protected $orderService;

    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    /**
     * Danh sách đơn hàng của tôi
     */
    public function index(Request $request)
    {
        $orders = Order::where('user_id', $request->user()->id)
            ->withCount('items')
            ->latest()
            ->paginate(10);

        return response()->json([
            'success' => true,
            'data'    => $orders
        ]);
    }

    /**
     * Chi tiết đơn hàng
     */
    public function show($id, Request $request)
    {
        $order = Order::with(['items', 'items.product', 'address', 'payments', 'statusHistories'])
            ->where('user_id', $request->user()->id)
            ->findOrFail($id);

        return response()->json([
            'success' => true,
            'data'    => $order
        ]);
    }

    /**
     * Đặt hàng từ dữ liệu gửi lên
     */
    public function store(Request $request)
    {
        $request->validate([
            'shipping_name'    => 'required|string|max:255',
            'shipping_phone'   => 'required|string|max:20',
            'shipping_address' => 'required|string',
            'payment_method'   => 'required|in:cod,transfer',
            'items'            => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity'   => 'required|integer|min:1',
            'items.*.price'      => 'required|numeric',
        ]);

        $subtotal = 0;
        foreach ($request->items as $item) {
            $subtotal += $item['price'] * $item['quantity'];
        }

        $orderData = [
            'customer_id'      => null,
            'subtotal'         => $subtotal,
            'shipping_fee'     => 0,
            'grand_total'      => $subtotal,
            'payment_method'   => $request->payment_method,
            'shipping_name'    => $request->shipping_name,
            'shipping_phone'   => $request->shipping_phone,
            'shipping_address' => $request->shipping_address,
            'note'             => $request->note,
            'items'            => $request->items
        ];

        try {
            $order = $this->orderService->createOrder($orderData);
            return response()->json([
                'success' => true,
                'message' => 'Đơn hàng đã được đặt thành công',
                'data'    => $order
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi khi đặt hàng: ' . $e->getMessage()
            ], 500);
        }
    }
}
