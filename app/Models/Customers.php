<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customers extends Model
{
    //
    protected $fillable = [
        'customer_code',
        'name',
        'phone',
        'email',
        'address',
        'default_address',
        'relative_name',
        'note',
    ];


    public function addresses()
    {
        return $this->hasMany(CustomerAddress::class, 'customer_id');
    }

    public function orders()
    {
        return $this->hasMany(Order::class, 'customer_id');
    }

    public function saleInvoices()
    {
        return $this->hasMany(SaleInvoice::class, 'customer_id');
    }

    public function debtBalance()
    {
        return $this->hasOne(CustomerDebtBalance::class, 'customer_id');
    }

    public function debtTransactions()
    {
        return $this->hasMany(DebtTransaction::class, 'customer_id');
    }

}
