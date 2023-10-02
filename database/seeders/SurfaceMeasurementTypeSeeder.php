<?php

namespace Database\Seeders;

use App\Models\SurfaceMeasurementType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SurfaceMeasurementTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $rentalTypes = [
            [
                'name' => 'Metros Cuadrados',
                'short_name' => 'm²',
            ],
            [
                'name' => 'Hectáreas',
                'short_name' => 'ha',
            ],
        ];


        foreach ($rentalTypes as $rentalType => $value) {
            SurfaceMeasurementType::create([
                'name' => $value['name'],
                'short_name' => $value['short_name'],
            ]);
        }
    }
}
