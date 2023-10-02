<?php

namespace Tests\Feature\Api;

use App\Models\Permission;
use App\Models\AntiquityType;
use Illuminate\Support\Str;
use Tests\Feature\Api\ApiUtils;
use Tests\TestCase;

class AntiquityTypeRestoreTest extends TestCase
{
    private $apiUtils;
    private $authHeaders;
    private $unauthHeaders;
    private $antiquityTypeToRestore;
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
            ->getAnyUserByPermission(Permission::AntiquityTypeRestore)
            ->createToken('TestToken')->plainTextToken;
        $this->authHeaders = $this->apiUtils->generateHttpHeaders($authorizedToken);

        // Unauthorized token and headers
        $unauthorizedToken = $this->apiUtils
            ->getAnyUserWithoutPermission(Permission::AntiquityTypeRestore)
            ->createToken('TestToken')->plainTextToken;
        $this->unauthHeaders = $this->apiUtils->generateHttpHeaders($unauthorizedToken);

        // Antiquity type to restore
        $this->antiquityTypeToRestore = AntiquityType::create(['name' => Str::random(50)]);
        $this->antiquityTypeToRestore->delete();

        // Route
        $this->route = '/api/v1/antiquity-type/restore/' . $this->antiquityTypeToRestore->antiquity_type_id;
    }


    public function test_antiquity_type_restore_returns_json()
    {
        $response = $this->patchJson($this->route, [], $this->authHeaders);
        $this->assertJson($response->getContent());
    }

    public function test_antiquity_type_restore_returns_code_200()
    {
        $response = $this->patchJson($this->route, [], $this->authHeaders);
        $response->assertStatus(200);
    }

    public function test_antiquity_type_is_restored_succesfully()
    {
        $this->patchJson($this->route, [], $this->authHeaders);

        // This asserts that the model has not been restored.
        $this->assertDatabaseHas('antiquity_type', [
            'antiquity_type_id' => $this->antiquityTypeToRestore->antiquity_type_id,
            $this->antiquityTypeToRestore->getDeletedAtColumn() => null
        ]);
    }

    public function test_antiquity_type_restore_returns_json_with_unathorized_user()
    {
        $response = $this->patchJson($this->route, [], $this->unauthHeaders);
        $this->assertJson($response->getContent());
    }

    public function test_antiquity_type_restore_returns_code_403_with_unathorized_user()
    {
        $response = $this->patchJson($this->route, [], $this->unauthHeaders);
        $response->assertStatus(403);
    }
}
