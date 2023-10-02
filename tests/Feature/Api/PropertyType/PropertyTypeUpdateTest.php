<?php

namespace Tests\Feature\Api;

use App\Models\Permission;
use App\Models\PropertyType;
use Illuminate\Support\Str;
use Tests\Feature\Api\ApiUtils;
use Tests\TestCase;

class PropertyTypeUpdateTest extends TestCase
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

        $this->user = $this->apiUtils->getAnyUserByPermission(Permission::PropertyTypeUpdate);
        $this->token = $this->user->createToken('TestToken')->plainTextToken;

        $this->headers = $this->apiUtils->generateHttpHeaders($this->token);
        $this->newData = ['name' => Str::random(50)];

        $idToUpdate = PropertyType::inRandomOrder()->first()->property_type_id;
        $this->route = '/api/v1/property-type/update/' . $idToUpdate;
    }

    public function test_property_type_update_returns_json()
    {
        $response = $this->patchJson($this->route, $this->newData, $this->headers);
        $this->assertJson($response->getContent());
    }

    public function test_property_type_was_updated_succesfully()
    {
        $response = $this->patchJson($this->route, $this->newData, $this->headers);
        $response->assertStatus(201);
        $this->assertEquals($this->newData['name'], $response->json()['property_type']['name']);
    }

    public function test_property_type_returns_442_when_data_is_missing()
    {
        $response = $this->patchJson($this->route, [], $this->headers);
        $response->assertStatus(422);
    }
}
