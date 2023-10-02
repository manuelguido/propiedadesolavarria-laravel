<?php

namespace Tests\Feature\Api;

use App\Models\Permission;
use App\Models\AntiquityType;
use App\Models\Role;
use Illuminate\Support\Str;
use Tests\Feature\Api\ApiUtils;
use Tests\TestCase;

class AntiquityTypeDeleteTest extends TestCase
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
            ->getAnyUserByPermission(Permission::AntiquityTypeDelete)
            ->createToken('TestToken')->plainTextToken;
        $this->authHeaders = $this->apiUtils->generateHttpHeaders($authorizedToken);

        // Unauthorized token and headers
        $unauthorizedToken = $this->apiUtils
            ->getAnyUserWithoutPermission(Permission::AntiquityTypeDelete)
            ->createToken('TestToken')->plainTextToken;
        $this->unauthHeaders = $this->apiUtils->generateHttpHeaders($unauthorizedToken);

        // Antiquity type to delete
        $this->antiquityTypeToDelete = AntiquityType::create(['name' => Str::random(50)]);

        // Route
        $this->route = '/api/v1/antiquity-type/delete/' . $this->antiquityTypeToDelete->antiquity_type_id;
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

    public function test_antiquity_type_is_deleted_succesfully()
    {
        $this->deleteJson($this->route, [], $this->authHeaders);
        $this->assertSoftDeleted('antiquity_type', [
            'antiquity_type_id' => $this->antiquityTypeToDelete->antiquity_type_id
        ]);
    }

    public function test_antiquity_type_cant_be_deleted_when_there_are_properties_using_it()
    {
        $renter = $this->apiUtils->getAnyUserByRole(Role::RoleRenter)->renter;

        $properties = $this->apiUtils->generatePropertiesForRenter($renter);

        foreach ($properties as $property) {
            $property->antiquity_type_id = $this->antiquityTypeToDelete->antiquity_type_id;
            $property->save();
        }

        $this->apiUtils->generatePostsForProperties($properties, $renter);

        $this->deleteJson($this->route, [], $this->authHeaders);

        // This asserts that the model has not been deleted.
        $this->assertDatabaseHas('antiquity_type', [
            'antiquity_type_id' => $this->antiquityTypeToDelete->antiquity_type_id,
            $this->antiquityTypeToDelete->getDeletedAtColumn() => null
        ]);
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
