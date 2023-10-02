<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class SurfaceMeasurementType extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * Attributes
     */
    protected $table = 'surface_measurement_type';

    protected $primaryKey = 'surface_measurement_type_id';

    public $timestamps = true;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'short_name',
    ];

    /**
     * Create a new Surface Measurement Type.
     *
     * @return \App\Models\SurfaceMeasurementType
     */
    public static function createSurfaceMeasurementType($surfaceMeasurementTypeData): SurfaceMeasurementType
    {
        $rentalType = SurfaceMeasurementType::create($surfaceMeasurementTypeData);
        $rentalType->save();
        return $rentalType;
    }

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
