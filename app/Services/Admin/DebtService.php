<?php

namespace App\Services\Admin;

use App\Models\Customers;
use App\Models\CustomerDebtBalance;
use App\Models\DebtTransaction;
use App\Models\SaleInvoice;

class DebtService
{
    /**
     * Tăng nợ cho khách từ hóa đơn
     */
    public function addDebt(
        Customers $customer,
        int $amount,
        int $saleInvoiceId,
        ?string $description = null
    ) {
        // 1. Lấy hoặc tạo bảng số dư nợ
        $debt = CustomerDebtBalance::firstOrCreate(
            ['customer_id' => $customer->id],
            [
                'balance_amount' => 0,
                'last_transaction_at' => now(),
            ]
        );

        // 2. Cộng số dư nợ
        $debt->increment('balance_amount', $amount);
        $debt->update(['last_transaction_at' => now()]);

        // 3. Ghi lịch sử nợ
        DebtTransaction::create([
            'customer_id' => $customer->id,
            'sale_invoice_id' => $saleInvoiceId,
            'type' => 'increase',
            'amount' => $amount,
            'description' => $description ?? 'Phát sinh nợ từ hóa đơn',
            'occurred_at' => now(),
            'created_by' => auth('admin')->id(),
        ]);
    }

    /**
     * Khách trả nợ
     */
    public function payDebt(
        Customers $customer,
        int $amount,
        ?string $description = null,
        ?int $saleInvoiceId = null
    ) {
        $debt = CustomerDebtBalance::where('customer_id', $customer->id)->first();

        if (!$debt) {
            return;
        }

        // 1. Trừ số dư nợ
        $debt->decrement('balance_amount', $amount);
        $debt->update(['last_transaction_at' => now()]);

        // 2. Nếu có saleInvoiceId, cập nhật số tiền đã trả của hoá đơn đó
        if ($saleInvoiceId) {
            $invoice = SaleInvoice::find($saleInvoiceId);
            if ($invoice) {
                $invoice->increment('paid_amount', $amount);
                // Tính toán lại nợ trong invoice nếu cần
                $invoice->decrement('debt_amount', $amount);
            }
        }

        // 3. Ghi lịch sử trả nợ
        DebtTransaction::create([
            'customer_id' => $customer->id,
            'sale_invoice_id' => $saleInvoiceId,
            'type' => 'payment',
            'amount' => $amount,
            'description' => $description ?? 'Khách trả nợ',
            'occurred_at' => now(),
            'created_by' => auth('admin')->id(),
        ]);
    }
}


