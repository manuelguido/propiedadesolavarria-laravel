<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class FavouriteCollection extends Model
{
    use HasFactory;

    /**
     * Attributes
     */
    protected $table = 'favourite_collection';

    protected $primaryKey = 'favourite_collection_id';

    public $timestamps = true;

    /**
     * Get the Client that owns the Favourite Collection.
     */
    public function client()
    {
        return $this->belongsTo('App\Client');
    }

    /**
     * Get the Post that owns the Favourite Collection.
     *
     * @return \App\Models\Post
     */
    public function post(): BelongsTo
    {
        return $this->belongsTo('App\Post');
    }

    /**
     * The FavouriteCollections that belong to the FavouritePost.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function favourite_collections(): BelongsToMany
    {
        return $this->belongsToMany(
            FavouriteCollection::class,
            'fav_collection_fav_post',
            'favourite_collection_id',
            'favourite_post_id',
        );
    }
}
