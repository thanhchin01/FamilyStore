<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InventoryMovement extends Model
{
    protected $fillable = [
        'product_id',
        'movement_type',
        'quantity_delta',
        'reference_type',
        'reference_id',
        'note',
        'occurred_at',
        'created_by',
    ];

    protected $casts = [
        'occurred_at' => 'datetime',
    ];

    public function product()
    {
        return $this->belongsTo(Products::class, 'product_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
