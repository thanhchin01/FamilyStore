<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StoreNotification extends Model
{
    protected $table = 'notifications';

    protected $fillable = ['user_id', 'type', 'title', 'content', 'is_read'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
