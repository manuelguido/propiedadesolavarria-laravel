<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class PropertyImage extends Model
{
    use HasFactory;

    /**
     * Attributes
     */
    protected $table = 'property_image';

    protected $primaryKey = 'property_image_id';

    public $timestamps = true;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'property_id',
        'name',
        'order',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    private static function imagePath($property_id)
    {
        return 'public/images/properties/' . $property_id . '/';
    }

    public static function storePropertyImage($image, $property_id)
    {
        // Generar un nombre de archivo único
        $filename = Str::random(32) . '.' . $image['file']->getClientOriginalExtension();

        // Almacenar imagen
        // $propertyData['images']
        $image['file']->storeAs(PropertyImage::imagePath($property_id), $filename);

        // Storage::disk('local')->put(PropertyImage::imagePath($property_id) . $filename, 'Contents');

        // Almacenar modelo en DB
        $propertyImage = new PropertyImage;
        $propertyImage->property_id = $property_id;
        $propertyImage->order = $image['order'];
        $propertyImage->name = $filename;
        $propertyImage->save();

        return $propertyImage;
    }

    /**
     * The Property that corresponds to this Image.
     *
     * @return BelongsTo
     */
    public function property(): BelongsTo
    {
        return $this->belongsTo(Property::class);
    }
}
