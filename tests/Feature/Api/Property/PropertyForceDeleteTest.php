<?php

namespace Tests\Feature\Api;

use App\Models\Permission;
use App\Models\Property;
use App\Models\Role;
use Illuminate\Support\Str;
use Tests\Feature\Api\ApiUtils;
use Tests\TestCase;

class PropertyForceDeleteTest extends TestCase
{
    private $apiUtils;
    private $authHeaders;
    private $unauthHeaders;
    private $propertyToDelete;
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
            ->getAnyUserWithoutPermission(Permission::PropertyForceDelete)
            ->createToken('TestToken')->plainTextToken;
        $this->unauthHeaders = $this->apiUtils->generateHttpHeaders($unauthorizedToken);

        // Antiquity type to delete
        $this->propertyToDelete = Property::factory(1)->create([
            'renter_id' => $this->user->renter->renter_id
        ])->first();
        $this->propertyToDelete->delete(); // Because must be trashed before being permanently deleted

        // Route
        $this->route = '/api/v1/property/delete/force/' . $this->propertyToDelete->property_id;
    }


    public function test_property_delete_returns_json()
    {
        $response = $this->deleteJson($this->route, [], $this->authHeaders);
        $this->assertJson($response->getContent());
    }

    public function test_property_delete_returns_code_200()
    {
        $response = $this->deleteJson($this->route, [], $this->authHeaders);
        $response->assertStatus(200);
    }

    // public function test_property_delete_returns_code_403_when_is_soft_deleted()
    // {
    //     $response = $this->deleteJson($this->route, [], $this->authHeaders);
    //     $response->assertStatus(403);
    // }

    public function test_property_is_force_deleted_succesfully()
    {
        $response = $this->deleteJson($this->route, [], $this->authHeaders);
        // dd($response);
        $result = Property::withTrashed()->find($this->propertyToDelete->property_id);
        $this->assertNull($result);
    }

    public function test_property_delete_returns_json_with_unathorized_user()
    {
        $response = $this->deleteJson($this->route, [], $this->unauthHeaders);
        $this->assertJson($response->getContent());
    }

    public function test_property_delete_returns_code_403_with_unathorized_user()
    {
        $response = $this->deleteJson($this->route, [], $this->unauthHeaders);
        $response->assertStatus(403);
    }
}
