<?php

namespace App\Policies;

use App\Models\AntiquityType;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class AntiquityTypePolicy
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
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user)
    {
        return $user->hasPermission(Permission::AntiquityTypeShow);
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        return $user->hasPermission(Permission::AntiquityTypeStore);
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\AntiquityType  $antiquityType
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, AntiquityType $antiquityType)
    {
        return $user->hasPermission(Permission::AntiquityTypeUpdate);
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\AntiquityType  $antiquityType
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, AntiquityType $antiquityType)
    {
        return $user->hasPermission(Permission::AntiquityTypeDelete);
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\AntiquityType  $antiquityType
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, AntiquityType $antiquityType)
    {
        return $user->hasPermission(Permission::AntiquityTypeRestore);
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\AntiquityType  $antiquityType
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, AntiquityType $antiquityType)
    {
        return $user->hasPermission(Permission::AntiquityTypeForceDelete);
    }
}
