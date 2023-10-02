<?php

namespace Tests\Unit;

use App\Models\Role;
use Tests\TestCase;
use Tests\UtilsForTests;

class UserHasRoleTest extends TestCase
{
    private $users;
    private $utils;

    protected function setUp(): void
    {
        parent::setUp();
        $this->utils = new UtilsForTests;
        $this->users = $this->utils->setupCompleteUsers();
    }

    private function getRoleName($userRole)
    {

        return $this->users[$userRole]['user']->roles->first()->name;
    }

    /**
     * Test user has role client.
     *
     * @return void
     */
    public function test_user_has_client_role()
    {
        $this->assertEquals(Role::RoleClient, $this->getRoleName(Role::RoleClient));
    }

    /**
     * Test user has role client.
     *
     * @return void
     */
    public function test_user_has_renter_role()
    {
        $this->assertEquals(Role::RoleRenter, $this->getRoleName(Role::RoleRenter));
    }

    /**
     * Test user has role client.
     *
     * @return void
     */
    public function test_user_has_administrator_role()
    {
        $this->assertEquals(Role::RoleAdministrator, $this->getRoleName(Role::RoleAdministrator));
    }

    /**
     * Test user has role client.
     *
     * @return void
     */
    public function test_user_has_staff_role()
    {
        $this->assertEquals(Role::RoleStaff, $this->getRoleName(Role::RoleStaff));
    }

    /**
     * Test user has role client.
     *
     * @return void
     */
    public function test_user_has_moderator_role()
    {
        $this->assertEquals(Role::RoleModerator, $this->getRoleName(Role::RoleModerator));
    }
}
