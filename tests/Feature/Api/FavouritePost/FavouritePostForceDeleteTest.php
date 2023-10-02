<?php

namespace Tests\Feature\Api;

use App\Models\FavouritePost;
use App\Models\Permission;
use App\Models\Renter;
use Tests\Feature\Api\ApiUtils;
use Tests\TestCase;

class FavouritePostForceDeleteTest extends TestCase
{
    private $apiUtils;
    private $user;
    private $authHeaders;
    private $route;
    private $expectedFavourites;

    protected function setUp(): void
    {
        // Parent setup
        parent::setUp();

        // Api utilities
        $this->apiUtils = new ApiUtils;

        // Create client user that owns the favourite posts
        $this->user = $this->apiUtils->getAnyUserByPermission(Permission::FavouritePostStore);

        // Authorized token and headers
        $this->authHeaders = $this->apiUtils->generateHttpHeaders(
            $this->user->createToken('TestToken')->plainTextToken
        );

        $this->expectedFavourites = FavouritePost::where('client_id', '=', $this->user->client->client_id)->get();

        // Create renter, properties and posts to add as FavouritePosts
        $posts = $this->createPropertiesWithPostsForRenter($this->createRenter());

        // Create favourite posts
        $this->createFavouritePosts($posts);

        // Get favourite posts
        $favouritePosts = $this->user->client->favourite_posts;
        $favouritePostToDelete = $favouritePosts->random();

        // Route
        $this->route = '/api/v1/favourite-post/delete/force/' . $favouritePostToDelete->favourite_post_id;
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

    public function test_favourite_post_force_delete_returns_json()
    {
        $response = $this->deleteJson($this->route, [], $this->authHeaders);
        $this->assertJson($response->getContent());
    }

    public function test_favourite_post_delete_returns_200_code()
    {
        $response = $this->deleteJson($this->route, [], $this->authHeaders);
        $response->assertStatus(200);
    }
}
