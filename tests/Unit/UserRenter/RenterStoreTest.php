<?php

namespace Tests\Unit;

use App\Models\Renter;
use App\Models\Role;
use App\Models\User;
use Tests\TestCase;

class RenterStoreTest extends TestCase
{
    private $user;
    private $renter;
    private $utils;

    protected function setUp(): void
    {
        parent::setUp();
        $this->renter = Renter::createRenter(User::factory()->generateUserData(Role::RoleRenter));
        $this->user = $this->renter->user;
    }

    /**
     * @return void
     */
    public function test_renter_store()
    {
        $renterToTest = Renter::findWithUser($this->renter->renter_id);
        $this->assertEquals($this->user->getAttribute('user_id'), $renterToTest->user_id);
        $this->assertEquals($this->renter->renter_id, $renterToTest->renter_id);
    }

    /**
     * @return void
     */
    public function test_user_has_role_renter()
    {
        $roles = Renter::find($this->renter->renter_id)->user->roles;
        $this->assertTrue($roles->contains('name', Role::RoleRenter));
    }
}
