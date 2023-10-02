<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Neighborhood extends Model
{
    use HasFactory;

    /**
     * Attributes
     */
    protected $table = 'neighborhood';

    protected $primaryKey = 'neighborhood_id';

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
     * The Properties that correspond to this SurfaceMeasurementType.
     *
     * @return HasMany
     */
    public function property(): HasMany
    {
        return $this->HasMany(Property::class);
    }
}
