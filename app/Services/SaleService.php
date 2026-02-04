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
     * Bán hàng
     */
    public function sell(
        Products $product,
        int $quantity,
        int $price,
        ?Customers $customer = null,
        int $paidAmount = 0
    ): Sales {
        // 1. Tạo bản ghi bán
        $sale = Sales::create([
            'product_id' => $product->id,
            'customer_id' => $customer?->id,
            'quantity' => $quantity,
            'price' => $price,
            'sold_at' => now(),
        ]);

        // 2. Trừ kho
        $product->decrement('stock', $quantity);

        // 3. Tính tiền
        $total = $price * $quantity;

        // 4. Nếu chưa trả đủ → ghi nợ
        if ($customer && $paidAmount < $total) {
            $this->debtService->addDebt(
                $customer,
                $total - $paidAmount,
                $sale->id,
                "Nợ từ lần mua sản phẩm {$product->name}"
            );
        }

        return $sale;
    }
}
