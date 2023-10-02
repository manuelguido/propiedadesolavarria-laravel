<?php

namespace Tests\Feature\Api;

use App\Models\Property;
use App\Models\Role;
use Tests\Feature\Api\ApiUtils;
use Tests\TestCase;

class PropertyShowTest extends TestCase
{
    private $user;
    private $token;
    private $headers;
    private $route;
    private $apiUtils;
    private $users;
    private $property;

    protected function setUp(): void
    {
        // Parent
        parent::setUp();

        // Api utilities
        $this->apiUtils = new ApiUtils;

        // Roles
        $roles = [Role::RoleAdministrator, Role::RoleModerator, Role::RoleStaff, Role::RoleClient, Role::RoleRenter];
        foreach ($roles as $role) {
            $this->users[$role] = $this->apiUtils->getUserWithTokenByRole($role);
        }

        // Adds data to renter
        $auxRenter = $this->users[Role::RoleRenter]['user']->renter;
        $this->property = $this->apiUtils->generatePropertyForRenter($auxRenter);
        $this->apiUtils->generatePostForProperty($this->property, $auxRenter);

        // Route
        $this->route = '/api/v1/property/show/' . $this->property->property_id;
    }

    /*
    |--------------------------------------------------------------------------
    | Generic functions for testing
    |--------------------------------------------------------------------------
    */
    private function makeRequest($user, $route)
    {
        $headers = $this->apiUtils->generateHttpHeaders($user['token']);
        $response = $this->withHeaders($headers)->get($route);
        return $response;
    }

    private function expectedHTTPCode($user, $route, $expectedHTTPCode)
    {
        $response = $this->makeRequest($user, $route);
        $response->assertStatus($expectedHTTPCode);
    }

    private function expectedJson($user, $route)
    {
        $response = $this->makeRequest($user, $route);
        $this->assertJson($response->getContent());
    }

    private function expectedData($user, $route)
    {
        $response = $response = $this->makeRequest($user, $route);
        $expectedData = Property::find($this->property->property_id)->toArray();

        $this->assertEmpty(
            array_diff(
                $expectedData,
                $response->json()['data'],
            )
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Renter
    |--------------------------------------------------------------------------
    */
    public function test_renter_gets_json_response()
    {
        $this->expectedJson($this->users[Role::RoleRenter], $this->route);
    }

    public function test_renter_gets_200_code()
    {
        $this->expectedHTTPCode($this->users[Role::RoleRenter], $this->route, 200);
    }

    public function test_renter_gets_own_property()
    {
        $this->expectedData($this->users[Role::RoleRenter], $this->route);
    }

    /*
    |--------------------------------------------------------------------------
    | Moderator
    |--------------------------------------------------------------------------
    */
    public function test_moderator_gets_json_response()
    {
        $this->expectedJson($this->users[Role::RoleModerator], $this->route);
    }

    public function test_moderator_gets_200_code()
    {
        $this->expectedHTTPCode($this->users[Role::RoleModerator], $this->route, 200);
    }

    public function test_moderator_gets_all_properties()
    {
        $this->expectedData($this->users[Role::RoleModerator], $this->route);
    }

    /*
    |--------------------------------------------------------------------------
    | Administrator
    |--------------------------------------------------------------------------
    */
    public function test_administrator_gets_json_response()
    {
        $this->expectedJson($this->users[Role::RoleAdministrator], $this->route);
    }

    public function test_administrator_gets_200_code()
    {
        $this->expectedHTTPCode($this->users[Role::RoleAdministrator], $this->route, 200);
    }

    public function test_administrator_gets_all_properties()
    {
        $this->expectedData($this->users[Role::RoleAdministrator], $this->route);
    }

    /*
    |--------------------------------------------------------------------------
    | Staff
    |--------------------------------------------------------------------------
    */
    public function test_staff_gets_json_response()
    {
        $this->expectedJson($this->users[Role::RoleStaff], $this->route);
    }

    public function test_staff_gets_200_code()
    {
        $this->expectedHTTPCode($this->users[Role::RoleStaff], $this->route, 200);
    }

    public function test_staff_gets_all_properties()
    {
        $this->expectedData($this->users[Role::RoleStaff], $this->route);
    }

    /*
    |--------------------------------------------------------------------------
    | Client
    |--------------------------------------------------------------------------
    */
    public function test_client_gets_json_response()
    {
        $this->expectedJSON($this->users[Role::RoleClient], $this->route);

    }

    public function test_client_gets_403_code()
    {
        $this->expectedHTTPCode($this->users[Role::RoleClient], $this->route, 403);
    }
}
