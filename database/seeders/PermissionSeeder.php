<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permissions = [
                /*
                | Administrator Permissions
                */
            Permission::AdministratorIndex,
            Permission::AdministratorShow,
            Permission::AdministratorStore,
            Permission::AdministratorUpdate,
            Permission::AdministratorDelete,
            Permission::AdministratorRestore,
            Permission::AdministratorForceDelete,

                /*
                | Anqituity Type Permissions
                */
            Permission::AntiquityTypeShow,
            Permission::AntiquityTypeStore,
            Permission::AntiquityTypeUpdate,
            Permission::AntiquityTypeDelete,
            Permission::AntiquityTypeRestore,
            Permission::AntiquityTypeForceDelete,

                /*
                | Client Permissions
                */
            Permission::ClientIndex,
            Permission::ClientShow,
            Permission::ClientDelete,
            Permission::ClientRestore,
            Permission::ClientForceDelete,

                /*
                | Favourite Collection Permissions
                */
            Permission::FavouriteCollectionIndex,
            Permission::FavouriteCollectionShow,
            Permission::FavouriteCollectionStore,
            Permission::FavouriteCollectionUpdate,
            Permission::FavouriteCollectionForceDelete,

                /*
                | Favourite Post Permissions
                */
            Permission::FavouritePostIndex,
            Permission::FavouritePostShow,
            Permission::FavouritePostStore,
            Permission::FavouritePostForceDelete,

                /*
                | Moderator Permissions
                */
            Permission::ModeratorIndex,
            Permission::ModeratorShow,
            Permission::ModeratorStore,
            Permission::ModeratorUpdate,
            Permission::ModeratorDelete,
            Permission::ModeratorRestore,
            Permission::ModeratorForceDelete,

                /*
                | Post Permissions
                */
            Permission::PostIndex,
            Permission::PostShow,
            Permission::PostStore,
            Permission::PostUpdate,
            Permission::PostDelete,
            Permission::PostRestore,
            Permission::PostForceDelete,

                /*
                | Property Permissions
                */
            Permission::PropertyIndex,
            Permission::PropertyShow,
            Permission::PropertyStore,
            Permission::PropertyUpdate,
            Permission::PropertyDelete,
            Permission::PropertyRestore,
            Permission::PropertyForceDelete,

                /*
                | Property Type Permissions
                */
            Permission::PropertyTypeStore,
            Permission::PropertyTypeUpdate,
            Permission::PropertyTypeForceDelete,

                /*
                | Rental Type Permissions
                */
            Permission::RentalTypeStore,
            Permission::RentalTypeUpdate,
            Permission::RentalTypeForceDelete,

                /*
                | Renter Permissions
                */
            Permission::RenterIndex,
            Permission::RenterShow,
            Permission::RenterStore,
            Permission::RenterUpdate,
            Permission::RenterDelete,
            Permission::RenterRestore,
            Permission::RenterForceDelete,

                /*
                | Staff Permissions
                */
            Permission::StaffIndex,
            Permission::StaffShow,
            Permission::StaffStore,
            Permission::StaffUpdate,
            Permission::StaffDelete,
            Permission::StaffRestore,
            Permission::StaffForceDelete,

                /*
                | Surface Measurement Type Permissions
                */
            Permission::SurfaceMeasurementTypeStore,
            Permission::SurfaceMeasurementTypeUpdate,
            Permission::SurfaceMeasurementTypeForceDelete,
        ];

        foreach ($permissions as $newPermission) {
            Permission::create(['name' => $newPermission]);
        }
    }
}
