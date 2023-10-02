<?php

namespace Tests\Feature\Api;

use App\Models\Permission;
use App\Models\Post;
use App\Models\Property;
use App\Models\Role;
use Tests\Feature\Api\ApiUtils;
use Tests\TestCase;

class PostForceDeleteTest extends TestCase
{
    private $apiUtils;
    private $authHeaders;
    private $unauthHeaders;
    private $property;
    private $postToDelete;
    private $user;
    private $route;

    protected function setUp(): void
    {
        $this->refreshApplication();

        // Parent setup
        parent::setUp();

        // Api utilities
        $this->apiUtils = new ApiUtils;

        // Authorized token and headers
        $this->user = $this->apiUtils->getAnyUserByRole(Role::RoleRenter);
        $this->authHeaders = $this->apiUtils->generateHttpHeaders(
            $this->user->createToken('TestToken')->plainTextToken
        );

        // Unauthorized token and headers
        $unauthorizedToken = $this->apiUtils
            ->getAnyUserWithoutPermission(Permission::PropertyForceDelete)
            ->createToken('TestToken')->plainTextToken;
        $this->unauthHeaders = $this->apiUtils->generateHttpHeaders($unauthorizedToken);

        // Post to delete
        $this->property = Property::factory(1)->createPropertyForRenter($this->user->renter->renter_id);
        $this->postToDelete = Post::factory(1)->createPostForProperty($this->property);
        $this->postToDelete->delete(); // Because must be trashed before being permanently deleted

        // Route
        $this->route = '/api/v1/post/delete/force/' . $this->postToDelete->post_id;
    }


    public function test_post_delete_returns_json()
    {
        $response = $this->deleteJson($this->route, [], $this->authHeaders);
        $this->assertJson($response->getContent());
    }

    public function test_post_delete_returns_code_200()
    {
        $response = $this->deleteJson($this->route, [], $this->authHeaders);
        $response->assertStatus(200);
    }

    public function test_post_is_force_deleted_succesfully()
    {
        $this->deleteJson($this->route, [], $this->authHeaders);
        $result = Post::withTrashed()->find($this->postToDelete->post_id);
        $this->assertNull($result);

    }

    public function test_post_delete_returns_json_with_unathorized_user()
    {
        $response = $this->deleteJson($this->route, [], $this->unauthHeaders);
        $this->assertJson($response->getContent());
    }

    public function test_post_delete_returns_code_403_with_unathorized_user()
    {
        $response = $this->deleteJson($this->route, [], $this->unauthHeaders);
        $response->assertStatus(403);
    }
}
