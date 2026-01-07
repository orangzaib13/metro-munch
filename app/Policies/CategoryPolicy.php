<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Category;
use Illuminate\Auth\Access\HandlesAuthorization;

class CategoryPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any categories.
     */
    public function viewAny(User $user)
    {
        return true; // all authenticated users can view
    }

    /**
     * Determine whether the user can view a category.
     */
    public function view(User $user, Category $category)
    {
        return $user->is_admin || $user->branch_id === $category->branch_id;
    }

    /**
     * Determine whether the user can create categories.
     */
    public function create(User $user)
    {
        return $user->is_admin;
    }

    /**
     * Determine whether the user can update the category.
     */
    public function update(User $user, Category $category)
    {
        return $user->is_admin || $user->branch_id === $category->branch_id;
    }

    /**
     * Determine whether the user can delete the category.
     */
    public function delete(User $user, Category $category)
    {
        return $user->is_admin || $user->branch_id === $category->branch_id;
    }
}
