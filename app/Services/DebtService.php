<?php

namespace App\Services;

use App\Models\Customers;
use App\Models\Customer_Debts;
use App\Models\Debt_Transactions;

class DebtService
{
    /**
     * Tăng nợ cho khách
     */
    public function addDebt(
        Customers $customer,
        int $amount,
        int $saleId,
        ?string $description = null
    ) {
        // 1. Lấy hoặc tạo bảng tổng nợ
        $debt = Customer_Debts::firstOrCreate(
            ['customer_id' => $customer->id],
            [
                'total_debt' => 0,
                'last_updated_at' => now(),
            ]
        );

        // 2. Cộng tổng nợ
        $debt->increment('total_debt', $amount);
        $debt->update(['last_updated_at' => now()]);

        // 3. Ghi lịch sử nợ
        Debt_Transactions::create([
            'customer_id' => $customer->id,
            'sale_id' => $saleId,
            'type' => 'increase',
            'amount' => $amount,
            'description' => $description ?? 'Phát sinh nợ',
        ]);
    }

    /**
     * Khách trả nợ
     */
    public function payDebt(
        Customers $customer,
        int $amount,
        ?string $description = null
    ) {
        $debt = Customer_Debts::where('customer_id', $customer->id)->first();

        if (!$debt) {
            return;
        }

        // 1. Trừ tổng nợ
        $debt->decrement('total_debt', $amount);
        $debt->update(['last_updated_at' => now()]);

        // 2. Ghi lịch sử trả nợ
        Debt_Transactions::create([
            'customer_id' => $customer->id,
            'sale_id' => null,
            'type' => 'payment',
            'amount' => $amount,
            'description' => $description ?? 'Khách trả nợ',
        ]);
    }
}
