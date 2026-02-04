<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer_Debts extends Model
{
    //
    protected $fillable = [
        'customer_id',
        'total_debt',
        'last_updated_at',
    ];

    public function customer()
    {
        return $this->belongsTo(Customers::class);
    }
}
