<?php

namespace App\Policies;

use App\Models\Administrator;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class AdministratorPolicy
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
        return $user->hasPermission(Permission::AdministratorIndex);
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Administrator  $administrator
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, Administrator $administrator)
    {
        return $user->hasPermission(Permission::AdministratorShow);
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        return $user->hasPermission(Permission::AdministratorStore);
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Administrator  $administrator
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, Administrator $administrator)
    {
        return $user->hasPermission(Permission::AdministratorUpdate);
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Administrator  $administrator
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Administrator $administrator)
    {
        return $user->hasPermission(Permission::AdministratorDelete);
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Administrator  $administrator
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, Administrator $administrator)
    {
        return $user->hasPermission(Permission::AdministratorRestore);
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Administrator  $administrator
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, Administrator $administrator)
    {
        return $user->hasPermission(Permission::AdministratorForceDelete);
    }
}
