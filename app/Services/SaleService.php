<?php

namespace App\Services;

use App\Models\Sales;
use App\Models\Products;
use App\Models\Customers;

class SaleService
{
    protected DebtService $debtService;

    public function __construct(DebtService $debtService)
    {
        $this->debtService = $debtService;
    }

    /**
     * Bán hàng nhiều dòng (1 hóa đơn)
     */
    public function sellInvoice(
        array $items,
        ?Customers $customer = null,
        int $paidAmount = 0
    ): string {
        // ✅ Added: invoice_code để gom nhiều dòng bán hàng vào 1 hóa đơn
        $invoiceCode = (string) \Illuminate\Support\Str::uuid();

        // Tính tổng tiền hóa đơn
        $invoiceTotal = 0;
        foreach ($items as $item) {
            $invoiceTotal += ((int) $item['price']) * ((int) $item['quantity']);
        }

        // ✅ Khách vãng lai: luôn trả đủ, không ghi nợ
        if ($customer === null) {
            $paidAmount = $invoiceTotal;
        }

        // Trạng thái thanh toán theo hóa đơn
        $paymentStatus = 'paid';
        if ($customer && $paidAmount < $invoiceTotal) {
            $paymentStatus = 'debt';
        }

        $firstSaleId = null;
        $soldAt = now();

        foreach ($items as $item) {
            /** @var Products $product */
            $product = $item['product'];
            $quantity = (int) $item['quantity'];
            $price = (int) $item['price'];

            // 1) Tạo dòng bán
            $sale = Sales::create([
                'invoice_code' => $invoiceCode,
                'product_id' => $product->id,
                'customer_id' => $customer?->id,
                'quantity' => $quantity,
                'price' => $price,
                // ✅ Lưu paid_amount theo hóa đơn (lặp lại trên từng dòng để đọc lịch sử dễ)
                'payment_status' => $paymentStatus,
                'paid_amount' => $paidAmount,
                'sold_at' => $soldAt,
            ]);

            $firstSaleId ??= $sale->id;

            // 2) Trừ kho
            $product->decrement('stock', $quantity);
        }

        // 3) Ghi nợ theo hóa đơn (nếu có)
        if ($customer && $paidAmount < $invoiceTotal && $firstSaleId) {
            $this->debtService->addDebt(
                $customer,
                $invoiceTotal - $paidAmount,
                $firstSaleId,
                'Nợ từ hóa đơn ' . $invoiceCode
            );
        }

        return $invoiceCode;
    }
}
