<?php

namespace Tests\Feature\Api;

use App\Models\Currency;
use Tests\Feature\Api\ApiUtils;
use Tests\TestCase;

class CurrencyShowTest extends TestCase
{
    private $user;
    private $token;
    private $headers;
    private $route;
    private $apiUtils;
    private $expectedCurrency;

    protected function setUp(): void
    {
        parent::setUp();
        $this->apiUtils = new ApiUtils;
        $this->expectedCurrency = Currency::inRandomOrder()->first();

        $this->headers = $this->apiUtils->generateHttpHeaders($this->token);
        $this->route = '/api/v1/currency/show/' . $this->expectedCurrency->currency_id;
    }

    public function test_currency_show_returns_json()
    {
        $response = $this->withHeaders($this->headers)->get($this->route);
        $this->assertJson($response->getContent());
    }

    public function test_currency_show_returns_correct_information()
    {
        $response = $this->withHeaders($this->headers)->get($this->route);
        $this->assertEmpty(array_diff($this->expectedCurrency->toArray(), $response->json()));
    }
}
