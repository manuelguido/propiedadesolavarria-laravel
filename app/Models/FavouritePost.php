<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class FavouritePost extends Model
{
    use HasFactory;

    /**
     * Attributes
     */
    protected $table = 'favourite_post';

    protected $primaryKey = 'favourite_post_id';

    public $timestamps = true;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'client_id',
        'post_id',
        'collection_count',
    ];

    /**
     * Get the Client that owns the Favourite Post.
     *
     * @return  BelongsTo
     */
    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    /**
     * Get the Post that owns the Favourite Post.
     *
     * @return  BelongsTo
     */
    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }

    /**
     * The FavouriteCollections that belong to the FavouritePost.
     *
     * @return BelongsToMany
     */
    public function favourite_collections(): BelongsToMany
    {
        return $this->belongsToMany(
            FavouriteCollection::class,
            'fav_collection_fav_post',
            'favourite_post_id',
            'favourite_collection_id'
        );
    }
}
