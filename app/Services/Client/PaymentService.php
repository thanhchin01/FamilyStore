<?php

namespace App\Services\Client;

use App\Models\Order;
use App\Models\OrderPayment;
use Illuminate\Support\Facades\DB;

class PaymentService
{
    /**
     * Khởi tạo bản ghi thanh toán cho đơn hàng
     */
    public function initializePayment(Order $order, $method = 'cod')
    {
        return OrderPayment::create([
            'order_id'       => $order->id,
            'payment_method' => $method,
            'amount'         => $order->grand_total,
            'status'         => 'pending',
            'transaction_id' => null, // Sẽ cập nhật sau khi có phản hồi từ cổng thanh toán
        ]);
    }

    /**
     * Xử lý khi thanh toán thành công
     */
    public function processSuccess(Order $order, $transactionId = null, $note = null)
    {
        return DB::transaction(function () use ($order, $transactionId, $note) {
            // 1. Cập nhật bản ghi thanh toán cuối cùng của đơn hàng
            $payment = $order->payments()->latest()->first();
            
            if ($payment) {
                $payment->update([
                    'status'         => 'success',
                    'transaction_id' => $transactionId,
                    'payed_at'       => now(),
                    'note'           => $note
                ]);
            }

            // 2. Cập nhật trạng thái đơn hàng và trạng thái thanh toán tổng quát
            $order->update([
                'payment_status' => 'paid',
                // Có thể chuyển trạng thái đơn hàng sang 'processing' nếu là thanh toán trước
                'status'         => ($order->status === 'pending') ? 'processing' : $order->status
            ]);

            return $payment;
        });
    }

    /**
     * Xử lý khi thanh toán thất bại
     */
    public function processFailed(Order $order, $reason = null)
    {
        $payment = $order->payments()->latest()->first();
        
        if ($payment) {
            $payment->update([
                'status' => 'failed',
                'note'   => $reason
            ]);
        }

        return $payment;
    }

    /**
     * Tạo URL thanh toán (Cửa sổ chờ cho VNPay/Momo/ZaloPay sau này)
     */
    public function generatePaymentUrl(Order $order, $gateway = 'vnpay')
    {
        // Hiện tại trả về null hoặc link giả lập
        // Đây là nơi bạn sẽ viết logic tích hợp API của các cổng thanh toán
        return null; 
    }
}
