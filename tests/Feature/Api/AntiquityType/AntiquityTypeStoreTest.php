<?php

namespace Tests\Feature\Api;

use App\Models\Permission;
use App\Models\AntiquityType;
use Illuminate\Support\Str;
use Tests\Feature\Api\ApiUtils;
use Tests\TestCase;

class AntiquityTypeStoreTest extends TestCase
{
    private $apiUtils;
    private $authHeaders;
    private $unauthHeaders;
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
            ->getAnyUserByPermission(Permission::AntiquityTypeStore)
            ->createToken('TestToken')->plainTextToken;
        $this->authHeaders = $this->apiUtils->generateHttpHeaders($authorizedToken);

        // Unauthorized token and headers
        $unauthorizedToken = $this->apiUtils
            ->getAnyUserWithoutPermission(Permission::AntiquityTypeStore)
            ->createToken('TestToken')->plainTextToken;
        $this->unauthHeaders = $this->apiUtils->generateHttpHeaders($unauthorizedToken);

        // Route
        $this->route = '/api/v1/antiquity-type/store';
    }

    public function test_antiquity_type_store_returns_json()
    {
        $newAntiquityType = ['name' => Str::random(50)];

        $response = $this->postJson($this->route, $newAntiquityType, $this->authHeaders);
        $this->assertJson($response->getContent());
    }

    public function test_antiquity_type_store_returns_code_200()
    {
        $newAntiquityType = ['name' => Str::random(50)];

        $response = $this->postJson($this->route, $newAntiquityType, $this->authHeaders);
        $response->assertStatus(201);
    }

    public function test_antiquity_type_store_returns_correct_information()
    {
        $newAntiquityType = ['name' => Str::random(50)];

        $response = $this->postJson($this->route, $newAntiquityType, $this->authHeaders);
        $this->assertEquals($newAntiquityType['name'], $response->json()['data']['name']);
    }

    public function test_antiquity_type_store_already_exists()
    {
        $repeatedAntiquityType = AntiquityType::inRandomOrder()->first()->toArray();

        $response = $this->postJson($this->route, $repeatedAntiquityType, $this->authHeaders);
        $response->assertStatus(422);
    }

    public function test_antiquity_type_store_returns_json_with_unathorized_user()
    {
        $newAntiquityType = ['name' => Str::random(50)];

        $response = $this->postJson($this->route, $newAntiquityType, $this->unauthHeaders);
        $this->assertJson($response->getContent());
    }

    public function test_antiquity_type_store_returns_code_403_with_unathorized_user()
    {
        $newAntiquityType = ['name' => Str::random(50)];

        $response = $this->postJson($this->route, $newAntiquityType, $this->unauthHeaders);
        $response->assertStatus(403);
    }
}
