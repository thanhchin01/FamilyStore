<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ImportReceipt extends Model
{
    protected $fillable = [
        'receipt_code',
        'created_by',
        'supplier_name',
        'note',
        'received_at',
    ];

    protected $casts = [
        'received_at' => 'datetime',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function items()
    {
        return $this->hasMany(ImportItem::class);
    }
}
