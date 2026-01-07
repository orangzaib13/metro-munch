<?php

namespace App\Policies;

use App\Models\User;
use App\Models\FoodItem;
use Illuminate\Auth\Access\HandlesAuthorization;

class FoodItemPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return true;
    }

    public function view(User $user, FoodItem $foodItem)
    {
        return $user->is_admin || $user->branch_id === $foodItem->branch_id;
    }

    public function create(User $user)
    {
        return $user->is_admin;
    }

    public function update(User $user, FoodItem $foodItem)
    {
        return $user->is_admin || $user->branch_id === $foodItem->branch_id;
    }

    public function delete(User $user, FoodItem $foodItem)
    {
        return $user->is_admin || $user->branch_id === $foodItem->branch_id;
    }
}
