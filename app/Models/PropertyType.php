<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class PropertyType extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * Attributes
     */
    protected $table = 'property_type';

    protected $primaryKey = 'property_type_id';

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
     * Create a new Property Type.
     *
     * @return \App\Models\PropertyType
     */
    public static function createPropertyType($propertyTypeData): PropertyType
    {
        $propertyType = PropertyType::create($propertyTypeData);
        $propertyType->save();
        return $propertyType;
    }

    /**
     * The Properties that correspond to this PropertyType.
     *
     * @return HasMany
     */
    public function property(): HasMany
    {
        return $this->HasMany(Property::class);
    }
}
