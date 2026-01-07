<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Subcategory;
use Illuminate\Auth\Access\HandlesAuthorization;

class SubcategoryPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return true;
    }

    public function view(User $user, Subcategory $subcategory)
    {
        return $user->is_admin || $user->branch_id === $subcategory->branch_id;
    }

    public function create(User $user)
    {
        return $user->is_admin;
    }

    public function update(User $user, Subcategory $subcategory)
    {
        return $user->is_admin || $user->branch_id === $subcategory->branch_id;
    }

    public function delete(User $user, Subcategory $subcategory)
    {
        return $user->is_admin || $user->branch_id === $subcategory->branch_id;
    }
}
