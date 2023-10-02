<?php

namespace Tests\Feature\Api;

use App\Models\FavouritePost;
use App\Models\Renter;
use App\Models\Role;
use Tests\Feature\Api\ApiUtils;
use Tests\TestCase;

class FavouritePostIndexTest extends TestCase
{
    private $apiUtils;
    private $authHeaders;
    private $user;
    private $expectedFavourites;
    private $route;

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
        $posts = $this->createPropertiesWithPostsForRenter($this->createRenter());

        // Create favourite posts
        $this->createFavouritePosts($posts);

        // Get favourite posts
        $this->expectedFavourites = FavouritePost::where('client_id', '=', $this->user->client->client_id)->get();

        // Route
        $this->route = '/api/v1/favourite-post/index';
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

    private function createFavouritePosts($posts)
    {
        foreach ($posts as $post) {
            FavouritePost::create([
                'client_id' => $this->user->client->client_id,
                'post_id' => $post->post_id
            ]);
        }
    }

    public function test_favourite_post_index_returns_json()
    {
        $response = $this->withHeaders($this->authHeaders)->get($this->route);
        $this->assertJson($response->getContent());
    }

    public function test_favourite_post_index_returns_code_200()
    {
        $response = $this->withHeaders($this->authHeaders)->get($this->route);
        $this->assertJson($response->getContent());
    }

    public function test_favourite_post_index_returns_code_correct_amount_of_posts()
    {
        $response = $this->withHeaders($this->authHeaders)->get($this->route);
        $this->assertEquals(
            count($response->json()['data']),
            count($this->expectedFavourites->toArray())
        );
    }
}
