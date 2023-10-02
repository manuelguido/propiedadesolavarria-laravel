<?php

namespace Tests\Feature\Api;

use App\Models\Permission;
use App\Models\RentalType;
use Illuminate\Support\Str;
use Tests\Feature\Api\ApiUtils;
use Tests\TestCase;

class RentalTypeCreateTest extends TestCase
{
    private $user;
    private $token;
    private $headers;
    private $route;
    private $apiUtils;

    protected function setUp(): void
    {
        parent::setUp();
        $this->route = '/api/v1/rental-type/store';
        $this->user = Permission::where('name', '=', Permission::RentalTypeStore)->first()->roles->first()->users->first();
        $this->token = $this->user->createToken('TestToken')->plainTextToken;

        $this->apiUtils = new ApiUtils;
        $this->headers = $this->apiUtils->generateHttpHeaders($this->token);
    }

    public function test_rental_type_was_created_succesfully()
    {
        $newRentalType = ['name' => Str::random(20) . Str::random(20)];
        $response = $this->postJson($this->route, $newRentalType, $this->headers);
        $response->assertStatus(201);

        RentalType::where('name', '=', $newRentalType['name'])->first()->forceDelete();
    }

    public function test_new_rental_type_already_exists()
    {
        $alreadyExistent = RentalType::first()->toArray();
        $response = $this->postJson($this->route, $alreadyExistent, $this->headers);
        $response->assertStatus(422);
    }
}
