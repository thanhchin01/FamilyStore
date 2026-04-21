<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Products extends Model
{
    //
    protected $fillable = [
        'category_id',
        'name',
        'slug',
        'sku',
        'image',
        'thumbnail_image',
        'brand',
        'model',
        'price',
        'list_price',
        'sale_price',
        'warranty_months',
        'stock',
        'is_active',
        'short_description',
        'is_featured',
        'description'
    ];

    public function getRouteKeyName()
    {
        return 'slug';
    }

    // Sản phẩm thuộc 1 loại
    public function category()
    {
        return $this->belongsTo(Categories::class, 'category_id');
    }

    public function cartItems()

    {
        return $this->hasMany(CartItem::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function productImages()
    {
        return $this->hasMany(ProductImage::class, 'product_id');
    }

    public function reviews()
    {
        return $this->hasMany(Review::class, 'product_id');
    }

    public function saleItems()
    {
        return $this->hasMany(SaleItem::class, 'product_id');
    }

    public function importItems()
    {
        return $this->hasMany(ImportItem::class, 'product_id');
    }

    public function inventoryMovements()
    {
        return $this->hasMany(InventoryMovement::class, 'product_id');
    }
}
