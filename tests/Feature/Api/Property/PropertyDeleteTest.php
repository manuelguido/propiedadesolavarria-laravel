<?php

namespace Tests\Feature\Api;

use App\Models\Post;
use App\Models\Property;
use App\Models\Permission;
use App\Models\User;
use App\Models\Role;
use Tests\Feature\Api\ApiUtils;
use Tests\TestCase;

class PropertyDeleteTest extends TestCase
{
    private $apiUtils;
    private $authHeaders;
    private $unauthHeaders;
    private $propertyToDelete;
    private $user;
    private $route;

    protected function setUp(): void
    {
        // Parent setup
        parent::setUp();

        // Api utilities
        $this->apiUtils = new ApiUtils;

        // Authorized token and headers
        $this->user = $this->apiUtils->getAnyUserByPermission(Permission::PropertyDelete);
        $this->authHeaders = $this->apiUtils->generateHttpHeaders(
            $this->user->createToken('TestToken')->plainTextToken
        );

        // Unauthorized token and headers
        $unauthorizedToken = $this->apiUtils
            ->getAnyUserWithoutPermission(Permission::PropertyDelete)
            ->createToken('TestToken')->plainTextToken;
        $this->unauthHeaders = $this->apiUtils->generateHttpHeaders($unauthorizedToken);

        // Property to delete
        $this->propertyToDelete = Property::factory(1)->createPropertyForRenter($this->user->renter->renter_id);
        Post::factory(1)->createPostForProperty($this->propertyToDelete);

        // Route
        $this->route = '/api/v1/property/delete/' . $this->propertyToDelete->property_id;
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

    public function test_property_is_deleted_succesfully()
    {
        $response = $this->deleteJson($this->route, [], $this->authHeaders);
        $response->assertStatus(200);
        $this->assertSoftDeleted('property', [
            'property_id' => $this->propertyToDelete->property_id
        ]);
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
