<?php

namespace Tests\Unit;

use App\Models\Administrator;
use App\Models\Role;
use App\Models\User;
use Tests\TestCase;

class AdministratorStoreTest extends TestCase
{
    private $user;
    private $administrator;

    protected function setUp(): void
    {
        parent::setUp();
        $this->administrator = Administrator::createAdministrator(User::factory()->generateUserData(Role::RoleAdministrator));
        $this->user = $this->administrator->user;
    }

    /**
     * @return void
     */
    public function test_administrator_store()
    {
        $administratorToTest = Administrator::findWithUser($this->administrator->administrator_id);
        $this->assertEquals($this->user->getAttribute('user_id'), $administratorToTest->user_id);
        $this->assertEquals($this->administrator->administrator_id, $administratorToTest->administrator_id);
    }

    /**
     * @return void
     */
    public function test_user_has_role_administrator()
    {
        $roles = Administrator::find($this->administrator->administrator_id)->user->roles;
        $this->assertTrue($roles->contains('name', Role::RoleAdministrator));
    }
}
