<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerAddress extends Model
{
    protected $fillable = [
        'customer_id',
        'recipient_name',
        'recipient_phone',
        'province_code',
        'district_code',
        'ward_code',
        'address_line',
        'is_default',
    ];

    public function customer()
    {
        return $this->belongsTo(Customers::class, 'customer_id');
    }
}
