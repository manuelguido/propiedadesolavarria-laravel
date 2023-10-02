<?php

namespace Tests\Feature\Api;

use App\Models\Post;
use App\Models\Property;
use App\Models\Permission;
use Tests\Feature\Api\ApiUtils;
use Tests\TestCase;

class PostUpdateTest extends TestCase
{
    private $apiUtils;
    private $authHeaders;
    private $unauthHeaders;
    private $route;
    private $user;
    private $property;
    private $postToUpdate;

    protected function setUp(): void
    {
        // Parent setup
        parent::setUp();

        // Api utilities
        $this->apiUtils = new ApiUtils;

        // Authorized token and headers
        $this->user = $this->apiUtils->getAnyUserByPermission(Permission::PropertyStore);
        $this->authHeaders = $this->apiUtils->generateHttpHeaders(
            $this->user->createToken('TestToken')->plainTextToken
        );

        // Unauthorized token and headers
        $unauthorizedToken = $this->apiUtils
            ->getAnyUserWithoutPermission(Permission::PropertyUpdate)
            ->createToken('TestToken')->plainTextToken;
        $this->unauthHeaders = $this->apiUtils->generateHttpHeaders($unauthorizedToken);

        // Create a property for the renter
        $this->property = Property::factory(1)->createPropertyForRenter($this->user->renter->renter_id);
        $this->postToUpdate = Post::factory(1)->createPostForProperty($this->property);

        // Route
        $this->route = '/api/v1/post/update/' . $this->postToUpdate->post_id;


    }

    private function generateFormData()
    {
        $formData = Post::factory(1)->generateRandomData();
        $formData['property_id'] = $this->property->property_id;
        $formData['renter_id'] = $this->user->renter->renter_id;
        return $formData;
    }

    public function test_post_update_returns_json()
    {
        $formData = $this->generateFormData();
        $response = $this->patchJson($this->route, $formData, $this->authHeaders);
        $this->assertJson($response->getContent());
    }

    public function test_post_update_returns_code_201()
    {
        $formData = $this->generateFormData();
        $response = $this->patchJson($this->route, $formData, $this->authHeaders);
        $response->assertStatus(201);
    }

    public function test_post_update_returns_correct_information()
    {
        $formData = $this->generateFormData();
        $response = $this->patchJson($this->route, $formData, $this->authHeaders);
        $responseData = $response->json()['data'];

        // $this->assertEquals($this->user->renter->renter_id, $responseData['renter_id']);
        $this->assertEquals($formData['title'], $responseData['title']);
        $this->assertEquals($formData['value'], $responseData['value']);
        $this->assertEquals($formData['expenses'], $responseData['expenses']);
        $this->assertEquals($formData['property_id'], $responseData['property_id']);
        $this->assertEquals($formData['rental_type_id'], $responseData['rental_type_id']);
        $this->assertEquals($formData['value_currency_id'], $responseData['value_currency_id']);
        $this->assertEquals($formData['expenses_currency_id'], $responseData['expenses_currency_id']);
    }

    public function test_post_update_returns_json_with_unathorized_user()
    {
        $formData = $this->generateFormData();
        $response = $this->patchJson($this->route, $formData, $this->unauthHeaders);
        $this->assertJson($response->getContent());
    }

    public function test_post_update_returns_code_403_with_unathorized_user()
    {
        $formData = $this->generateFormData();
        $response = $this->patchJson($this->route, $formData, $this->unauthHeaders);
        $response->assertStatus(403);
    }
}
