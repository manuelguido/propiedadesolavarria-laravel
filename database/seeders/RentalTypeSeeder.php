<?php

namespace Database\Seeders;

use App\Models\RentalType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RentalTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $rentalTypes = [
            'Alquiler',
            'Venta',
        ];


        foreach ($rentalTypes as $rentalType => $value) {
            RentalType::create(['name' => $value]);
        }
    }
}
