<?php

namespace Tests\Unit;

use App\Models\Moderator;
use App\Models\Role;
use App\Models\User;
use Tests\TestCase;

class ModeratorStoreTest extends TestCase
{
    private $user;
    private $moderator;

    protected function setUp(): void
    {
        parent::setUp();
        $this->moderator = Moderator::createModerator(User::factory()->generateUserData(Role::RoleModerator));
        $this->user = $this->moderator->user;
    }

    /**
     * @return void
     */
    public function test_moderator_store()
    {
        $moderatorToTest = Moderator::findWithUser($this->moderator->moderator_id);
        $this->assertEquals($this->user->getAttribute('user_id'), $moderatorToTest->user_id);
        $this->assertEquals($this->moderator->moderator_id, $moderatorToTest->moderator_id);
    }

    /**
     * @return void
     */
    public function test_user_has_role_moderator()
    {
        $roles = Moderator::find($this->moderator->moderator_id)->user->roles;
        $this->assertTrue($roles->contains('name', Role::RoleModerator));
    }
}
