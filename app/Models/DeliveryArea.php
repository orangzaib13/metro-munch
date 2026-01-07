<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DeliveryArea extends Model
{
    protected $fillable = [
        'branch_id',
        'name',
        'delivery_fee',
        'is_active',
    ];

    protected $casts = [
        'delivery_fee' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }
}
