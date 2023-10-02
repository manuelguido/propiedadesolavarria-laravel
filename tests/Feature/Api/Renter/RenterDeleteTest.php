<?php

namespace Tests\Feature\Api;

use App\Models\Administrator;
use App\Models\Post;
use App\Models\Property;
use App\Models\PropertyImage;
use App\Models\Renter;
use App\Models\User;
use App\Models\Role;
use Tests\Feature\Api\ApiUtils;
use Tests\TestCase;

class RenterDeleteTest extends TestCase
{
    private $user;
    private $token;
    private $headers;
    private $apiUtils;
    private $renterToDelete;

    protected function setUp(): void
    {
        parent::setUp();
        $this->apiUtils = new ApiUtils;

        $this->renterToDelete = Renter::createRenter(User::factory()->generateUserData(Role::RoleRenter));

        $this->user = Administrator::first()->user;
        $this->token = $this->user->createToken('TestToken')->plainTextToken;

        $this->headers = $this->apiUtils->generateHttpHeaders($this->token);

        $properties = $this->apiUtils->generatePropertiesForRenter($this->renterToDelete);
        $this->apiUtils->generatePostsForProperties($properties, $this->renterToDelete);
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        gc_collect_cycles();
    }

    private function route()
    {
        return '/api/v1/renter/delete/' . $this->renterToDelete->renter_id;
    }

    public function test_renter_delete_response_is_200()
    {
        $response = $this->deleteJson($this->route(), [], $this->headers);
        $response->assertStatus(200);
    }

    public function test_renter_delete_returns_json()
    {
        $response = $this->deleteJson($this->route(), [], $this->headers);
        $this->assertJson($response->getContent());
    }

    public function test_renter_was_deleted_succesfully()
    {
        $this->deleteJson($this->route(), [], $this->headers);
        $this->assertSoftDeleted('renter', [
            'renter_id' => $this->renterToDelete->renter_id
        ]);
    }

    public function test_renter_has_no_active_properties()
    {
        $this->deleteJson($this->route(), [], $this->headers);

        $this->assertCount(0, Property::where('renter_id', '=', $this->renterToDelete->renter_id)->get());
        $this->assertCount(0, $this->renterToDelete->properties);
    }

    public function test_renter_has_no_active_posts()
    {
        $this->deleteJson($this->route(), [], $this->headers);

        $this->assertCount(0, Post::where('renter_id', '=', $this->renterToDelete->renter_id)->get());
        $this->assertCount(0, $this->renterToDelete->posts);
    }

    public function test_renter_properties_have_no_active_images()
    {
        $this->deleteJson($this->route(), [], $this->headers);

        $this->assertCount(
            0,
            PropertyImage::where('renter_id', '=', $this->renterToDelete->renter_id)
                ->join('property', 'property.property_id', '=', 'property_image.property_id')
                ->get()
        );
        $this->assertCount(0, $this->renterToDelete->posts);
    }
}
