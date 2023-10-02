<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use DB;
use Illuminate\Database\Seeder;

class PermissionRoleSeeder extends Seeder
{
    private function getRoleId($role)
    {
        return Role::where('name', '=', $role)->first()->role_id;
    }

    private function getPermissionId($permissionName)
    {
        return Permission::where('name', '=', $permissionName)->first()->permission_id;
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $userTypes = $this->getDefinedPermissions();

        foreach ($userTypes as $userType) {
            foreach ($userType['permissions'] as $permission) {

                DB::table('permission_role')->insert([
                    'role_id' => $userType['role_id'],
                    'permission_id' => $this->getPermissionId($permission)
                ]);
            }
        }
    }

    private function getDefinedPermissions()
    {
        return [
            /*
            |--------------------------------------------------------------------------
            | Permissions for Client model
            |--------------------------------------------------------------------------
            */
            [
                'role_id' => $this->getRoleId(Role::RoleClient),
                'permissions' => [
                        /*
                        | Favourite Collection Permissions
                        */
                    Permission::FavouriteCollectionIndex,
                    Permission::FavouriteCollectionShow,
                    Permission::FavouriteCollectionStore,
                    Permission::FavouriteCollectionUpdate,
                    Permission::FavouriteCollectionForceDelete,

                        /*
                        | Favourite Property Permissions
                        */
                    Permission::FavouritePostIndex,
                    Permission::FavouritePostShow,
                    Permission::FavouritePostStore,
                    Permission::FavouritePostForceDelete,

                        /*
                        | Renter Permissions
                        */
                    Permission::RenterShow,

                        /*
                        | Post Permissions
                        */
                    Permission::PostShow,
                ]
            ],
            /*
            |--------------------------------------------------------------------------
            | Permissions for Renter model
            |--------------------------------------------------------------------------
            */
            [
                'role_id' => $this->getRoleId(Role::RoleRenter),
                'permissions' => [
                        /*
                        | Post Permissions
                        */
                    Permission::PostIndex,
                    Permission::PostShow,
                    Permission::PostStore,
                    Permission::PostUpdate,
                    Permission::PostDelete,

                        /*
                        | Property Permissions
                        */
                    Permission::PropertyIndex,
                    Permission::PropertyShow,
                    Permission::PropertyStore,
                    Permission::PropertyUpdate,
                    Permission::PropertyDelete,
                ]
            ],
            /*
            |--------------------------------------------------------------------------
            | Permissions for Moderator model
            |--------------------------------------------------------------------------
            */
            [
                'role_id' => $this->getRoleId(Role::RoleModerator),
                'permissions' => [
                        /*
                        | Post Permissions
                        */
                    Permission::PostIndex,
                    Permission::PostShow,

                        /*
                        | Property Permissions
                        */
                    Permission::PropertyIndex,
                    Permission::PropertyShow,

                        /*
                        | Renter Permissions
                        */
                    Permission::RenterIndex,
                    Permission::RenterShow,
                ]
            ],
            /*
            |--------------------------------------------------------------------------
            | Permissions for Staff model
            |--------------------------------------------------------------------------
            */
            [
                'role_id' => $this->getRoleId(Role::RoleStaff),
                'permissions' => [
                        /*
                        | Client Permissions
                        */
                    Permission::ClientIndex,
                    Permission::ClientShow,
                    Permission::ClientDelete,
                    Permission::ClientRestore,

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
                    Permission::PostDelete,
                    Permission::PostRestore,

                        /*
                        | Property Permissions
                        */
                    Permission::PropertyIndex,
                    Permission::PropertyShow,
                    Permission::PropertyDelete,
                    Permission::PropertyRestore,

                        /*
                        | Renter Permissions
                        */
                    Permission::RenterIndex,
                    Permission::RenterShow,
                    Permission::RenterStore,
                    Permission::RenterUpdate,
                    Permission::RenterDelete,
                    Permission::RenterRestore,

                        /*
                        | Staff Permissions
                        */
                    Permission::StaffIndex,
                    Permission::StaffShow,
                    Permission::StaffStore,
                ]
            ],
            /*
            |--------------------------------------------------------------------------
            | Permissions for Administrator model
            |--------------------------------------------------------------------------
            */
            [
                'role_id' => $this->getRoleId(Role::RoleAdministrator),
                'permissions' => [
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
                        | Antiquity Type Permissions
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

                        /*
                        | Moderator Permissions
                        */
                    Permission::ModeratorIndex,
                    Permission::ModeratorShow,
                    Permission::ModeratorStore,
                    Permission::ModeratorUpdate,
                    Permission::ModeratorDelete,

                        /*
                        | Post Permissions
                        */
                    Permission::PostIndex,
                    Permission::PostShow,
                    Permission::PostRestore,
                    Permission::PostForceDelete,

                        /*
                        | Property Permissions
                        */
                    Permission::PropertyIndex,
                    Permission::PropertyShow,
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
                ]
            ],
        ];
    }
}
