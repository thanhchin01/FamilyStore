<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Imports extends Model
{
    //
    protected $fillable = [
        // ✅ Added: mã phiếu nhập (gom nhiều dòng)
        'receipt_code',
        'product_id',
        'quantity',
        'note',
        'imported_at'
    ];

    protected $casts = [
        'imported_at' => 'date'
    ];

    // Lần nhập hàng thuộc 1 sản phẩm
    public function product()
    {
        return $this->belongsTo(Products::class);
    }
}
