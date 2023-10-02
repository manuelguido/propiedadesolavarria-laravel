<?php

namespace Database\Seeders;

use App\Models\Renter;
use App\Models\Property;
use Illuminate\Database\Seeder;

class PropertySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $renters = Renter::all();

        foreach ($renters as $renterData) {
            for ($i = 0; $i < 9; $i++) {
                Property::factory()->createPropertyForRenter($renterData->renter_id, 6);
            }
        }
    }
}
