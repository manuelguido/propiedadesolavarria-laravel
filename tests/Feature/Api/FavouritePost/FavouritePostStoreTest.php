<?php

namespace Tests\Feature\Api;

use App\Models\FavouritePost;
use App\Models\Renter;
use App\Models\Role;
use Tests\Feature\Api\ApiUtils;
use Tests\TestCase;

class FavouritePostStoreTest extends TestCase
{
    private $apiUtils;
    private $user;
    private $authHeaders;
    private $route;
    private $posts;

    protected function setUp(): void
    {
        // Parent setup
        parent::setUp();

        // Api utilities
        $this->apiUtils = new ApiUtils;

        // Create client user that owns the favourite posts
        $this->user = $this->apiUtils->getAnyUserByRole(Role::RoleClient);

        // Authorized token and headers
        $this->authHeaders = $this->apiUtils->generateHttpHeaders(
            $this->user->createToken('TestToken')->plainTextToken
        );

        // Create renter, properties and posts to add as FavouritePosts
        $this->posts = $this->createPropertiesWithPostsForRenter($this->createRenter());

        // Route
        $this->route = '/api/v1/favourite-post/store';
    }

    private function createRenter()
    {
        $userData = $this->apiUtils->generateNewUserDataToRegister();
        $userData = $this->apiUtils->addRenterData($userData);
        return Renter::createRenter($userData);
    }

    private function createPropertiesWithPostsForRenter($renter)
    {
        $properties = $this->apiUtils->generatePropertiesForRenter($renter, 1);
        $posts = $this->apiUtils->generatePostsForProperties($properties, $renter);
        return $posts;
    }

    public function test_favourite_post_returns_json()
    {
        $randomPost = $this->posts[random_int(0, count($this->posts) - 1)];
        $formData = ['post_id' => $randomPost->post_id];

        $response = $this->postJson($this->route, $formData, $this->authHeaders);
        $this->assertJson($response->getContent());
    }

    public function test_favourite_post_returns_code_201()
    {
        $randomPost = $this->posts[random_int(0, count($this->posts) - 1)];
        $formData = ['post_id' => $randomPost->post_id];

        $response = $this->postJson($this->route, $formData, $this->authHeaders);
        $response->assertStatus(201);
    }

    public function test_favourite_post_is_created_succesfully()
    {
        $randomPost = $this->posts[random_int(0, count($this->posts) - 1)];
        $formData = ['post_id' => $randomPost->post_id];

        $this->postJson($this->route, $formData, $this->authHeaders);

        $query = FavouritePost::where([
            ['post_id', '=', $formData['post_id']],
            ['client_id', '=', $this->user->client->client_id],
        ])->first();

        $this->assertNotNull($query);
    }

    public function test_favourite_post_cant_be_added_twice()
    {
        $randomPost = $this->posts[random_int(0, count($this->posts) - 1)];
        $formData = ['post_id' => $randomPost->post_id];

        // Verify response code is 401
        $response = $this->postJson($this->route, $formData, $this->authHeaders);
        $responseTwice = $this->postJson($this->route, $formData, $this->authHeaders);
        $responseTwice->assertStatus(401);

        // Verify there is only one element on DB
        $query = FavouritePost::where([
            ['post_id', '=', $formData['post_id']],
            ['client_id', '=', $this->user->client->client_id],
        ])->get();
        $this->assertEquals(count($query), 1);
    }
}
