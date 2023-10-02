<?php

namespace Tests\Feature\Api;

use App\Models\Moderator;
use App\Models\Permission;
use App\Models\User;
use App\Models\Role;
use Tests\Feature\Api\ApiUtils;
use Tests\TestCase;

class ModeratorDeleteTest extends TestCase
{
    private $apiUtils;
    private $authHeaders;
    private $unauthHeaders;
    private $moderatorToDelete;
    private $route;

    protected function setUp(): void
    {
        // Parent setup
        parent::setUp();

        // Api utilities
        $this->apiUtils = new ApiUtils;

        // Authorized token and headers
        $authorizedToken = $this->apiUtils
            ->getAnyUserByPermission(Permission::ModeratorDelete)
            ->createToken('TestToken')->plainTextToken;
        $this->authHeaders = $this->apiUtils->generateHttpHeaders($authorizedToken);

        // Unauthorized token and headers
        $unauthorizedToken = $this->apiUtils
            ->getAnyUserWithoutPermission(Permission::ModeratorDelete)
            ->createToken('TestToken')->plainTextToken;
        $this->unauthHeaders = $this->apiUtils->generateHttpHeaders($unauthorizedToken);

        // Moderator to delete
        $this->moderatorToDelete = Moderator::createModerator(
            User::factory()->generateUserData(Role::RoleModerator)
        );

        // Route
        $this->route = '/api/v1/moderator/delete/' . $this->moderatorToDelete->moderator_id;
    }

    public function test_moderator_delete_returns_json()
    {
        $response = $this->deleteJson($this->route, [], $this->authHeaders);
        $this->assertJson($response->getContent());
    }

    public function test_moderator_delete_returns_code_200()
    {
        $response = $this->deleteJson($this->route, [], $this->authHeaders);
        $response->assertStatus(200);
    }

    public function test_moderator_is_deleted_succesfully()
    {
        $response = $this->deleteJson($this->route, [], $this->authHeaders);
        $response->assertStatus(200);
        $this->assertSoftDeleted('Moderator', [
            'moderator_id' => $this->moderatorToDelete->moderator_id
        ]);
    }

    public function test_moderator_delete_returns_json_with_unathorized_user()
    {
        $response = $this->deleteJson($this->route, [], $this->unauthHeaders);
        $this->assertJson($response->getContent());
    }

    public function test_moderator_delete_returns_code_403_with_unathorized_user()
    {
        $response = $this->deleteJson($this->route, [], $this->unauthHeaders);
        $response->assertStatus(403);
    }
}
