<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Renter extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * Attributes
     */
    protected $table = 'renter';

    protected $primaryKey = 'renter_id';

    public $timestamps = true;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'renter_id',
        'user_id',
        'address',
        'commercial_email',
        'estate_agent',
        'image',
        'phone',
        'whatsapp_phone',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'deleted_at',
        'created_at',
        'updated_at',
    ];

    const ImagePath = 'public/images/renters/';

    /**
     * Get the Renter with the User.
     *
     * @return \App\Models\User
     */
    public static function findWithUser($renter_id): User
    {
        return User::where('renter_id', '=', $renter_id)
            ->join('renter', 'user.user_id', '=', 'renter.user_id')
            ->first();
    }

    /**
     * Create a new Renter.
     *
     * @return \App\Models\Renter
     */
    public static function createRenter($userData): Renter
    {
        // Create user
        $user = User::createUser($userData);

        // Setup data
        $renterData = $userData;
        $renterData['user_id'] = $user->user_id;

        // Almacenar imagen
        if (isset($renterData['image'])) {
            $renterData['image'] = Str::random(32) . '.' . $userData['image']->getClientOriginalExtension();
            $userData['image']->storeAs(Renter::ImagePath, $renterData['image']);
        }

        // Create renter
        $renter = Renter::create($renterData);

        // Assign role
        Role::assignRoleToUser($renter->user_id, Role::RoleRenter);
        return $renter;
    }

    /**
     * Get all renters with their user data.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function allWithUser(): Collection
    {
        return Renter::select('renter.*', 'user.name', 'user.email', 'user.profile_image')
            ->join('user', 'user.user_id', '=', 'renter.user_id')
            ->get();
    }

    /**
     * The User that corresponds to the Renter.
     *
     * @return HasOne
     */
    public function user(): HasOne
    {
        return $this->hasOne(User::class, 'user_id', 'user_id');
    }

    /**
     * The properties that belongs to this Renter.
     *
     * @return HasMany
     */
    public function properties(): HasMany
    {
        return $this->hasMany(Property::class, 'renter_id');
    }

    /**
     * The posts that belongs to this Renter.
     *
     * @return HasMany
     */
    public function posts(): HasMany
    {
        return $this->hasMany(Post::class, 'renter_id');
    }

    /**
     * The posts that belongs to this Renter with all information from FK ids on tables.
     */
    public function postsFormatted()
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
            )
            ->where([
                ['post.renter_id', '=', $this->renter_id],
                ['property_image.order', '=', 1],
            ])
            ->get()
            ->map(function ($post) {
                return [
                    'price' => [
                        'value' => $post['value'],
                        'currency' => $post['value_currency'],
                    ],
                    'expenses' => [
                        'value' => $post['expenses'],
                        'currency' => $post['expenses_currency'],
                    ],
                    'post_id' => $post['post_id'],
                    'title' => $post['title'],
                    'property_type' => $post['property_type'],
                    'rental_type' => $post['rental_type'],
                    'neighborhood' => $post['neighborhood'],
                    'image' => $post['image'],
                ];
            });

        return $posts;
    }

    /**
     * Return current model with its correspondig user.
     *
     * @return HasOne
     */
    public function withUser()
    {
        return $this->join('user', 'user.user_id', '=', 'renter.user_id')->first();
    }

    /**
     * User routes.
     */
    public static function getWebRoutes()
    {
        return [
            ['icon' => 'icon-Home', 'name' => 'Inicio', 'url' => '/dashboard/renter/home'],
            ['icon' => 'icon-User-information', 'name' => 'Mis propiedades', 'url' => '/dashboard/renter/property/index'],
            ['icon' => 'icon-User', 'name' => 'Mis publicaciones', 'url' => '/dashboard/renter/post/index'],
        ];
    }
}
