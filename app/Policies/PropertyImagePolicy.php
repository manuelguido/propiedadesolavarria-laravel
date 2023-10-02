<?php

namespace App\Policies;

use App\Models\Permission;
use App\Models\PropertyImage;
use App\Models\Role;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class PropertyImagePolicy
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
        return $user->isEmployee();
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\PropertyImage  $propertyImage
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, PropertyImage $propertyImage)
    {
        return
            $user->isEmployee()
            || ((
                $user->hasRole(Role::RoleRenter)
                && $propertyImage->property->renter_id == $user->renter->renter_id));
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        return $user->hasPermission(Permission::PropertyStore);
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\PropertyImage  $propertyImage
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, PropertyImage $propertyImage)
    {
        return
            $user->hasPermission(Permission::PropertyUpdate)
            && ($propertyImage->property->renter_id == $user->renter->renter_id);
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\PropertyImage  $propertyImage
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, PropertyImage $propertyImage)
    {
        return
            $user->hasPermission(Permission::PropertyDelete)
            && ($propertyImage->property->renter_id == $user->renter->renter_id);
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\PropertyImage  $propertyImage
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, PropertyImage $propertyImage)
    {
        return
            $user->hasPermission(Permission::PropertyRestore)
            && ($propertyImage->property->renter_id == $user->renter->renter_id);
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\PropertyImage  $propertyImage
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, PropertyImage $propertyImage)
    {
        return
            $user->hasPermission(Permission::PropertyForceDelete)
            && ($propertyImage->property->renter_id == $user->renter->renter_id);
    }
}
