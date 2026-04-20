<?php

namespace App\Services\Client;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderStatusHistory;
use App\Models\Products;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class OrderService
{
    public function createOrder(array $data)
    {
        return DB::transaction(function () use ($data) {
            $orderCode = $this->generateOrderCode();

            $order = Order::create([
                'order_code'       => $orderCode,
                'user_id'          => auth()->id(),
                'customer_id'      => $data['customer_id'] ?? null,
                'status'           => 'pending',
                'subtotal_amount'  => $data['subtotal'],
                'shipping_fee'     => $data['shipping_fee'] ?? 0,
                'discount_amount'  => $data['discount'] ?? 0,
                'grand_total'      => $data['grand_total'],
                'payment_method'   => $data['payment_method'],
                'payment_status'   => 'unpaid',
                'shipping_name'    => $data['shipping_name'],
                'shipping_phone'   => $data['shipping_phone'],
                'shipping_address' => $data['shipping_address'],
                'note'             => $data['note'] ?? null,
                'placed_at'        => now(),
            ]);

            foreach ($data['items'] as $item) {
                OrderItem::create([
                    'order_id'   => $order->id,
                    'product_id' => $item['product_id'],
                    'qty'        => $item['quantity'],
                    'price'      => $item['price'],
                    'total'      => $item['price'] * $item['quantity'],
                ]);

                $product = Products::find($item['product_id']);
                if ($product) {
                    $product->decrement('stock', $item['quantity']);
                }
            }

            $this->logStatusHistory($order->id, 'pending', 'Đơn hàng đã được đặt thành công.');

            return $order;
        });
    }

    public function updateStatus($orderId, $newStatus, $note = null)
    {
        return DB::transaction(function () use ($orderId, $newStatus, $note) {
            $order = Order::findOrFail($orderId);
            $oldStatus = $order->status;

            if ($oldStatus === $newStatus) {
                return $order;
            }

            $order->update(['status' => $newStatus]);
            $this->logStatusHistory($order->id, $newStatus, $note);

            if ($newStatus === 'cancelled' && $oldStatus !== 'cancelled') {
                $this->restoreStock($order);
            }

            return $order;
        });
    }

    private function restoreStock(Order $order)
    {
        foreach ($order->items as $item) {
            $product = Products::find($item->product_id);
            if ($product) {
                $product->increment('stock', $item->qty);
            }
        }
    }

    private function logStatusHistory($orderId, $status, $note = null)
    {
        OrderStatusHistory::create([
            'order_id'   => $orderId,
            'status'     => $status,
            'note'       => $note,
            'changed_by' => auth()->id(),
        ]);
    }

    private function generateOrderCode()
    {
        $prefix = 'ORD-';
        $datePart = now()->format('Ymd');
        
        do {
            $randomPart = strtoupper(Str::random(6));
            $code = $prefix . $datePart . '-' . $randomPart;
        } while (Order::where('order_code', $code)->exists());

        return $code;
    }
}
