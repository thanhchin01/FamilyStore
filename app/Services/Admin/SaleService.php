<?php

namespace App\Services\Admin;

use App\Models\SaleInvoice;
use App\Models\SaleItem;
use App\Models\SalePayment;
use App\Models\Products;
use App\Models\Customers;
use App\Models\InventoryMovement;
use Illuminate\Support\Facades\DB;

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
        return DB::transaction(function () use ($items, $customer, $paidAmount) {
            $invoiceCode = (string) \Illuminate\Support\Str::uuid();
            $soldAt = now();

            // Tính tổng tiền hóa đơn
            $subtotalAmount = 0;
            foreach ($items as $item) {
                $subtotalAmount += ((int) $item['price']) * ((int) $item['quantity']);
            }

            // Khách vãng lai: luôn trả đủ
            if ($customer === null) {
                $paidAmount = $subtotalAmount;
            }

            $debtAmount = max($subtotalAmount - $paidAmount, 0);
            $status = $debtAmount > 0 ? 'completed' : 'completed'; // Có thể dùng 'partially_paid' nếu có

            // 1) Tạo Header hóa đơn
            $invoice = SaleInvoice::create([
                'invoice_code' => $invoiceCode,
                'customer_id' => $customer?->id,
                'created_by' => auth('admin')->id(),
                'channel' => 'pos',
                'status' => 'completed',
                'subtotal_amount' => $subtotalAmount,
                'discount_amount' => 0,
                'grand_total' => $subtotalAmount,
                'paid_amount' => $paidAmount,
                'debt_amount' => $debtAmount,
                'sold_at' => $soldAt,
            ]);

            // 2) Lưu chi tiết thanh toán ban đầu (nếu có)
            if ($paidAmount > 0) {
                SalePayment::create([
                    'sale_invoice_id' => $invoice->id,
                    'payment_method' => 'cash', // Mặc định cash
                    'amount' => $paidAmount,
                    'paid_at' => $soldAt,
                    'created_by' => auth('admin')->id(),
                ]);
            }

            foreach ($items as $item) {
                /** @var Products $product */
                $product = $item['product'];
                $quantity = (int) $item['quantity'];
                $price = (int) $item['price'];

                // 3) Tạo dòng sản phẩm trong hóa đơn
                SaleItem::create([
                    'sale_invoice_id' => $invoice->id,
                    'product_id' => $product->id,
                    'quantity' => $quantity,
                    'unit_price' => $price,
                    'subtotal' => $price * $quantity,
                ]);

                // 4) Ghi nhận biến động kho (Xuất kho)
                InventoryMovement::create([
                    'product_id' => $product->id,
                    'movement_type' => 'sale',

                    'quantity_delta' => $quantity,
                    'reference_type' => 'sale',
                    'reference_id' => $invoice->id,
                    'note' => 'Xuất hàng cho hóa đơn ' . $invoiceCode,
                    'occurred_at' => $soldAt,
                    'created_by' => auth('admin')->id(),
                ]);


                // 5) Trừ tồn kho
                $product->decrement('stock', $quantity);
            }

            // 6) Ghi nợ (nếu có)
            if ($customer && $debtAmount > 0) {
                $this->debtService->addDebt(
                    $customer,
                    $debtAmount,
                    $invoice->id,
                    'Nợ từ hóa đơn ' . $invoiceCode
                );
            }

            return $invoiceCode;
        });
    }
}

