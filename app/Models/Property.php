<?php
namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Property extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * Attributes
     */
    protected $table = 'property';

    protected $primaryKey = 'property_id';

    public $timestamps = true;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'renter_id',
        'enviroments',
        'bathrooms',
        'bedrooms',
        'garages',
        'total_surface',
        'covered_surface',
        'surface_measurement_type_id',
        'antiquity_type_id',
        'property_type_id',
        'neighborhood_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'deleted_at',
        'updated_at',
    ];

    private function imagePath()
    {
        return 'public/images/properties/' . $this->property_id . '/';
    }

    private function deletePropertyImage($image)
    {
        Storage::delete($this->imagePath() . $image->name);
        $image->delete();
    }

    private function deletePropertyImages()
    {
        foreach ($this->property_images as $image) {
            $this->deletePropertyImage($image);
        }
    }

    public static function createProperty($propertyData)
    {
        // Iniciar una transacción
        DB::beginTransaction();
        $newData = $propertyData;
        unset($newData['images']);
        try {
            // Store data
            $property = Property::create($newData);

            // Store images
            foreach ($propertyData['images'] as $image) {
                PropertyImage::storePropertyImage($image, $property->property_id);
            }

            // Commmit
            DB::commit();
            return $property;
        } catch (\Exception $e) {
            DB::rollback();

            Log::error('Error al almacenar imágenes de propiedad: ' . $e->getMessage());

            // return ApiResponse::error('Ha ocurrido un error al guardar las imágenes de la propiedad.', null, 500);
            return null;
        }
    }

    public function updateProperty($propertyData)
    {
        // Iniciar una transacción
        DB::beginTransaction();

        try {
            $newData = $propertyData;
            unset($newData['images']);

            $this->update($newData);

            $this->updatePropertyImages($propertyData['images']);

            DB::commit();
            $this->fresh();
            return $this;

        } catch (\Exception $e) {

            DB::rollback();
            Log::error('Error al almacenar imágenes de propiedad: ' . $e->getMessage());
            return null;
        }
    }

    public function deletePermanently()
    {
        // Iniciar una transacción
        DB::beginTransaction();

        try {

            $this->deletePropertyImages();
            DB::commit();
            return true;

        } catch (\Exception $e) {

            DB::rollback();
            Log::error('Error al almacenar imágenes de propiedad: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Delete a property permanently.
     */
    public function forceDelete()
    {
        // Iniciar una transacción
        DB::beginTransaction();

        try {
            $this->deletePropertyImages();
            DB::table('property')->where('property_id', '=', $this->property_id)->delete();
            DB::commit();
            return true;

        } catch (\Exception $e) {

            DB::rollback();
            Log::error('Error al almacenar imágenes de propiedad: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Update Property Images
     */
    private function updatePropertyImages($images)
    {
        $currentImages = $this->property_images;

        // // Eliminar las imágenes que ya no fueron enviadas por la API
        foreach ($this->property_images as $currentImage) {
            $imageFound = false;
            foreach ($images as $newImage) {
                if (isset($newImage['image_property_id']) && $newImage['image_property_id'] == $currentImage->image_property_id) {
                    $imageFound = true;
                    break;
                }
            }
            if (!$imageFound) {
                Storage::delete($this->imagePath() . $currentImage->name);
                $currentImage->delete();
            }
        }

        // Procesar las nuevas imágenes enviadas por la API
        foreach ($images as $newImage) {
            // Si la imagen ya existe, actualizar su orden si es necesario
            if (isset($newImage['image_property_id'])) {
                $currentImage = $currentImages->find($newImage['image_property_id']);
                if ($currentImage && $currentImage->order != $newImage['order']) {
                    $currentImage->order = $newImage['order'];
                    $currentImage->save();
                }
            } else { // Si la imagen es nueva, almacenarla en archivo y en su correspondiente modelo PropertyImage
                if ($newImage['file']->isValid() && in_array($newImage['file']->getMimeType(), ['image/png', 'image/jpeg']) && $newImage['order'] >= 1 && $newImage['order'] <= 10) {
                    $filename = Str::random(20) . '.' . $newImage['file']->getClientOriginalExtension();
                    $newImage['file']->storeAs($this->imagePath(), $filename);

                    $propertyImage = new PropertyImage;
                    $propertyImage->property_id = $this->property_id;
                    $propertyImage->order = $newImage['order'];
                    $propertyImage->name = $filename;
                    $propertyImage->save();
                }
            }
        }
    }

    /**
     * The Neighborhood that corresponds to this Property.
     */
    public function neighborhood(): BelongsTo
    {
        return $this->belongsTo(Neighborhood::class);
    }

    /**s
     * The Renter that corresponds to this Property.
     */
    public function renter(): BelongsTo
    {
        return $this->belongsTo(Renter::class);
    }

    /**
     * The PropertyImages for the Property.
     */
    public function property_images()
    {
        return $this->hasMany(PropertyImage::class, 'property_id');
    }

    /**
     * The Posts that correspond to this Property.
     */
    public function posts(): HasMany
    {
        return $this->hasMany(Post::class, 'property_id');
    }

    /**
     * The SurfaceMeasurementType that corresponds to this Property.
     */
    public function surface_measurement_type(): BelongsTo
    {
        return $this->belongsTo(SurfaceMeasurementType::class);
    }

    /**
     * The SurfaceMeasurementType that corresponds to this Property.
     */
    public function property_type(): BelongsTo
    {
        return $this->belongsTo(SurfaceMeasurementType::class);
    }

    /**
     * The SurfaceType that corresponds to this Property.
     */
    public function surface_type(): BelongsTo
    {
        return $this->belongsTo(SurfaceType::class);
    }

    /**
     * The AntiquityType that corresponds to this Property.
     */
    public function antiquity_type(): BelongsTo
    {
        return $this->belongsTo(AntiquityType::class);
    }
}
