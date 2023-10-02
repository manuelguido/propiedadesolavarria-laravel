<?php

namespace Tests\Feature\Api;

use App\Models\Permission;
use App\Models\AntiquityType;
use Illuminate\Support\Str;
use Tests\Feature\Api\ApiUtils;
use Tests\TestCase;

class AntiquityTypeUpdateTest extends TestCase
{
    private $apiUtils;
    private $authHeaders;
    private $unauthHeaders;
    private $route;
    private $newData;

    protected function setUp(): void
    {
        $this->refreshApplication();

        // Parent setup
        parent::setUp();

        // Api utilities
        $this->apiUtils = new ApiUtils;

        // Authorized token and headers
        $authorizedToken = $this->apiUtils
            ->getAnyUserByPermission(Permission::AntiquityTypeUpdate)
            ->createToken('TestToken')->plainTextToken;
        $this->authHeaders = $this->apiUtils->generateHttpHeaders($authorizedToken);

        // Unauthorized token and headers
        $unauthorizedToken = $this->apiUtils
            ->getAnyUserWithoutPermission(Permission::AntiquityTypeUpdate)
            ->createToken('TestToken')->plainTextToken;
        $this->unauthHeaders = $this->apiUtils->generateHttpHeaders($unauthorizedToken);

        // New data
        $this->newData = ['name' => Str::random(50), 'short_name' => Str::random(20)];

        // Route
        $this->route = '/api/v1/antiquity-type/update/' . AntiquityType::inRandomOrder()->first()->antiquity_type_id;
    }

    public function test_antiquity_type_update_returns_json()
    {
        $response = $this->patchJson($this->route, $this->newData, $this->authHeaders);
        $this->assertJson($response->getContent());
    }

    public function test_antiquity_type_update_returns_code_201()
    {
        $response = $this->patchJson($this->route, $this->newData, $this->authHeaders);
        $response->assertStatus(201);
    }

    public function test_antiquity_type_update_is_updated_succesfully()
    {
        $response = $this->patchJson($this->route, $this->newData, $this->authHeaders);
        $this->assertEquals($this->newData['name'], $response->json()['data']['name']);
    }

    public function test_antiquity_type_update_returns_json_with_unathorized_user()
    {
        $response = $this->patchJson($this->route, $this->newData, $this->unauthHeaders);
        $this->assertJson($response->getContent());
    }

    public function test_antiquity_type_update_returns_code_403_with_unathorized_user()
    {
        $response = $this->patchJson($this->route, $this->newData, $this->unauthHeaders);
        $response->assertStatus(403);
    }
}
