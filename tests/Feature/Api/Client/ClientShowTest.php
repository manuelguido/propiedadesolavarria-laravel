<?php

namespace Tests\Feature\Api;

use App\Models\Administrator;
use App\Models\Client;
use Tests\Feature\Api\ApiUtils;
use Tests\TestCase;

class ClientShowTest extends TestCase
{
    private $user;
    private $token;
    private $headers;
    private $route;
    private $expectedUser;
    private $apiUtils;

    protected function setUp(): void
    {
        parent::setUp();
        $this->apiUtils = new ApiUtils;
        $this->user = Administrator::first()->user;
        $this->token = $this->user->createToken('TestToken')->plainTextToken;

        $this->headers = $this->apiUtils->generateHttpHeaders($this->token);
        $aux = Client::inRandomOrder()->first();
        $this->expectedUser = Client::findWithUser($aux->client_id);
    }

    public function test_client_show_returns_json()
    {
        $response = $this->withHeaders($this->headers)->get($this->formatRoute($this->expectedUser->client_id));
        $this->assertJson($response->getContent());
    }

    public function test_client_show_returns_correct_information()
    {
        $response = $this->withHeaders($this->headers)->get($this->formatRoute($this->expectedUser->client_id));
        $this->assertEmpty(array_diff($this->expectedUser->toArray(), $response->json()));
    }

    private function formatRoute($id)
    {
        return '/api/v1/client/show/' . $id;
    }
}
