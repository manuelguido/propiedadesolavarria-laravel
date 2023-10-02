<?php

namespace Tests\Feature\Api;

use App\Models\Permission;
use App\Models\PropertyType;
use Illuminate\Support\Str;
use Tests\Feature\Api\ApiUtils;
use Tests\TestCase;

class PropertyTypeStoreTest extends TestCase
{
    private $user;
    private $token;
    private $headers;
    private $route;
    private $apiUtils;

    protected function setUp(): void
    {
        parent::setUp();
        $this->apiUtils = new ApiUtils;

        $this->user = $this->apiUtils->getAnyUserByPermission(Permission::PropertyTypeStore);
        $this->token = $this->user->createToken('TestToken')->plainTextToken;
        $this->headers = $this->apiUtils->generateHttpHeaders($this->token);
        $this->route = '/api/v1/property-type/store';
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
        $alreadyExistent = PropertyType::inRandomOrder()->first()->toArray();
        $response = $this->postJson($this->route, $alreadyExistent, $this->headers);
        $response->assertStatus(422);
    }
}
