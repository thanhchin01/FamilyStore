<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'role',
        'phone',
        'address',
        'status',
        'last_login_at',
    ];

    public function customerProfile()
    {
        return $this->hasOne(Customers::class, 'user_id');
    }

    public function carts()
    {
        return $this->hasMany(Cart::class);
    }

    public function cartItems()
    {
        return $this->hasMany(CartItem::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function conversations()
    {
        return $this->hasMany(Conversation::class);
    }

    public function sentMessages()
    {
        return $this->hasMany(Message::class, 'sender_id');
    }

    public function storeNotifications()
    {
        return $this->hasMany(StoreNotification::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
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

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'last_login_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
