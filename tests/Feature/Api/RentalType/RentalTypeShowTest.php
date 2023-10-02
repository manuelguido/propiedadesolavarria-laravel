<?php

namespace Tests\Feature\Auth;

use App\Models\RentalType;
use App\Models\User;
use Tests\Feature\Api\ApiUtils;
use Tests\TestCase;

class RentalTypeShowTest extends TestCase
{
    private $user;
    private $token;
    private $headers;
    private $route;
    private $apiUtils;
    private $expectedRentalType;

    protected function setUp(): void
    {
        parent::setUp();
        $this->apiUtils = new ApiUtils;
        $this->user = User::first();
        $this->expectedRentalType = RentalType::inRandomOrder()->first();
        $this->token = $this->user->createToken('TestToken')->plainTextToken;

        $this->headers = $this->apiUtils->generateHttpHeaders($this->token);
        $this->route = '/api/v1/rental-type/show/' . $this->expectedRentalType->rental_type_id;
    }

    public function test_rental_type_show_returns_json()
    {
        $response = $this->withHeaders($this->headers)->get($this->route);
        $this->assertJson($response->getContent());
    }

    public function test_rental_type_show_returns_correct_information()
    {
        $response = $this->withHeaders($this->headers)->get($this->route);
        $this->assertEmpty(array_diff($this->expectedRentalType->toArray(), $response->json()));
    }
}
