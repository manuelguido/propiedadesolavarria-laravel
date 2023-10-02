<?php

namespace Tests\Feature\Api;

use App\Models\Administrator;
// use App\Models\Role;
use App\Models\Permission;
use Tests\Feature\Api\ApiUtils;
use Tests\TestCase;

class AdministratorShowTest extends TestCase
{
    private $apiUtils;
    private $authHeaders;
    private $unauthHeaders;
    private $expectedUser;
    private $route;

    protected function setUp(): void
    {
        // Parent setup
        parent::setUp();

        // Api utilities
        $this->apiUtils = new ApiUtils;

        // Authorized token and headers
        $authorizedToken = $this->apiUtils
            ->getAnyUserByPermission(Permission::AdministratorShow)
            ->createToken('TestToken')->plainTextToken;
        $this->authHeaders = $this->apiUtils->generateHttpHeaders($authorizedToken);

        // Unauthorized token and headers
        $unauthUser = $this->apiUtils->getAnyUserWithoutPermission(Permission::AdministratorShow);
        $unauthorizedToken = $unauthUser->createToken('TestToken')->plainTextToken;
        $this->unauthHeaders = $this->apiUtils->generateHttpHeaders($unauthorizedToken);

        // $perms = [];

        // foreach ($unauthUser->roles as $role) {
        //     foreach ($role->permissions as $p) {
        //         array_push($perms, $p->name);
        //     }
        // }

        // dd($perms);

        // Expected user data
        $this->expectedUser = Administrator::findWithUser(
            Administrator::inRandomOrder()->first()->administrator_id
        );

        // Route
        $this->route = '/api/v1/administrator/show/' . $this->expectedUser->administrator_id;
    }

    public function test_administrator_show_returns_json()
    {
        $response = $this->withHeaders($this->authHeaders)->get($this->route);
        $this->assertJson($response->getContent());
    }

    public function test_administrator_show_returns_code_200()
    {
        $response = $this->withHeaders($this->authHeaders)->get($this->route);
        $response->assertStatus(200);
    }

    public function test_administrator_show_returns_correct_information()
    {
        $response = $this->withHeaders($this->authHeaders)->get($this->route);
        $this->assertEmpty(array_diff($this->expectedUser->toArray(), $response->json()['data']));
    }

    public function test_administrator_show_returns_json_with_unathorized_user()
    {
        $response = $this->withHeaders($this->unauthHeaders)->get($this->route);
        $this->assertJson($response->getContent());
    }

    public function test_administrator_show_returns_code_403_with_unathorized_user()
    {
        $response = $this->withHeaders($this->unauthHeaders)->get($this->route);
        $response->assertStatus(403);
    }
}
