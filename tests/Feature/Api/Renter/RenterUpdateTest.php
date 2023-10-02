<?php

namespace Tests\Feature\Api;

use App\Models\Administrator;
use App\Models\Renter;
use Faker\Factory as Faker;
use Tests\Feature\Api\ApiUtils;
use Tests\TestCase;


class RenterUpdateTest extends TestCase
{
    private $user;
    private $token;
    private $headers;
    private $route;
    private $apiUtils;
    private $newData;
    private $faker;

    protected function setUp(): void
    {
        parent::setUp();
        $this->apiUtils = new ApiUtils;

        $renterToUpdateId = Renter::inRandomOrder()->first()->renter_id;
        $this->route = '/api/v1/renter/update/' . $renterToUpdateId;

        $this->user = Administrator::first()->user;
        $this->token = $this->user->createToken('TestToken')->plainTextToken;

        $this->headers = $this->apiUtils->generateHttpHeaders($this->token);
        $this->faker = Faker::create();

        $this->newData = [
            'name' => $this->faker->name(),
            'phone' => $this->faker->numberBetween(1111111111, 9999999999),
            'whatsapp_phone' => $this->faker->numberBetween(1111111111, 9999999999),
            'commercial_email' => $this->faker->unique()->safeEmail(),
            'address' => $this->faker->address(),
        ];
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        gc_collect_cycles();
    }

    public function test_renter_update_returns_json()
    {
        $response = $this->patchJson($this->route, $this->newData, $this->headers);
        $this->assertJson($response->getContent());
    }

    public function test_renter_was_updated_succesfully()
    {
        $response = $this->patchJson($this->route, $this->newData, $this->headers);
        $response->assertStatus(200);
        $this->assertEquals($this->newData['name'], $response->json()['data']['name']);
        $this->assertEquals($this->newData['phone'], $response->json()['data']['phone']);
        $this->assertEquals($this->newData['whatsapp_phone'], $response->json()['data']['whatsapp_phone']);
        $this->assertEquals($this->newData['commercial_email'], $response->json()['data']['commercial_email']);
        $this->assertEquals($this->newData['address'], $response->json()['data']['address']);

    }

    public function test_renter_returns_442_when_data_is_missing()
    {
        $response = $this->patchJson($this->route, [], $this->headers);
        $response->assertStatus(422);
    }
}
