<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SaleItem extends Model
{
    protected $fillable = [
        'sale_invoice_id',
        'product_id',
        'product_name_snapshot',
        'quantity',
        'unit_price',
        'line_total',
    ];

    public function invoice()
    {
        return $this->belongsTo(SaleInvoice::class, 'sale_invoice_id');
    }

    public function product()
    {
        return $this->belongsTo(Products::class, 'product_id');
    }
}
