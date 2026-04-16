<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'order_code',
        'user_id',
        'customer_id',
        'status',
        'total_amount',
        'subtotal_amount',
        'shipping_fee',
        'discount_amount',
        'grand_total',
        'payment_method',
        'payment_status',
        'shipping_name',
        'shipping_phone',
        'shipping_address',
        'note',
        'placed_at',
    ];

    protected $casts = [
        'placed_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customers::class, 'customer_id');
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function address()
    {
        return $this->hasOne(OrderAddress::class);
    }

    public function payments()
    {
        return $this->hasMany(OrderPayment::class);
    }

    public function statusHistories()
    {
        return $this->hasMany(OrderStatusHistory::class);
    }
}
