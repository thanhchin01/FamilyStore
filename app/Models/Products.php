<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Products extends Model
{
    //
    protected $fillable = [
        'category_id',
        'name',
        'brand',
        'price',
        'warranty_months',
        'stock'
    ];

    // Sản phẩm thuộc 1 loại
    public function category()
    {
        return $this->belongsTo(Categories::class);
    }

    // Sản phẩm có nhiều lần nhập hàng
    public function imports()
    {
        return $this->hasMany(Imports::class);
    }

     // Sản phẩm có nhiều lần bán
    public function sales()
    {
        return $this->hasMany(Sales::class);
    }
}

