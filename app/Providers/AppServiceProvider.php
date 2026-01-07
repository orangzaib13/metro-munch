<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use App\Models\Category;
use App\Models\Subcategory;
use App\Models\FoodItem;
use App\Policies\CategoryPolicy;
use App\Policies\SubcategoryPolicy;
use App\Policies\FoodItemPolicy;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     */
    protected $policies = [
        Category::class => CategoryPolicy::class,
        Subcategory::class => SubcategoryPolicy::class,
        FoodItem::class => FoodItemPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();
    }
}
