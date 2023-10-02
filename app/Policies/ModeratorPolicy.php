<?php

namespace App\Policies;

use App\Models\Moderator;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ModeratorPolicy
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
        return $user->hasPermission(Permission::ModeratorIndex);
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Moderator  $moderator
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, Moderator $moderator)
    {
        return $user->hasPermission(Permission::ModeratorShow);
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        return $user->hasPermission(Permission::ModeratorStore);
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Moderator  $moderator
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, Moderator $moderator)
    {
        return $user->hasPermission(Permission::ModeratorUpdate);
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Moderator  $moderator
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Moderator $moderator)
    {
        return $user->hasPermission(Permission::ModeratorDelete);
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Moderator  $moderator
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, Moderator $moderator)
    {
        return $user->hasPermission(Permission::ModeratorRestore);
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Moderator  $moderator
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, Moderator $moderator)
    {
        return $user->hasPermission(Permission::ModeratorForceDelete);
    }
}
