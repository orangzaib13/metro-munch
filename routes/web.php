<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\PasswordResetLinkController;


Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('/check-out', function () {
    return view('check-out');
})->name('check-out');

Route::get('/order-tracker', function () {
    return view('order-tracker');
})->name('order-tracker');

// Password reset form
Route::get('/reset-password/{token}', [NewPasswordController::class, 'create'])
    ->name('password.reset');

Route::post('/reset-password', [NewPasswordController::class, 'store'])
    ->name('password.update');

// Authentication routes
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.submit');
Route::get('/logout', [LoginController::class, 'logout'])->name('logout');


Route::middleware(['auth', 'role:Admin'])->group(function () {

    // Dashboard
    Route::get('/dashboard', function () {
        return view('pages/dashboard/app');
    })->name('admin.dashboard');

    // Orders
    Route::get('/orders/history', function () {
        return view('pages/dashboard/order-history');
    })->name('order-history');

    // Branch Management
    Route::get('/branches', function () {
        return view('pages/dashboard/branches');
    })->name('branch-management');

    // Categories / Subcategories
    Route::get('/categories', function () {
        return view('pages/dashboard/categories');
    })->name('categories');

    Route::get('/sub-categories', function () {
        return view('pages/dashboard/subCategories');
    })->name('subcategories');

    // Food Items
    Route::get('/food-items/manage', function () {
        return view('fooditems.manage');
    })->name('food-item-management');

    Route::get('/customers', function () {
        return view('pages/dashboard/customers');
    })->name('customers');

    Route::get('/food-items', function () {
        return view('pages/dashboard/food-items');
    })->name('food-items');

    // Discount
    Route::get('/discount', function () {
        return view('pages/dashboard/discount');
    })->name('discount');

    // Delivery Areas
    Route::get('/delivery-areas', function () {
        return view('pages/dashboard/delivery-areas');
    })->name('delivery-areas');

    // Order Options
    Route::get('/order-options', function () {
        return view('pages/dashboard/order-options');
    })->name('order-options');

    // Analytics
    Route::get('/analytics', function () {
        return view('pages/dashboard/analytics');
    })->name('analytics');

    // Create User
    Route::get('/create-user', function () {
        return view('pages/dashboard/create-user');
    })->name('create-user');

    // System Settings
    Route::get('/system-settings', function () {
        return view('settings.system');
    })->name('system-settings');
});


// Branch routes for branch only
Route::middleware(['auth', 'role:Manager'])->group(function () {

    // Branch dashboard
    Route::get('/branch/dashboard', function () {
        return view('pages/branch/dashboard');
    })->name('branch.dashboard');

     Route::get('/branch/order-management', function () {
        return view('pages/branch/order-management');
    })->name('branch.order-management');

    Route::get('/branch/orders-history', function () {
        return view('pages/branch/order-history');
    })->name('branch.order-history');

    Route::get('/branch/customers', function () {
        return view('pages/branch/customers');
    })->name('branch.customers');

    Route::get('/branch/food-items', function () {
        return view('pages/branch/food-items');
    })->name('branch.food-items');

    Route::get('/branch/categories', function () {
        return view('pages/branch/categories');
    })->name('branch.categories');

    Route::get('/branch/sub-categories', function () {
        return view('pages/branch/sub-categories');
    })->name('branch.subcategories');

    Route::get('/branch/analytics', function () {
        return view('pages/branch/analytics');
    })->name('branch.analytics');

});

Route::middleware(['auth', 'role:Admin;Manager'])->group(function () {

    Route::get('/show-order/{orderId}', function ($orderId) {
        return view('pages/dashboard/show-order', ['orderId' => $orderId]);
    })->name('show-order');

});