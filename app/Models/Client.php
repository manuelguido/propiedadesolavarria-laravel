<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Client extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * Attributes
     */
    protected $table = 'client';

    protected $primaryKey = 'client_id';

    public $timestamps = true;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'client_id',
        'user_id',
    ];

    /**
     * Create a new Client.
     *
     * @return \App\Models\Client
     */
    public static function createClient($userData): Client
    {
        $user = User::createUser($userData);
        $clientData = $userData;
        $clientData['user_id'] = $user->user_id;
        $client = Client::create($clientData);
        Role::assignRoleToUser($client->user_id, Role::RoleClient);
        return $client;
    }

    /**
     * Get the Client with the User.
     *
     * @return \App\Models\User
     */
    public static function findWithUser($client_id): User
    {
        return User::where('client_id', '=', $client_id)
            ->join('client', 'user.user_id', '=', 'client.user_id')
            ->first();
    }


    /**
     * Get all clients with their user data.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function allWithUser(): Collection
    {
        return Client::select('client.*', 'user.name', 'user.email', 'user.profile_image')
            ->join('user', 'user.user_id', '=', 'client.user_id')
            ->get();
    }

    /**
     * The user that corresponds to the client.
     */
    public function user(): HasOne
    {
        return $this->hasOne(User::class, 'user_id', 'user_id');
    }

    /**
     * Get the Favourite Posts for the client.
     */
    public function favourite_posts(): HasMany
    {
        return $this->hasMany(FavouritePost::class, 'client_id');
    }

    /**
     * Get the Favourite Collections for the client.
     */
    public function favourite_collections(): HasMany
    {
        return $this->hasMany('App\FavouriteCollection');
    }

    /**
     * Return current model with its correspondig user.
     *
     * @return HasOne
     */
    public function withUser()
    {
        return $this->join('user', 'user.user_id', '=', 'client.user_id')->first();
    }

    /**
     * Returns true if the client owns the favourite post.
     *
     * @return bool
     */
    public function ownsFavouritePost($favouritePost): bool
    {
        return ($favouritePost->client_id = $this->client_id);
        // return $this->loadMissing('favourite_posts')->pluck('favourite_posts')->flatten()->contains('favourite_post_id', $favouritePost->favourite_post_id);
    }

    /**
     * Returns true if the client owns the favourite collection.
     *
     * @return bool
     */
    public function ownsFavouriteCollection($favouriteCollection): bool
    {
        return $this->loadMissing('favourite_collections')->pluck('favourite_collections')->flatten()->contains('favourite_collections', $favouriteCollection->favourite_collection_id);
    }

    /**
     * User routes.
     */
    public static function getWebRoutes()
    {
        return [
            ['icon' => 'icon-Home', 'name' => 'Inicio', 'url' => '/dashboard/client/home'],
            ['icon' => 'icon-User', 'name' => 'Mis favoritos', 'url' => '/dashboard/client/users'],
        ];
    }
}
