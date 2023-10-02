<?php

namespace App\Policies;

use App\Models\Permission;
use App\Models\Renter;
use App\Models\Role;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class RenterPolicy
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
        return $user->hasPermission(Permission::RenterIndex);
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Renter  $renter
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, Renter $renter)
    {
        return $user->hasPermission(Permission::RenterShow);
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        return $user->hasPermission(Permission::RenterStore);
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Renter  $renter
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, Renter $renter)
    {
        return
            $user->hasPermission(Permission::RenterUpdate) ||
            ($user->hasRole(Role::RoleRenter) && $user->user_id = $renter->user_id);
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Renter  $renter
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Renter $renter)
    {
        return $user->hasPermission(Permission::RenterDelete);
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Renter  $renter
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, Renter $renter)
    {
        return $user->hasPermission(Permission::RenterRestore);
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Renter  $renter
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, Renter $renter)
    {
        return $user->hasPermission(Permission::RenterForceDelete);
    }
}
