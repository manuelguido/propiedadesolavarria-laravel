<?php

namespace Database\Factories;

use App\Models\Property;
use App\Models\PropertyType;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Property>
 */
class PropertyFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'name' => 'Nueva propiedad',
            'enviroments' => 1,
            'bathrooms' => 1,
            'bedrooms' => 1,
            'garages' => 1,
            'total_surface' => 1,
            'covered_surface' => 1,
            'surface_measurement_type_id' => 1,
            'property_type_id' => 1,
            'antiquity_type_id' => 1,
            'neighborhood_id' => 1,
        ];
    }

    public function generateRandomPropertyData($count)
    {
        return $this->generateRandomData($count);
    }

    public function generateRandomData($imageCount = 5)
    {
        // Obtener todos los IDs de la tabla 'property_type'
        $propertyTypeIds = PropertyType::pluck('property_type_id')->toArray();


        // Utilizar el ID seleccionado en los datos generados
        return [
            'name' => 'Nueva propiedad ' . Str::random(10),
            'enviroments' => rand(1, 10),
            'bathrooms' => rand(1, 3),
            'bedrooms' => rand(1, 7),
            'garages' => rand(1, 3),
            'total_surface' => rand(40, 180),
            'covered_surface' => rand(30, 160),
            'surface_measurement_type_id' => 1,
            'property_type_id' => $propertyTypeIds[array_rand($propertyTypeIds)],
            'antiquity_type_id' => 1,
            'neighborhood_id' => rand(1, 20),
            'images' => $this->generateImages($imageCount)
        ];
    }

    private function generateImages($count)
    {
        $images = [];
        $orders = range(1, $count);

        foreach ($orders as $order) {
            $imageName = 'image' . rand(1, 9) . '.png';
            $images[] = [
                'file' => new UploadedFile('storage/app/example-properties/' . $imageName, $imageName, 'image/png', null, true),
                'order' => $order,
            ];
        }
        return $images;
    }

    public function createPropertyForRenter($renter_id, $imageCount = 5): mixed
    {
        $newData = $this->generateRandomData($imageCount);
        $newData['renter_id'] = $renter_id;
        return Property::createProperty($newData);
    }
}
