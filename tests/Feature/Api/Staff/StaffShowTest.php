<?php

namespace Tests\Feature\Api;

use App\Models\Staff;
use App\Models\Permission;
use Tests\Feature\Api\ApiUtils;
use Tests\TestCase;

class StaffShowTest extends TestCase
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
            ->getAnyUserByPermission(Permission::StaffShow)
            ->createToken('TestToken')->plainTextToken;
        $this->authHeaders = $this->apiUtils->generateHttpHeaders($authorizedToken);

        // Unauthorized token and headers
        $unauthorizedToken = $this->apiUtils
            ->getAnyUserWithoutPermission(Permission::StaffShow)
            ->createToken('TestToken')->plainTextToken;
        $this->unauthHeaders = $this->apiUtils->generateHttpHeaders($unauthorizedToken);

        // Expected user data
        $this->expectedUser = Staff::findWithUser(
            Staff::inRandomOrder()->first()->staff_id
        );

        // Route
        $this->route = '/api/v1/staff/show/' . $this->expectedUser->staff_id;
    }

    public function test_staff_show_returns_json()
    {
        $response = $this->withHeaders($this->authHeaders)->get($this->route);
        $this->assertJson($response->getContent());
    }

    public function test_staff_show_returns_code_200()
    {
        $response = $this->withHeaders($this->authHeaders)->get($this->route);
        $response->assertStatus(200);
    }

    public function test_staff_show_returns_correct_information()
    {
        $response = $this->withHeaders($this->authHeaders)->get($this->route);
        $this->assertEmpty(array_diff($this->expectedUser->toArray(), $response->json()['data']));
    }

    public function test_staff_show_returns_json_with_unathorized_user()
    {
        $response = $this->withHeaders($this->unauthHeaders)->get($this->route);
        $this->assertJson($response->getContent());
    }

    public function test_staff_show_returns_code_403_with_unathorized_user()
    {
        $response = $this->withHeaders($this->unauthHeaders)->get($this->route);
        $response->assertStatus(403);
    }
}
