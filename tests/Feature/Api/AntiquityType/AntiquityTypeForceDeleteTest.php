<?php

namespace Tests\Feature\Api;

use App\Models\Permission;
use App\Models\AntiquityType;
use Illuminate\Support\Str;
use Tests\Feature\Api\ApiUtils;
use Tests\TestCase;

class AntiquityTypeForceDeleteTest extends TestCase
{
    private $apiUtils;
    private $authHeaders;
    private $unauthHeaders;
    private $antiquityTypeToDelete;
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
            ->getAnyUserByPermission(Permission::AntiquityTypeForceDelete)
            ->createToken('TestToken')->plainTextToken;
        $this->authHeaders = $this->apiUtils->generateHttpHeaders($authorizedToken);

        // Unauthorized token and headers
        $unauthorizedToken = $this->apiUtils
            ->getAnyUserWithoutPermission(Permission::AntiquityTypeForceDelete)
            ->createToken('TestToken')->plainTextToken;
        $this->unauthHeaders = $this->apiUtils->generateHttpHeaders($unauthorizedToken);

        // Antiquity type to delete
        $this->antiquityTypeToDelete = AntiquityType::create(['name' => Str::random(50)]);
        $this->antiquityTypeToDelete->delete();

        // Route
        $this->route = '/api/v1/antiquity-type/delete/force/' . $this->antiquityTypeToDelete->antiquity_type_id;
    }


    public function test_antiquity_type_delete_returns_json()
    {
        $response = $this->deleteJson($this->route, [], $this->authHeaders);
        $this->assertJson($response->getContent());
    }

    public function test_antiquity_type_delete_returns_code_200()
    {
        $response = $this->deleteJson($this->route, [], $this->authHeaders);
        $response->assertStatus(200);
    }

    public function test_antiquity_type_is_force_deleted_succesfully()
    {
        $this->deleteJson($this->route, [], $this->authHeaders);
        $result = AntiquityType::withTrashed()->find($this->antiquityTypeToDelete->antiquity_type_id);
        $this->assertNull($result);
    }

    public function test_antiquity_type_delete_returns_json_with_unathorized_user()
    {
        $response = $this->deleteJson($this->route, [], $this->unauthHeaders);
        $this->assertJson($response->getContent());
    }

    public function test_antiquity_type_delete_returns_code_403_with_unathorized_user()
    {
        $response = $this->deleteJson($this->route, [], $this->unauthHeaders);
        $response->assertStatus(403);
    }
}
