<?php

namespace Tests\Feature\Auth;

use App\Models\Permission;
use App\Models\RentalType;
use Illuminate\Support\Str;
use Tests\Feature\Api\ApiUtils;
use Tests\TestCase;

class RentalTypeUpdateTest extends TestCase
{
    private $user;
    private $token;
    private $headers;
    private $route;
    private $apiUtils;
    private $newData;
    private $rentalTypeToUpdate;

    protected function setUp(): void
    {
        parent::setUp();
        $this->apiUtils = new ApiUtils;


        $this->rentalTypeToUpdate = RentalType::create(['name' => Str::random(50)]);

        $this->route = '/api/v1/rental-type/update/' . $this->rentalTypeToUpdate->rental_type_id;
        $this->user = $this->apiUtils->getAnyUserByPermission(Permission::RentalTypeUpdate);
        $this->token = $this->user->createToken('TestToken')->plainTextToken;

        $this->headers = $this->apiUtils->generateHttpHeaders($this->token);
        $this->newData = ['name' => Str::random((20)) . Str::random(20)];
    }

    protected function tearDown(): void
    {
        $rentalType = RentalType::where('name', '=', $this->newData['name'])->first();
        if ($rentalType) {
            $rentalType->forceDelete();
        }
    }

    public function test_rental_type_update_returns_json()
    {
        $response = $this->patchJson($this->route, $this->newData, $this->headers);
        $this->assertJson($response->getContent());
        RentalType::where('name', '=', $this->rentalTypeToUpdate->name)->forceDelete();
    }

    public function test_rental_type_was_updated_succesfully()
    {
        $response = $this->patchJson($this->route, $this->newData, $this->headers);
        $response->assertStatus(201);
        $this->assertEquals($this->newData['name'], $response->json()['rental_type']['name']);
        RentalType::where('name', '=', $this->rentalTypeToUpdate->name)->forceDelete();
    }

    public function test_rental_type_returns_442_when_data_is_missing()
    {
        $response = $this->patchJson($this->route, [], $this->headers);
        $response->assertStatus(422);
        RentalType::where('name', '=', $this->rentalTypeToUpdate->name)->forceDelete();
    }
}
