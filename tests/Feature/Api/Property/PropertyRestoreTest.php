<?php

namespace Tests\Feature\Api;

use App\Models\Permission;
use App\Models\Property;
use App\Models\Role;
use Tests\Feature\Api\ApiUtils;
use Tests\TestCase;

class PropertyRestoreTest extends TestCase
{
    private $apiUtils;
    private $authHeaders;
    private $unauthHeaders;
    private $propertyToRestore;
    private $user;
    private $route;

    protected function setUp(): void
    {
        $this->refreshApplication();

        // Parent setup
        parent::setUp();

        // Api utilities
        $this->apiUtils = new ApiUtils;

        // Authorized token and headers
        $this->user = $this->apiUtils->getAnyUserByRole(Role::RoleRenter);
        $this->authHeaders = $this->apiUtils->generateHttpHeaders(
            $this->user->createToken('TestToken')->plainTextToken
        );

        // Unauthorized token and headers
        $unauthorizedToken = $this->apiUtils
            ->getAnyUserWithoutPermission(Permission::PropertyRestore)
            ->createToken('TestToken')->plainTextToken;
        $this->unauthHeaders = $this->apiUtils->generateHttpHeaders($unauthorizedToken);

        // Property to restore
        $this->propertyToRestore = Property::factory(1)->create([
            'renter_id' => $this->user->renter->renter_id
        ])->first();
        $this->propertyToRestore->delete();

        // Route
        $this->route = '/api/v1/property/restore/' . $this->propertyToRestore->property_id;
    }


    public function test_property_restore_returns_json()
    {
        $response = $this->patchJson($this->route, [], $this->authHeaders);
        $this->assertJson($response->getContent());
    }

    public function test_property_restore_returns_code_200()
    {
        $response = $this->patchJson($this->route, [], $this->authHeaders);
        $response->assertStatus(200);
    }

    public function test_property_is_restored_succesfully()
    {
        $this->patchJson($this->route, [], $this->authHeaders);

        // This asserts that the model has not been restored.
        $this->assertDatabaseHas('property', [
            'property_id' => $this->propertyToRestore->property_id,
            $this->propertyToRestore->getDeletedAtColumn() => null
        ]);
    }

    public function test_property_restore_returns_json_with_unathorized_user()
    {
        $response = $this->patchJson($this->route, [], $this->unauthHeaders);
        $this->assertJson($response->getContent());
    }

    public function test_property_restore_returns_code_403_with_unathorized_user()
    {
        $response = $this->patchJson($this->route, [], $this->unauthHeaders);
        $response->assertStatus(403);
    }
}
