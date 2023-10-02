<?php

namespace Tests\Feature\Auth;

use App\Models\Client;
use App\Models\User;
use Illuminate\Support\Str;
use Tests\Feature\Api\ApiUtils;
use Tests\TestCase;

class UserPasswordUpdateTest extends TestCase
{

    private $user;
    private $token;
    private $headers;
    private $route;
    private $apiUtils;

    protected function setUp(): void
    {
        parent::setUp();
        $this->apiUtils = new ApiUtils;

        $userData = $this->apiUtils->generateNewUserDataToRegister();
        $this->user = Client::createClient($userData)->user;
        $this->token = $this->user->createToken('TestToken')->plainTextToken;
        $this->headers = $this->apiUtils->generateHttpHeaders($this->token);

        $this->route = '/api/v1/user/password/update';
    }

    public function test_user_can_change_its_password()
    {
        $newPassword = 'password';
        $data = [
            'current_password' => 'password',
            'password' => $newPassword,
            'password_confirmation' => $newPassword,
        ];

        $response = $this->patchJson($this->route, $data, $this->headers);

        $response->assertStatus(201);
    }
}
