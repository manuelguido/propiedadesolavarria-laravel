<?php

namespace Tests\Feature\Api;

use App\Models\Permission;
use App\Models\SurfaceMeasurementType;
use App\Models\Role;
use Illuminate\Support\Str;
use Tests\Feature\Api\ApiUtils;
use Tests\TestCase;

class SurfaceMeasurementTypeForceDeleteTest extends TestCase
{
    private $user;
    private $token;
    private $headers;
    private $apiUtils;
    private $sufaceMeasurementTypeToDelete;
    private $route;

    protected function setUp(): void
    {
        parent::setUp();
        $this->apiUtils = new ApiUtils;

        $this->sufaceMeasurementTypeToDelete = SurfaceMeasurementType::createSurfaceMeasurementType([
            'name' => Str::random(50),
            'short_name' => Str::random(20),
        ]);

        $this->user = $this->apiUtils->getAnyUserByPermission(Permission::SurfaceMeasurementTypeForceDelete);
        $this->token = $this->user->createToken('TestToken')->plainTextToken;
        $this->headers = $this->apiUtils->generateHttpHeaders($this->token);
        $this->route = '/api/v1/surface-measurement-type/delete/' . $this->sufaceMeasurementTypeToDelete->surface_measurement_type_id;
    }

    public function test_surface_measurement_type_delete_response_is_201()
    {
        $response = $this->deleteJson($this->route, [], $this->headers);
        $response->assertStatus(201);
    }

    public function test_surface_measurement_type_delete_returns_json()
    {
        $response = $this->deleteJson($this->route, [], $this->headers);
        $this->assertJson($response->getContent());
    }

    public function test_surface_measurement_type_was_deleted_succesfully()
    {
        $this->deleteJson($this->route, [], $this->headers);
        $this->assertSoftDeleted('surface_measurement_type', [
            'surface_measurement_type_id' => $this->sufaceMeasurementTypeToDelete->surface_measurement_type_id
        ]);
    }

    public function test_surface_measurement_type_cant_be_deleted_when_there_are_properties_using_it()
    {
        $renter = $this->apiUtils->getAnyUserByRole(Role::RoleRenter)->renter;
        $properties = $this->apiUtils->generatePropertiesForRenter($renter, $this->sufaceMeasurementTypeToDelete->surface_measurement_type_id);
        $this->apiUtils->generatePostsForProperties($properties, $renter);

        $this->deleteJson($this->route, [], $this->headers);

        // This asserts that the model has not been deleted.
        $this->assertDatabaseHas('surface_measurement_type', [
            'surface_measurement_type_id' => $this->sufaceMeasurementTypeToDelete->surface_measurement_type_id,
            $this->sufaceMeasurementTypeToDelete->getDeletedAtColumn() => null
        ]);
    }

}
