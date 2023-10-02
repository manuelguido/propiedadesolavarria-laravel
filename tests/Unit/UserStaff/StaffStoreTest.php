<?php

namespace Tests\Unit;

use App\Models\Staff;
use App\Models\Role;
use App\Models\User;
use Tests\TestCase;

class StaffStoreTest extends TestCase
{
    private $user;
    private $staff;

    protected function setUp(): void
    {
        parent::setUp();
        $this->staff = Staff::createStaff(User::factory()->generateUserData(Role::RoleStaff));
        $this->user = $this->staff->user;
    }

    /**
     * @return void
     */
    public function test_staff_store()
    {
        $staffToTest = Staff::findWithUser($this->staff->staff_id);
        $this->assertEquals($this->user->getAttribute('user_id'), $staffToTest->user_id);
        $this->assertEquals($this->staff->staff_id, $staffToTest->staff_id);
    }

    /**
     * @return void
     */
    public function test_user_has_role_staff()
    {
        $roles = Staff::find($this->staff->staff_id)->user->roles;
        $this->assertTrue($roles->contains('name', Role::RoleStaff));
    }
}
