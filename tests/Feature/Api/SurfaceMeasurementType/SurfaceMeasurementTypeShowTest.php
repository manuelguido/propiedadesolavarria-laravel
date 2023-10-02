<?php

namespace Tests\Feature\Api;

use App\Models\SurfaceMeasurementType;
use App\Models\User;
use Tests\Feature\Api\ApiUtils;
use Tests\TestCase;

class SurfaceMeasurementTypeShowTest extends TestCase
{
    private $user;
    private $token;
    private $headers;
    private $route;
    private $apiUtils;
    private $expectedMeasurementType;

    protected function setUp(): void
    {
        parent::setUp();
        $this->apiUtils = new ApiUtils;
        $this->user = User::first();
        $this->token = $this->user->createToken('TestToken')->plainTextToken;

        $this->expectedMeasurementType = SurfaceMeasurementType::inRandomOrder()->first();

        $this->headers = $this->apiUtils->generateHttpHeaders($this->token);
        $this->route = '/api/v1/surface-measurement-type/show/' . $this->expectedMeasurementType->surface_measurement_type_id;
    }

    public function test_surface_measurement_type_show_returns_json()
    {
        $response = $this->withHeaders($this->headers)->get($this->route);
        $this->assertJson($response->getContent());
    }

    public function test_surface_measurement_type_show_returns_correct_information()
    {
        $response = $this->withHeaders($this->headers)->get($this->route);
        $this->assertEmpty(array_diff($this->expectedMeasurementType->toArray(), $response->json()));
    }
}
