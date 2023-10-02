<?php

namespace Tests\Feature\Api;

use App\Models\Permission;
use App\Models\Renter;
use App\Models\Role;
use App\Models\User;
use Tests\Feature\Api\ApiUtils;
use Tests\TestCase;

class RenterStoreTest extends TestCase
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
            ->getAnyUserByPermission(Permission::RenterStore)
            ->createToken('TestToken')->plainTextToken;
        $this->authHeaders = $this->apiUtils->generateHttpHeaders($authorizedToken);

        // Unauthorized token and headers
        $unauthorizedToken = $this->apiUtils
            ->getAnyUserWithoutPermission(Permission::RenterStore)
            ->createToken('TestToken')->plainTextToken;
        $this->unauthHeaders = $this->apiUtils->generateHttpHeaders($unauthorizedToken);

        // Route
        $this->route = '/api/v1/renter/store';
    }

    private function generateUserData()
    {
        $newUser = $this->apiUtils->addRenterData(
            $this->apiUtils->generateNewUserDataToRegister()
        );
        $newUser['image'] = Renter::factory()->generateRandomImage();
        return $newUser;
    }

    public function test_renter_store_returns_json()
    {
        $newUser = $this->generateUserData();
        $response = $this->postJson($this->route, $newUser, $this->authHeaders);
        $this->assertJson($response->getContent());
    }

    public function test_renter_store_returns_code_200()
    {
        $newUser = $this->generateUserData();
        $response = $this->postJson($this->route, $newUser, $this->authHeaders);
        $response->assertStatus(201);
    }

    public function test_renter_store_returns_correct_information()
    {
        $newUser = $this->generateUserData();
        $response = $this->postJson($this->route, $newUser, $this->authHeaders);

        $this->assertEquals($newUser['address'], $response->json()['data']['address']);
        $this->assertEquals($newUser['commercial_email'], $response->json()['data']['commercial_email']);
        $this->assertEquals($newUser['email'], $response->json()['data']['email']);
        $this->assertEquals($newUser['name'], $response->json()['data']['name']);
        $this->assertEquals($newUser['whatsapp_phone'], $response->json()['data']['whatsapp_phone']);
    }

    public function test_renter_store_has_role_Renter()
    {
        $newUser = $this->generateUserData();

        $response = $this->postJson($this->route, $newUser, $this->authHeaders);
        $this->assertTrue(
            User::find($response->json()['data']['user_id'])->hasRole(Role::RoleRenter)
        );
    }

    public function test_renter_store_already_exists()
    {
        $user = $this->apiUtils->getAnyExistingUserByRole(Role::RoleRenter);

        $repeatedUser = Renter::findWithUser($user->renter->renter_id)->toArray();

        $response = $this->postJson($this->route, $repeatedUser, $this->authHeaders);
        $response->assertStatus(422);
    }

    public function test_renter_store_returns_json_with_unathorized_user()
    {
        $newUser = $this->generateUserData();
        $response = $this->postJson($this->route, $newUser, $this->unauthHeaders);
        $this->assertJson($response->getContent());
    }

    public function test_renter_store_returns_code_403_with_unathorized_user()
    {
        $newUser = $this->generateUserData();
        $response = $this->postJson($this->route, $newUser, $this->unauthHeaders);
        $response->assertStatus(403);
    }
}
