<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Customer extends Model
{
    protected $fillable = [
        'name',
        'phone',
        'alt_phone',
        'email',
        'area',
        'total_orders',
        'total_spent',
        'last_order_at',
    ];

    protected $casts = [
        'total_spent' => 'decimal:2',
        'last_order_at' => 'datetime',
    ];

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }
}
