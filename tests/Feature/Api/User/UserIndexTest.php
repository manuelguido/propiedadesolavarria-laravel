<?php

namespace Tests\Feature\Api;

use App\Models\Administrator;
use Tests\Feature\Api\ApiUtils;
use Tests\TestCase;

class UserIndexTest extends TestCase
{
    private $user;
    private $token;
    private $headers;
    private $apiUtils;

    protected function setUp(): void
    {
        parent::setUp();
        $this->apiUtils = new ApiUtils;
        $this->user = Administrator::first()->user;
        $this->token = $this->user->createToken('TestToken')->plainTextToken;
        $this->headers = $this->apiUtils->generateHttpHeaders($this->token);
    }

    public function test_administrator_index_returns_json()
    {
        $response = $this->withHeaders($this->headers)->get($this->formatRoute('administrator'));
        $this->assertJson($response->getContent());
    }

    public function test_client_index_returns_json()
    {
        $response = $this->withHeaders($this->headers)->get($this->formatRoute('client'));
        $this->assertJson($response->getContent());
    }

    public function test_moderator_index_returns_json()
    {
        $response = $this->withHeaders($this->headers)->get($this->formatRoute('moderator'));
        $this->assertJson($response->getContent());
    }

    public function test_renter_index_returns_json()
    {
        $response = $this->withHeaders($this->headers)->get($this->formatRoute('renter'));
        $this->assertJson($response->getContent());
    }

    public function test_staff_index_returns_json()
    {
        $response = $this->withHeaders($this->headers)->get($this->formatRoute('staff'));
        $this->assertJson($response->getContent());
    }

    private function formatRoute($userName)
    {
        return '/api/v1/' . $userName . '/index';
    }
}
