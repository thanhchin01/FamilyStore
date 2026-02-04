<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customers extends Model
{
    //
    protected $fillable = [
        'name',
        'phone',
        'address',
        'relative_name',
        'note',
    ];

    // 1 khách có thể mua nhiều lần
    public function sales()
    {
        return $this->hasMany(Sales::class);
    }

    public function debt()
    {
        return $this->hasOne(Customer_Debts::class);
    }

    public function debtTransactions()
    {
        return $this->hasMany(Debt_Transactions::class);
    }
}
