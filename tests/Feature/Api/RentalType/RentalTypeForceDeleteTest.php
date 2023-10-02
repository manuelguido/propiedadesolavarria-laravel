<?php

namespace Tests\Feature\Api;

use App\Models\Permission;
use App\Models\RentalType;
use App\Models\Role;
use Illuminate\Support\Str;
use Tests\Feature\Api\ApiUtils;
use Tests\TestCase;

class RentalTypeForceDeleteTest extends TestCase
{
    private $user;
    private $token;
    private $headers;
    private $apiUtils;
    private $rentalTypeToDelete;
    private $route;

    protected function setUp(): void
    {
        parent::setUp();
        $this->apiUtils = new ApiUtils;

        $this->rentalTypeToDelete = RentalType::createRentalType(['name' => Str::random(20) . Str::random(20)]);

        $this->user = $this->apiUtils->getAnyUserByPermission(Permission::RentalTypeForceDelete);
        $this->token = $this->user->createToken('TestToken')->plainTextToken;
        $this->headers = $this->apiUtils->generateHttpHeaders($this->token);
        $this->route = '/api/v1/rental-type/delete/' . $this->rentalTypeToDelete->rental_type_id;
    }

    public function test_rental_type_delete_response_is_201()
    {
        $response = $this->deleteJson($this->route, [], $this->headers);
        $response->assertStatus(201);
        $this->rentalTypeToDelete->forceDelete();
    }

    public function test_rental_type_delete_returns_json()
    {
        $response = $this->deleteJson($this->route, [], $this->headers);
        $this->assertJson($response->getContent());
        $this->rentalTypeToDelete->forceDelete();
    }

    public function test_rental_type_was_deleted_succesfully()
    {
        $this->deleteJson($this->route, [], $this->headers);
        $this->assertSoftDeleted('rental_type', [
            'rental_type_id' => $this->rentalTypeToDelete->rental_type_id
        ]);
        $this->rentalTypeToDelete->forceDelete();
    }

    public function test_rental_type_cant_be_deleted_when_there_are_active_posts()
    {
        $renter = $this->apiUtils->getAnyUserByRole(Role::RoleRenter)->renter;
        $properties = $this->apiUtils->generatePropertiesForRenter($renter);
        $posts = $this->apiUtils->generatePostsForProperties($properties, $renter, $this->rentalTypeToDelete->rental_type_id);

        $this->deleteJson($this->route, [], $this->headers);

        // This asserts that the model has not been deleted.
        $this->assertDatabaseHas('rental_type', [
            'rental_type_id' => $this->rentalTypeToDelete->rental_type_id,
            $this->rentalTypeToDelete->getDeletedAtColumn() => null
        ]);

        // Tear up
        foreach ($posts as $post) {
            $post->forceDelete();
        }
        foreach ($properties as $property) {
            $property->forceDelete();
        }
        $this->rentalTypeToDelete->forceDelete();
    }

}
