<?php

namespace Tests\Feature\Api;

use App\Models\Permission;
use App\Models\SurfaceMeasurementType;
use Illuminate\Support\Str;
use Tests\Feature\Api\ApiUtils;
use Tests\TestCase;

class SurfaceMeasurementTypeStoreTest extends TestCase
{
    private $user;
    private $token;
    private $headers;
    private $route;
    private $apiUtils;

    protected function setUp(): void
    {
        parent::setUp();
        $this->route = '/api/v1/surface-measurement-type/store';
        $this->user = Permission::where('name', '=', Permission::SurfaceMeasurementTypeStore)->first()->roles->first()->users->first();
        $this->token = $this->user->createToken('TestToken')->plainTextToken;

        $this->apiUtils = new ApiUtils;
        $this->headers = $this->apiUtils->generateHttpHeaders($this->token);
    }

    public function test_surface_measurement_type_was_created_succesfully()
    {
        $newMeasurementType = [
            'name' => Str::random(50),
            'short_name' => Str::random(20),
        ];
        $response = $this->postJson($this->route, $newMeasurementType, $this->headers);
        $response->assertStatus(201);
    }

    public function test_new_surface_measurement_type_already_exists()
    {
        $alreadyExistent = SurfaceMeasurementType::inRandomOrder()->first()->toArray();
        $response = $this->postJson($this->route, $alreadyExistent, $this->headers);
        $response->assertStatus(422);
    }
}
