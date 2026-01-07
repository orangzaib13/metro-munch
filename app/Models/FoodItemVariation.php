<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FoodItemVariation extends Model
{
    protected $fillable = [
        'food_item_id',
        'name',
        'price',
        'allow_discount',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'allow_discount' => 'boolean',
    ];

    public function foodItem(): BelongsTo
    {
        return $this->belongsTo(FoodItem::class);
    }
}
