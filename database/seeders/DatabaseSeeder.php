<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            PermissionSeeder::class,
            RoleSeeder::class,
            PermissionRoleSeeder::class,
            UserSeeder::class,
            AntiquityTypeSeeder::class,
            CurrencySeeder::class,
            RentalTypeSeeder::class,
            SurfaceMeasurementTypeSeeder::class,
            PropertyTypeSeeder::class,
            NeighborhoodSeeder::class,
            PropertySeeder::class,
            PostSeeder::class,
        ]);
    }
}
