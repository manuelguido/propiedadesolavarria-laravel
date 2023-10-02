<?php

namespace Database\Seeders;

use App\Models\PropertyType;
use Illuminate\Database\Seeder;

class PropertyTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $propertyTypes = ['Casa', 'Departamento', 'Quinta', 'PH', 'Oficina Comercial', 'Local Comercial'];

        foreach ($propertyTypes as $propertyType) {
            PropertyType::createPropertyType([
                'name' => $propertyType,
            ]);
        }
    }
}
