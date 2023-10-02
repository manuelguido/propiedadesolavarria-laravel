<?php

namespace Tests\Feature\Api;

use App\Models\Staff;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Str;
use Tests\Feature\Api\ApiUtils;
use Tests\TestCase;

class StaffUpdateTest extends TestCase
{
    private $apiUtils;
    private $authHeaders;
    private $unauthHeaders;
    private $route;
    private $newData;

    protected function setUp(): void
    {
        // Parent setup
        parent::setUp();

        // Api utilities
        $this->apiUtils = new ApiUtils;

        // Authorized token and headers
        $authorizedToken = $this->apiUtils
            ->getAnyUserByPermission(Permission::StaffUpdate)
            ->createToken('TestToken')->plainTextToken;
        $this->authHeaders = $this->apiUtils->generateHttpHeaders($authorizedToken);

        // Unauthorized token and headers
        $unauthorizedToken = $this->apiUtils
            ->getAnyUserWithoutPermission(Permission::StaffUpdate)
            ->createToken('TestToken')->plainTextToken;
        $this->unauthHeaders = $this->apiUtils->generateHttpHeaders($unauthorizedToken);

        $this->newData = [
            'name' => 'Nuevo nombre ' . Str::random(10),
        ];

        // Route
        $this->route = '/api/v1/staff/update/' . Staff::inRandomOrder()->first()->staff_id;
        ;
    }

    public function test_staff_update_returns_json()
    {
        $response = $this->patchJson($this->route, $this->newData, $this->authHeaders);
        $this->assertJson($response->getContent());
    }

    public function test_staff_update_returns_code_201()
    {
        $response = $this->patchJson($this->route, $this->newData, $this->authHeaders);
        $response->assertStatus(201);
    }

    public function test_staff_update_returns_correct_information()
    {
        $response = $this->patchJson($this->route, $this->newData, $this->authHeaders);
        $this->assertEquals($this->newData['name'], $response->json()['data']['name']);
    }

    public function test_staff_update_returns_json_with_unathorized_user()
    {
        $response = $this->patchJson($this->route, $this->newData, $this->unauthHeaders);
        $this->assertJson($response->getContent());
    }

    public function test_staff_update_returns_code_403_with_unathorized_user()
    {
        $response = $this->patchJson($this->route, $this->newData, $this->unauthHeaders);
        $response->assertStatus(403);
    }
}
