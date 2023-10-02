<?php

namespace Tests\Feature\Api;

use App\Models\Permission;
use App\Models\Post;
use App\Models\Property;
use App\Models\PropertyImage;
use App\Models\Role;
use App\Models\User;
use Faker\Generator as Faker;
use Illuminate\Contracts\Database\Query\Builder;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

class ApiUtils
{
    private $faker;
    public function __construct()
    {
        $this->faker = new Faker;
    }

    /**
     * Get any existing user by a role name.
     *
     * @param  string $role
     * @return \App\Models\User
     */
    public function getAnyExistingUserByRole($roleName)
    {
        return User::whereHas('roles', function (Builder $query) use ($roleName) {
            $query->where('role.name', '=', $roleName);
        })->inRandomOrder()->first();
        // return Role::where('name', '=', $roleName)->first()->users->inRandomOrder()->first();
    }

    /**
     * Generate basic headers for api testing.
     *
     * @param  string $token
     * @return array<string>
     */
    public function generateHttpHeaders($token = null)
    {
        $headers = ['Accept' => 'application/json'];
        if ($token)
            $headers['Authorization'] = 'Bearer ' . $token;
        return $headers;
    }

    /**
     * Generate user data as array to create a new user.
     *
     * @return array
     */
    public function generateNewUserDataToRegister($password = 'password')
    {
        return [
            'name' => 'Nuevo ' . Str::random(15),
            'email' => Str::random(15) . '@' . Str::random(4) . 'com',
            'password' => $password,
            'password_confirmation' => $password,
        ];
    }

    /**
     * Gnerate data as array for a user with role Renter.
     *
     * @param  array<string>
     * @return array
     */
    public function addRenterData($userData)
    {
        $fileName = 'companyexample' . rand(1, 4) . '.png';

        $userData['address'] = Str::random(10);
        $userData['commercial_email'] = Str::random(15) . '@' . Str::random(4) . 'com';
        $userData['estate_agent'] = 'Inmobiliara asd';
        $userData['image'] = new UploadedFile('storage/app/' . $fileName, $fileName, 'image/jpeg', null, true);
        $userData['phone'] = $this->faker->randomNumber(9);
        $userData['whatsapp_phone'] = $this->faker->randomNumber(9);
        return $userData;
    }

    /**
     * Generate an array with one of each user type that
     * has a permission.
     *
     * @param  string $permissionName
     * @return array<\App\Models\User>
     */
    public function getOneOfEachUserByPermission($permissionName)
    {
        $users = [];
        $roles = Permission::where('name', '=', $permissionName)->first()->roles;
        foreach ($roles as $role) {
            array_push($users, $role->users()->first());
        }
        return $users;
    }

    /**
     * Get any existing user by a role name.
     *
     * @param  string $roleName
     * @return \App\Models\User
     */
    public function getAnyUserByRole($roleName)
    {
        return Role::where('name', '=', $roleName)->first()->users->random();
    }

    /**
     * Get any user by permission name.
     *
     * @param  string $permissionName
     * @return \App\Models\Permission
     */
    public function getAnyUserByPermission($permissionName)
    {
        return Permission::where('name', '=', $permissionName)->first()
            ->roles->first()
            ->users->random();
    }

    public function getUserWithTokenByRole($roleName)
    {
        $user['user'] = $this->getAnyUserByRole($roleName);
        $user['token'] = $user['user']->createToken('TestToken')->plainTextToken;
        return $user;
    }

    public function getAnyUserWithoutPermission($permissionName)
    {
        return User::whereDoesntHave('roles.permissions', function (Builder $query) use ($permissionName) {
            $query->where('permission.name', '=', $permissionName);
        })->inRandomOrder()->first();
    }

    /**
     * Generate a property for an existing renter.
     *
     * @param  \App\Models\Renter $renter
     * @param  integer $surface_measurement_type_id
     * @param  integer $property_type_id
     * @return \App\Models\Property
     */
    public function generatePropertyForRenter($renter, $surface_measurement_type_id = 1, $property_type_id = 1): Property
    {
        $property = Property::factory(1)->create([
            'renter_id' => $renter->renter_id,
            'surface_measurement_type_id' => $surface_measurement_type_id,
            'property_type_id' => $property_type_id
        ])->first();
        $this->generateImagesForProperty($property, 1);
        return $property;
    }

    /**
     * Generate properties for an existing renter.
     *
     * @param  \App\Models\Renter $renter
     * @param  integer $surface_measurement_type_id
     * @param  integer $property_type_id
     * @return \App\Models\Property collection
     */
    public function generatePropertiesForRenter($renter, $surface_measurement_type_id = 1, $property_type_id = 1)
    {
        $properties = Property::factory(3)->create([
            'renter_id' => $renter->renter_id,
            'surface_measurement_type_id' => $surface_measurement_type_id,
            'property_type_id' => $property_type_id
        ]);

        foreach ($properties as $property) {
            $this->generateImagesForProperty($property);
        }

        return $properties;
    }

    /**
     * Generate random posts for properties.
     *
     * @param  \App\Models\Property $property
     * @param  integer $renter_id
     * @param  integer $rental_type_id
     * @return \App\Models\Post
     */
    public function generatePostForProperty($property, $renter_id, $rental_type_id = 1)
    {
        return Post::factory()->create([
            'renter_id' => $renter_id,
            'rental_type_id' => $rental_type_id,
            'property_id' => $property->property_id,
        ]);
    }

    /**
     * Generate random posts for properties.
     *
     * @param  \App\Models\Property $property
     * @param  \App\Models\Renter $renter
     * @param  integer $rental_type_id
     */
    public function generatePostsForProperties($properties, $renter, $rental_type_id = 1)
    {
        $posts = [];
        foreach ($properties as $property) {
            $newPost = $this->generatePostForProperty($property, $renter->renter_id, $rental_type_id);
            array_push($posts, $newPost);
        }
        return $posts;
    }

    /**
     * Generate images for a existing property.
     *
     * @param  \App\Models\Property $property
     * @param  integer $imageCount
     * @return  void
     */
    private function generateImagesForProperty($property, $imageCount = 2)
    {
        for ($i = 1; $i < $imageCount; $i++) {
            PropertyImage::factory()->create([
                'name' => 'image0' . $i . '.jpg',
                'order' => $i,
                'property_id' => $property->property_id,
            ]);
        }
    }
}
