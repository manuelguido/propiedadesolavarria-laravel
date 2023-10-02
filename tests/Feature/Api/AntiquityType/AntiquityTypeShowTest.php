<?php

namespace Tests\Feature\Api;

use App\Models\AntiquityType;
use App\Models\Permission;
use Tests\Feature\Api\ApiUtils;
use Tests\TestCase;

class AntiquityTypeShowTest extends TestCase
{
    private $apiUtils;
    private $authHeaders;
    private $unauthHeaders;
    private $expectedAntiquityType;
    private $route;

    protected function setUp(): void
    {
        $this->refreshApplication();

        // Parent setup
        parent::setUp();

        // Api utilities
        $this->apiUtils = new ApiUtils;

        // Authorized token and headers
        $authorizedToken = $this->apiUtils
            ->getAnyUserByPermission(Permission::AntiquityTypeShow)
            ->createToken('TestToken')->plainTextToken;
        $this->authHeaders = $this->apiUtils->generateHttpHeaders($authorizedToken);

        // Unauthorized token and headers
        $unauthorizedToken = $this->apiUtils
            ->getAnyUserWithoutPermission(Permission::AntiquityTypeShow)
            ->createToken('TestToken')->plainTextToken;
        $this->unauthHeaders = $this->apiUtils->generateHttpHeaders($unauthorizedToken);

        // Expected user data
        $this->expectedAntiquityType = AntiquityType::inRandomOrder()->first();

        // Route
        $this->route = '/api/v1/antiquity-type/show/' . $this->expectedAntiquityType->antiquity_type_id;
    }

    public function test_antiquity_type_show_returns_json()
    {
        $response = $this->withHeaders($this->authHeaders)->get($this->route);
        $this->assertJson($response->getContent());
    }

    public function test_antiquity_type_show_returns_code_200()
    {
        $response = $this->withHeaders($this->authHeaders)->get($this->route);
        $response->assertStatus(200);
    }

    public function test_antiquity_type_show_returns_correct_information()
    {
        $response = $this->withHeaders($this->authHeaders)->get($this->route);
        $expectedData = $this->expectedAntiquityType->toArray();
        $responseData = $response->json()['data'];
        $this->assertEquals($expectedData['name'], $responseData['name']);
        $this->assertEquals($expectedData['antiquity_type_id'], $responseData['antiquity_type_id']);
    }

    public function test_antiquity_type_show_returns_json_with_unathorized_user()
    {
        $response = $this->withHeaders($this->unauthHeaders)->get($this->route);
        $this->assertJson($response->getContent());
    }

    public function test_antiquity_type_show_returns_code_403_with_unathorized_user()
    {
        $response = $this->withHeaders($this->unauthHeaders)->get($this->route);
        $response->assertStatus(403);
    }
}
