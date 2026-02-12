<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sales extends Model
{
    //
     protected $fillable = [
        'product_id',
        'customer_id',
        'quantity',
        'price',
        'sold_at',
        'payment_status',
        'paid_amount'
    ];

    protected $casts = [
        'sold_at' => 'date'
    ];

     // Lần bán thuộc 1 sản phẩm
    public function product()
    {
        return $this->belongsTo(Products::class);
    }

     // Lần bán thuộc 1 khách (có thể null)
    public function customer()
    {
        return $this->belongsTo(Customers::class);
    }

    public function debtTransactions()
    {
        return $this->hasMany(Debt_Transactions::class);
    }
}
