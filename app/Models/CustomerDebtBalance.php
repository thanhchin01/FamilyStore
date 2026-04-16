<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerDebtBalance extends Model
{
    protected $fillable = ['customer_id', 'balance_amount', 'last_transaction_at'];

    protected $casts = [
        'last_transaction_at' => 'datetime',
    ];

    public function customer()
    {
        return $this->belongsTo(Customers::class, 'customer_id');
    }
}
