<?php

namespace Tests\Feature\Api;

use App\Models\Client;
use Illuminate\Support\Str;
use Tests\Feature\Api\ApiUtils;
use Tests\TestCase;

class ClientUpdateTest extends TestCase
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

        $this->user = Client::first()->user;
        $this->token = $this->user->createToken('TestToken')->plainTextToken;

        $this->headers = $this->apiUtils->generateHttpHeaders($this->token);

        $this->newData = ['name' => Str::random(200)];

        $this->route = '/api/v1/client/update/';
    }

    public function test_client_update_returns_json()
    {
        $response = $this->patchJson($this->route, $this->newData, $this->headers);
        $this->assertJson($response->getContent());
    }

    public function test_client_was_updated_succesfully()
    {
        $response = $this->patchJson($this->route, $this->newData, $this->headers);
        $response->assertStatus(201);
        $this->assertEquals($this->newData['name'], $response->json()['user']['name']);
    }

    public function test_client_returns_442_when_data_is_missing()
    {

        $response = $this->patchJson($this->route, [], $this->headers);
        $response->assertStatus(422);
    }
}
