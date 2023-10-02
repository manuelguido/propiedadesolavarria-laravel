<?php

namespace App\Policies;

use App\Models\FavouriteCollection;
use App\Models\Permission;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class FavouriteCollectionPolicy
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
     * @param  \App\Models\FavouriteCollection  $favouriteCollection
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, FavouriteCollection $favouriteCollection)
    {
        return $user->isEmployee()
            || (
                $user->hasPermission(Permission::FavouriteCollectionShow)
                && $user->client->ownsFavouriteCollection($favouriteCollection));
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        return $user->hasPermission(Permission::FavouriteCollectionStore);
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\FavouriteCollection  $favouriteCollection
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, FavouriteCollection $favouriteCollection)
    {
        return $user->hasPermission(Permission::FavouriteCollectionUpdate)
            && $user->client->ownsFavouriteCollection($favouriteCollection);
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\FavouriteCollection  $favouriteCollection
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, FavouriteCollection $favouriteCollection)
    {
        return $user->hasPermission(Permission::FavouriteCollectionForceDelete)
            && $user->client->ownsFavouriteCollection($favouriteCollection);
    }
}
