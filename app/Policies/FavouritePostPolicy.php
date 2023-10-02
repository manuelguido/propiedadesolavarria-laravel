<?php

namespace App\Policies;

use App\Models\FavouritePost;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class FavouritePostPolicy
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
     * @param  \App\Models\FavouritePost  $favouritePost
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, FavouritePost $favouritePost)
    {
        return $user->isEmployee()
            || (
                $user->hasPermission(Permission::FavouritePostShow)
                && $user->client->ownsFavouritePost($favouritePost));
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        return $user->hasPermission(Permission::FavouritePostStore);
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\FavouritePost  $favouritePost
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, FavouritePost $favouritePost)
    {
        return $user->hasPermission(Permission::FavouritePostForceDelete)
            && $user->client->ownsFavouritePost($favouritePost);
    }
}
