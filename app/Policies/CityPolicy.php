<?php

namespace App\Policies;

use Illuminate\Auth\Access\Response;
use App\Models\City;
use App\Models\User;

class CityPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->checkPermissionTo('view-any City');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, City $city): bool
    {
        return $user->checkPermissionTo('view City');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->checkPermissionTo('create City');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, City $city): bool
    {
        return $user->checkPermissionTo('update City');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, City $city): bool
    {
        return $user->checkPermissionTo('delete City');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, City $city): bool
    {
        return $user->checkPermissionTo('{{ restorePermission }}');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, City $city): bool
    {
        return $user->checkPermissionTo('{{ forceDeletePermission }}');
    }
}
