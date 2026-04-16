<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customers extends Model
{
    //
    protected $fillable = [
        'user_id',
        'customer_code',
        'name',
        'phone',
        'email',
        'address',
        'default_address',
        'relative_name',
        'note',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // 1 khách có thể mua nhiều lần
    public function sales()
    {
        return $this->hasMany(Sales::class);
    }

    public function debt()
    {
        // Khóa ngoại trong bảng customer__debts là customer_id
        return $this->hasOne(Customer_Debts::class, 'customer_id');
    }

    public function debtTransactions()
    {
        return $this->hasMany(Debt_Transactions::class);
    }

    public function addresses()
    {
        return $this->hasMany(CustomerAddress::class, 'customer_id');
    }

    public function orders()
    {
        return $this->hasMany(Order::class, 'customer_id');
    }

    public function saleInvoices()
    {
        return $this->hasMany(SaleInvoice::class, 'customer_id');
    }

    public function debtBalance()
    {
        return $this->hasOne(CustomerDebtBalance::class, 'customer_id');
    }

    public function debtTransactionsV2()
    {
        return $this->hasMany(DebtTransaction::class, 'customer_id');
    }
}
