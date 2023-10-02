<?php

namespace Tests\Feature\Api;

use App\Models\Permission;
use App\Models\SurfaceMeasurementType;
use Illuminate\Support\Str;
use Tests\Feature\Api\ApiUtils;
use Tests\TestCase;

class SurfaceMeasurementTypeUpdateTest extends TestCase
{
    private $user;
    private $token;
    private $headers;
    private $route;
    private $apiUtils;
    private $newData;

    protected function setUp(): void
    {
        parent::setUp();
        $this->apiUtils = new ApiUtils;
        $this->user = $this->apiUtils->getAnyUserByPermission(Permission::SurfaceMeasurementTypeUpdate);
        $this->token = $this->user->createToken('TestToken')->plainTextToken;

        $this->headers = $this->apiUtils->generateHttpHeaders($this->token);
        $this->newData = [
            'name' => Str::random(50),
            'short_name' => Str::random(20),
        ];

        $idToUpdate = SurfaceMeasurementType::inRandomOrder()->first()->surface_measurement_type_id;
        $this->route = '/api/v1/surface-measurement-type/update/' . $idToUpdate;
    }

    public function test_surface_measurement_type_update_returns_json()
    {
        $response = $this->patchJson($this->route, $this->newData, $this->headers);
        $this->assertJson($response->getContent());
    }

    public function test_surface_measurement_type_was_updated_succesfully()
    {
        $response = $this->patchJson($this->route, $this->newData, $this->headers);
        $response->assertStatus(201);
        $this->assertEquals($this->newData['name'], $response->json()['surface_measurement_type']['name']);
        $this->assertEquals($this->newData['short_name'], $response->json()['surface_measurement_type']['short_name']);
    }

    public function test_surface_measurement_type_returns_442_when_data_is_missing()
    {
        $response = $this->patchJson($this->route, [], $this->headers);
        $response->assertStatus(422);
    }
}
