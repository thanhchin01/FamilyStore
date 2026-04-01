<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customers extends Model
{
    //
    protected $fillable = [
        'user_id',
        'name',
        'phone',
        'address',
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
}
