<?php

namespace Tests\Feature\Api;

use App\Models\Post;
use App\Models\Role;
use Tests\Feature\Api\ApiUtils;
use Tests\TestCase;

class PostShowTest extends TestCase
{
    private $user;
    private $token;
    private $headers;
    private $route;
    private $apiUtils;
    private $users;
    private $post;

    protected function setUp(): void
    {
        // Parent
        parent::setUp();

        // Api utilities
        $this->apiUtils = new ApiUtils;

        // Adds data to renter
        $auxRenter = $this->apiUtils->getAnyExistingUserByRole(Role::RoleRenter)->renter;
        $this->post = $this->apiUtils->generatePostForProperty(
            $this->apiUtils->generatePropertyForRenter($auxRenter),
            $auxRenter
        );

        // Route
        $this->route = '/api/v1/post/show/' . $this->post->post_id;
    }

    public function test_post_show_returns_json_response()
    {
        $response = $this->withHeaders($this->apiUtils->generateHttpHeaders())->get($this->route);
        $this->assertJson($response->getContent());

    }

    public function test_post_show_returns_200_code()
    {
        $response = $this->withHeaders($this->apiUtils->generateHttpHeaders())->get($this->route);
        $response->assertStatus(200);
    }
}
