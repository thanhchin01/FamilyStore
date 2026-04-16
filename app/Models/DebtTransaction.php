<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DebtTransaction extends Model
{
    protected $fillable = [
        'customer_id',
        'sale_invoice_id',
        'type',
        'amount',
        'description',
        'occurred_at',
        'created_by',
    ];

    protected $casts = [
        'occurred_at' => 'datetime',
    ];

    public function customer()
    {
        return $this->belongsTo(Customers::class, 'customer_id');
    }

    public function invoice()
    {
        return $this->belongsTo(SaleInvoice::class, 'sale_invoice_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
