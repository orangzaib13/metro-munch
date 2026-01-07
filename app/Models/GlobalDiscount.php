<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GlobalDiscount extends Model
{
    protected $fillable = [
        'discount_percentage',
        'is_active',
        'updated_by',
        'last_updated_at',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'last_updated_at' => 'datetime',
    ];

    // Check if global discount is active
    public function isActive(): bool
    {
        return $this->is_active;
    }
}
