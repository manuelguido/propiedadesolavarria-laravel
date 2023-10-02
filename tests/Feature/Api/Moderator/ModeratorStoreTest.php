<?php

namespace Tests\Feature\Api;

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Tests\Feature\Api\ApiUtils;
use Tests\TestCase;

class ModeratorStoreTest extends TestCase
{
    private $apiUtils;
    private $authHeaders;
    private $unauthHeaders;
    private $route;

    protected function setUp(): void
    {
        // Parent setup
        parent::setUp();

        // Api utilities
        $this->apiUtils = new ApiUtils;

        // Authorized token and headers
        $authorizedToken = $this->apiUtils
            ->getAnyUserByPermission(Permission::ModeratorStore)
            ->createToken('TestToken')->plainTextToken;
        $this->authHeaders = $this->apiUtils->generateHttpHeaders($authorizedToken);

        // Unauthorized token and headers
        $unauthorizedToken = $this->apiUtils
            ->getAnyUserWithoutPermission(Permission::ModeratorStore)
            ->createToken('TestToken')->plainTextToken;
        $this->unauthHeaders = $this->apiUtils->generateHttpHeaders($unauthorizedToken);

        // Route
        $this->route = '/api/v1/moderator/store';
    }

    public function test_moderator_store_returns_json()
    {
        $newUser = $this->apiUtils->generateNewUserDataToRegister();

        $response = $this->postJson($this->route, $newUser, $this->authHeaders);
        $this->assertJson($response->getContent());
    }

    public function test_moderator_store_returns_code_200()
    {
        $newUser = $this->apiUtils->generateNewUserDataToRegister();

        $response = $this->postJson($this->route, $newUser, $this->authHeaders);
        $response->assertStatus(201);
    }

    public function test_moderator_store_returns_correct_information()
    {
        $newUser = $this->apiUtils->generateNewUserDataToRegister();

        $response = $this->postJson($this->route, $newUser, $this->authHeaders);
        $this->assertEquals($newUser['name'], $response->json()['data']['name']);
        $this->assertEquals($newUser['email'], $response->json()['data']['email']);
    }

    public function test_moderator_store_has_role_Moderator()
    {
        $newUser = $this->apiUtils->generateNewUserDataToRegister();

        $response = $this->postJson($this->route, $newUser, $this->authHeaders);
        $this->assertTrue(
            User::find($response->json()['data']['user_id'])->hasRole(Role::RoleModerator)
        );
    }

    public function test_moderator_store_already_exists()
    {
        $repeatedUser = $this->apiUtils->getAnyExistingUserByRole(Role::RoleModerator)->toArray();

        $response = $this->postJson($this->route, $repeatedUser, $this->authHeaders);
        $response->assertStatus(422);
    }

    public function test_moderator_store_returns_json_with_unathorized_user()
    {
        $newUser = $this->apiUtils->generateNewUserDataToRegister();

        $response = $this->postJson($this->route, $newUser, $this->unauthHeaders);
        $this->assertJson($response->getContent());
    }

    public function test_moderator_store_returns_code_403_with_unathorized_user()
    {
        $newUser = $this->apiUtils->generateNewUserDataToRegister();
        $response = $this->postJson($this->route, $newUser, $this->unauthHeaders);
        $response->assertStatus(403);
    }
}
