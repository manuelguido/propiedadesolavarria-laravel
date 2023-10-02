<?php

namespace App\Policies;

use App\Models\Permission;
use App\Models\Staff;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class StaffPolicy
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
        return $user->hasPermission(Permission::StaffIndex);
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Staff  $staff
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, Staff $staff)
    {
        return $user->hasPermission(Permission::StaffShow);
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        return $user->hasPermission(Permission::StaffStore);
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Staff  $staff
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, Staff $staff)
    {
        return $user->hasPermission(Permission::StaffUpdate);
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Staff  $staff
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Staff $staff)
    {
        return $user->hasPermission(Permission::StaffDelete);
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Staff  $staff
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, Staff $staff)
    {
        return $user->hasPermission(Permission::StaffRestore);
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Staff  $staff
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, Staff $staff)
    {
        return $user->hasPermission(Permission::StaffForceDelete);
    }
}
