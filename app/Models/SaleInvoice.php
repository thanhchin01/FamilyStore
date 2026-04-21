<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SaleInvoice extends Model
{
    protected $fillable = [
        'invoice_code',
        'customer_id',
        'created_by',
        'channel',
        'status',
        'subtotal_amount',
        'discount_amount',
        'grand_total',
        'paid_amount',
        'debt_amount',
        'note',
        'sold_at',
    ];

    protected $casts = [
        'sold_at' => 'datetime',
    ];

    public function customer()
    {
        return $this->belongsTo(Customers::class, 'customer_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function items()
    {
        return $this->hasMany(SaleItem::class);
    }

    public function payments()
    {
        return $this->hasMany(SalePayment::class);
    }

    public function debtTransactions()
    {
        return $this->hasMany(DebtTransaction::class);
    }

    public function debtBalance()
    {
        return $this->hasOne(CustomerDebtBalance::class, 'customer_id', 'customer_id');
    }
}

