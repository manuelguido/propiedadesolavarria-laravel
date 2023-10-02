<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * Attributes
     */
    protected $table = 'post';

    protected $primaryKey = 'post_id';

    public $timestamps = true;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'value',
        'featured',
        'expenses',
        'renter_id',
        'property_id',
        'rental_type_id',
        'value_currency_id',
        'expenses_currency_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'renter_id',
        'deleted_at',
        'created_at',
        'updated_at',
    ];

    /**
     * The Renter that corresponds to this Post.
     *
     * @return BelongsTo
     */
    public function renter(): BelongsTo
    {
        return $this->belongsTo(Renter::class, 'renter_id');
    }

    /**
     * The Property that corresponds to the Post.
     *
     * @return BelongsTo
     */
    public function property(): BelongsTo
    {
        return $this->belongsTo(Property::class, 'property_id');
    }

    /**
     * The RentalType that corresponds to the Post.
     *
     * @return BelongsTo
     */
    public function rental_type(): BelongsTo
    {
        return $this->belongsTo(RentalType::class);
    }

    /**
     * The Currency that corresponds to this Post.
     *
     * @return BelongsTo
     */
    public function currency(): BelongsTo
    {
        return $this->belongsTo(Currency::class);
    }

    /**
     * The Currency that corresponds to this Post.
     */
    public function relatedPosts($limit = 4)
    {
        $posts = Post::join('property', 'property.property_id', '=', 'post.property_id')
            ->join('property_image', 'property_image.property_id', '=', 'post.property_id')
            ->join('rental_type', 'rental_type.rental_type_id', '=', 'post.rental_type_id')
            ->join('currency as v_currency', 'v_currency.currency_id', '=', 'post.value_currency_id')
            ->join('currency as e_currency', 'e_currency.currency_id', '=', 'post.expenses_currency_id')
            ->join('property_type', 'property_type.property_type_id', '=', 'property.property_type_id')
            ->join('neighborhood', 'neighborhood.neighborhood_id', '=', 'property.neighborhood_id')
            ->select(
                'e_currency.short_name as expenses_currency',
                'neighborhood.name as neighborhood',
                'post.post_id',
                'post.title',
                'post.value',
                'post.expenses',
                'property_type.name as property_type',
                'rental_type.name as rental_type',
                'v_currency.short_name as value_currency',
                \DB::raw("CONCAT('storage/images/properties/', post.property_id, '/', property_image.name) AS image"),
            )->where([
                ['post.post_id', '<>', $this->post_id],
                ['property_image.order', '=', 1],
            ])
            ->limit($limit)
            ->get();

        $posts->each(function ($post) {
            $post['price'] = ['value' => $post['value'], 'currency' => $post['value_currency']];
            $post['expenses'] = ['value' => $post['expenses'], 'currency' => $post['expenses_currency']];
            unset(
                $post['value'], $post['value_currency'], $post['expenses_currency'], $post['property_id']
                );

        });

        return $posts;
    }

    /**
     * The Currency that corresponds to this Post.
     */
    public function relatedRenterPosts($limit = 4)
    {
        $posts = Post::join('property', 'property.property_id', '=', 'post.property_id')
            ->join('property_image', 'property_image.property_id', '=', 'post.property_id')
            ->join('rental_type', 'rental_type.rental_type_id', '=', 'post.rental_type_id')
            ->join('currency as v_currency', 'v_currency.currency_id', '=', 'post.value_currency_id')
            ->join('currency as e_currency', 'e_currency.currency_id', '=', 'post.expenses_currency_id')
            ->join('property_type', 'property_type.property_type_id', '=', 'property.property_type_id')
            ->join('neighborhood', 'neighborhood.neighborhood_id', '=', 'property.neighborhood_id')
            ->select(
                'e_currency.short_name as expenses_currency',
                'neighborhood.name as neighborhood',
                'post.post_id',
                'post.title',
                'post.value',
                'post.expenses',
                'property_type.name as property_type',
                'rental_type.name as rental_type',
                'v_currency.short_name as value_currency',
                \DB::raw("CONCAT('storage/images/properties/', post.property_id, '/', property_image.name) AS image"),
            )->where([
                ['post.post_id', '<>', $this->post_id],
                ['property_image.order', '=', 1],
            ])
            ->limit($limit)
            ->get();

        $posts->each(function ($post) {
            $post['price'] = ['value' => $post['value'], 'currency' => $post['value_currency']];
            $post['expenses'] = ['value' => $post['expenses'], 'currency' => $post['expenses_currency']];
            unset(
                $post['value'], $post['value_currency'], $post['expenses_currency'], $post['property_id']
                );

        });

        return $posts;
    }
}
