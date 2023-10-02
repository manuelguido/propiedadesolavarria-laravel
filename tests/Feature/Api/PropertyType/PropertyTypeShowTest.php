<?php

namespace Tests\Feature\Api;

use App\Models\PropertyType;
use Tests\Feature\Api\ApiUtils;
use Tests\TestCase;

class PropertyTypeShowTest extends TestCase
{
    private $headers;
    private $route;
    private $apiUtils;
    private $expectedPropertyType;

    protected function setUp(): void
    {
        parent::setUp();
        $this->apiUtils = new ApiUtils;

        $this->expectedPropertyType = PropertyType::inRandomOrder()->first();

        $this->headers = $this->apiUtils->generateHttpHeaders();
        $this->route = '/api/v1/property-type/show/' . $this->expectedPropertyType->property_type_id;
    }

    public function test_surface_measurement_type_show_returns_json()
    {
        $response = $this->withHeaders($this->headers)->get($this->route);
        $this->assertJson($response->getContent());
    }

    public function test_surface_measurement_type_show_returns_correct_information()
    {
        $response = $this->withHeaders($this->headers)->get($this->route);
        $this->assertEmpty(array_diff($this->expectedPropertyType->toArray(), $response->json()));
    }
}
