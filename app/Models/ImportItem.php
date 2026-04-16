<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ImportItem extends Model
{
    protected $fillable = [
        'import_receipt_id',
        'product_id',
        'quantity',
        'unit_cost',
        'line_cost',
        'note',
    ];

    public function receipt()
    {
        return $this->belongsTo(ImportReceipt::class, 'import_receipt_id');
    }

    public function product()
    {
        return $this->belongsTo(Products::class, 'product_id');
    }
}
