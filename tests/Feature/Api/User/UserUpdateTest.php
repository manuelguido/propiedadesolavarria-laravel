<?php

namespace Tests\Feature\Api;

use App\Models\Administrator;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Str;
use Tests\Feature\Api\ApiUtils;
use Tests\TestCase;

class UserUpdateTest extends TestCase
{
    private $apiUtils;
    private $authHeaders;
    private $route;
    private $newData;

    protected function setUp(): void
    {
        // Parent setup
        parent::setUp();

        // Api utilities
        $this->apiUtils = new ApiUtils;

        // Authorized token and headers
        $user = User::inRandomOrder()->first();
        $authorizedToken = $user->createToken('TestToken')->plainTextToken;
        $this->authHeaders = $this->apiUtils->generateHttpHeaders($authorizedToken);

        // New data
        $this->newData = [
            'name' => 'Nuevo nombre ' . Str::random(10),
        ];

        // Route
        $this->route = '/api/v1/user/update';
    }

    public function test_user_update_returns_json()
    {
        $response = $this->patchJson($this->route, $this->newData, $this->authHeaders);
        $this->assertJson($response->getContent());
    }

    public function test_user_update_returns_code_201()
    {
        $newUser = $this->apiUtils->generateNewUserDataToRegister();

        $response = $this->patchJson($this->route, $this->newData, $this->authHeaders);
        $response->assertStatus(201);
    }

    public function test_user_update_returns_correct_information()
    {
        $response = $this->patchJson($this->route, $this->newData, $this->authHeaders);
        $this->assertEquals($this->newData['name'], $response->json()['data']['name']);
    }
}
