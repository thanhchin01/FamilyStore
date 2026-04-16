<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderAddress extends Model
{
    protected $fillable = [
        'order_id',
        'recipient_name',
        'recipient_phone',
        'province_code',
        'district_code',
        'ward_code',
        'address_line',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
