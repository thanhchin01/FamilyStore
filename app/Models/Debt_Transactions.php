<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Debt_Transactions extends Model
{
    //
    protected $fillable = [
        'customer_id',
        'sale_id',
        'type',
        'amount',
        'description',
    ];

    public function customer()
    {
        return $this->belongsTo(Customers::class);
    }

    public function sale()
    {
        return $this->belongsTo(Sales::class);
    }
}
