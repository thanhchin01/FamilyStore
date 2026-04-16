<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderPayment extends Model
{
    protected $fillable = [
        'order_id',
        'method',
        'provider',
        'transaction_code',
        'amount',
        'status',
        'paid_at',
        'raw_payload',
    ];

    protected $casts = [
        'paid_at' => 'datetime',
        'raw_payload' => 'array',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
