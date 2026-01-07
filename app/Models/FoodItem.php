<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FoodItem extends Model
{
    protected $fillable = [
        'branch_id',
        'category_id',
        'subcategory_id',
        'name',
        'description',
        'price',
        'image',
        'is_available',
        'allow_discount',
        'display_order',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'is_available' => 'boolean',
        'allow_discount' => 'boolean',
    ];

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function subcategory(): BelongsTo
    {
        return $this->belongsTo(Subcategory::class);
    }

    public function variations(): HasMany
    {
        return $this->hasMany(FoodItemVariation::class);
    }

    public function extras(): HasMany
    {
        return $this->hasMany(FoodItemExtra::class);
    }

    public function sideOrders(): HasMany
    {
        return $this->hasMany(FoodItemSideOrder::class);
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

}
