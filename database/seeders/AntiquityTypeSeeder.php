<?php

namespace Database\Seeders;

use App\Models\AntiquityType;
use Illuminate\Database\Seeder;

class AntiquityTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $antiquityTypes = [
            'A estrenar',
            'Renovado',
            '10 años de antiguedad',
            '20 años de antiguedad',
            '30 años de antiguedad',
        ];


        foreach ($antiquityTypes as $antiquityType) {
            AntiquityType::create(['name' => $antiquityType]);
        }
    }
}
