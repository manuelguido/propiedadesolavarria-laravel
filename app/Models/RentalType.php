<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class RentalType extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * Attributes
     */
    protected $table = 'rental_type';

    protected $primaryKey = 'rental_type_id';

    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'deleted_at',
    ];

    /**
     * Create a new Administrator.
     *
     * @return \App\Models\RentalType
     */
    public static function createRentalType($rentalTypeData): RentalType
    {
        $rentalType = RentalType::create($rentalTypeData);
        $rentalType->save();
        return $rentalType;
    }

    /**
     * The Properties that correspond to this RentalType.
     *
     * @return HasMany
     */
    public function post(): HasMany
    {
        return $this->HasMany(Post::class);
    }
}
