<?php

namespace App\Policies;

use App\Models\Permission;
use App\Models\PropertyType;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class PropertyTypePolicy
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
     * @param  \App\Models\PropertyType  $propertyType
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, PropertyType $propertyType)
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
        return $user->hasPermission(Permission::PropertyTypeStore);
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\PropertyType  $propertyType
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, PropertyType $propertyType)
    {
        return $user->hasPermission(Permission::PropertyTypeUpdate);
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\PropertyType  $propertyType
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, PropertyType $propertyType)
    {
        return $user->hasPermission(Permission::PropertyTypeForceDelete);
    }
}
