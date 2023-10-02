<?php

namespace Tests\Unit;

use App\Models\Role;
use Tests\TestCase;
use Tests\UtilsForTests;

class UserHasPermissionTest extends TestCase
{
    private $users;
    private $utils;

    protected function setUp(): void
    {
        parent::setUp();
        $this->utils = new UtilsForTests;
        $this->users = $this->utils->setupCompleteUsers();
        // dd($this->users);
    }

    /**
     * Returns all the User permissions y its role as an array of strings.
     *
     * @return array
     */
    private function getUserPermissions($userRole)
    {
        return $this->formatPermissionsAsArray($this->users[$userRole]['user']->roles->first()->permissions);
    }

    /**
     * Returns all the Role permissions as an array of strings.
     *
     * @return array
     */
    private function getRolePermissions($roleName)
    {
        return $this->formatPermissionsAsArray(Role::where('name', '=', $roleName)->first()->permissions);
    }

    private function formatPermissionsAsArray($permissions)
    {
        $permission_array = [];
        foreach ($permissions as $permission) {
            array_push($permission_array, $permission->name);
        }
        return $permission_array;
    }

    /**
     * Test user has role client.
     *
     * @return void
     */
    public function test_user_has_client_permissions()
    {
        $userPermissions = $this->getUserPermissions(Role::RoleClient);
        $rolePermissions = $this->getRolePermissions(Role::RoleClient);
        $this->assertEmpty(array_diff($rolePermissions, $userPermissions));
    }

    /**
     * Test user has role client.
     *
     * @return void
     */
    public function test_user_has_renter_permissions()
    {
        $userPermissions = $this->getUserPermissions(Role::RoleRenter);
        $rolePermissions = $this->getRolePermissions(Role::RoleRenter);
        $this->assertEmpty(array_diff($rolePermissions, $userPermissions));
    }


    /**
     * Test user has role client.
     *
     * @return void
     */
    public function test_user_has_administrator_permissions()
    {
        $userPermissions = $this->getUserPermissions(Role::RoleAdministrator);
        $rolePermissions = $this->getRolePermissions(Role::RoleAdministrator);
        $this->assertEmpty(array_diff($rolePermissions, $userPermissions));
    }


    /**
     * Test user has role client.
     *
     * @return void
     */
    public function test_user_has_staff_permissions()
    {
        $userPermissions = $this->getUserPermissions(Role::RoleStaff);
        $rolePermissions = $this->getRolePermissions(Role::RoleStaff);
        $this->assertEmpty(array_diff($rolePermissions, $userPermissions));
    }

    /**
     * Test user has role client.
     *
     * @return void
     */
    public function test_user_has_moderator_permissions()
    {
        $userPermissions = $this->getUserPermissions(Role::RoleModerator);
        $rolePermissions = $this->getRolePermissions(Role::RoleModerator);
        $this->assertEmpty(array_diff($rolePermissions, $userPermissions));
    }
}
