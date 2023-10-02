<?php

namespace Tests\Unit;

use App\Models\Client;
use App\Models\Role;
use App\Models\User;
use Tests\TestCase;

class ClientStoreTest extends TestCase
{
    private $user;
    private $client;

    protected function setUp(): void
    {
        parent::setUp();
        $this->client = Client::createClient(User::factory()->generateUserData(Role::RoleClient));
        $this->user = $this->client->user;
    }

    /**
     * @return void
     */
    public function test_client_store()
    {
        $clientToTest = Client::findWithUser($this->client->client_id);
        $this->assertEquals($this->user->getAttribute('user_id'), $clientToTest->user_id);
        $this->assertEquals($this->client->client_id, $clientToTest->client_id);
    }

    /**
     * @return void
     */
    public function test_user_has_role_client()
    {
        $roles = Client::find($this->client->client_id)->user->roles;
        $this->assertTrue($roles->contains('name', Role::RoleClient));
    }
}
