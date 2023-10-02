<?php

namespace Tests\Feature\Api;

use App\Models\Permission;
use App\Models\PropertyType;
use App\Models\Role;
use Illuminate\Support\Str;
use Tests\Feature\Api\ApiUtils;
use Tests\TestCase;

class PropertyTypeForceDeleteTest extends TestCase
{
    private $user;
    private $token;
    private $headers;
    private $apiUtils;
    private $propertyType;
    private $route;

    protected function setUp(): void
    {
        parent::setUp();
        $this->apiUtils = new ApiUtils;

        $this->propertyType = PropertyType::createPropertyType([
            'name' => Str::random(50),
            'short_name' => Str::random(20),
        ]);

        $this->user = $this->apiUtils->getAnyUserByPermission(Permission::PropertyTypeForceDelete);
        $this->token = $this->user->createToken('TestToken')->plainTextToken;
        $this->headers = $this->apiUtils->generateHttpHeaders($this->token);
        $this->route = '/api/v1/property-type/delete/' . $this->propertyType->property_type_id;
    }

    public function test_property_type_delete_response_is_201()
    {
        $response = $this->deleteJson($this->route, [], $this->headers);
        $response->assertStatus(201);
    }

    public function test_property_type_delete_returns_json()
    {
        $response = $this->deleteJson($this->route, [], $this->headers);
        $this->assertJson($response->getContent());
    }

    public function test_property_type_was_deleted_succesfully()
    {
        $this->deleteJson($this->route, [], $this->headers);
        $this->assertSoftDeleted('property_type', [
            'property_type_id' => $this->propertyType->property_type_id
        ]);
    }

    public function test_property_type_cant_be_deleted_when_there_are_properties_using_it()
    {
        $renter = $this->apiUtils->getAnyUserByRole(Role::RoleRenter)->renter;
        $properties = $this->apiUtils->generatePropertiesForRenter($renter, 1, $this->propertyType->property_type_id);
        $this->apiUtils->generatePostsForProperties($properties, $renter);

        $this->deleteJson($this->route, [], $this->headers);

        // This asserts that the model has not been deleted.
        $this->assertDatabaseHas('property_type', [
            'property_type_id' => $this->propertyType->property_type_id,
            $this->propertyType->getDeletedAtColumn() => null
        ]);
    }

}
