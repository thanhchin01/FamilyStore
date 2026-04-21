<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Admin extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'role',
        'is_active',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    public function saleInvoices()
    {
        return $this->hasMany(SaleInvoice::class, 'created_by');
    }

    public function importReceipts()
    {
        return $this->hasMany(ImportReceipt::class, 'created_by');
    }

    public function inventoryMovements()
    {
        return $this->hasMany(InventoryMovement::class, 'created_by');
    }
}
