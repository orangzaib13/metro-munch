<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FoodItemSideOrder extends Model
{
    protected $fillable = [
        'food_item_id',
        'name',
        'description',
        'price',
        'image',
        'category',
    ];

    protected $casts = [
        'price' => 'decimal:2',
    ];

    public function foodItem(): BelongsTo
    {
        return $this->belongsTo(FoodItem::class);
    }
}
