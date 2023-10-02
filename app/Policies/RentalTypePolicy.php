<?php

namespace App\Policies;

use App\Models\Permission;
use App\Models\RentalType;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class RentalTypePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\RentalType  $rentalType
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, RentalType $rentalType)
    {
        return true;
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        return $user->hasPermission(Permission::RentalTypeStore);
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\RentalType  $rentalType
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, RentalType $rentalType)
    {
        return $user->hasPermission(Permission::RentalTypeUpdate);
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\RentalType  $rentalType
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, RentalType $rentalType)
    {
        return $user->hasPermission(Permission::RentalTypeForceDelete);
    }
}
