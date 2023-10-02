<?php

namespace Tests;

use App\Models\Administrator;
use App\Models\Client;
use App\Models\Moderator;
use App\Models\Renter;
use App\Models\Role;
use App\Models\Staff;
use App\Models\User;
use Faker\Factory as Faker;
use Illuminate\Support\Str;

class UtilsForTests
{
    /**
     * Generate information for a Renter Model.
     * @param int user_id
     * @return \App\Models\Renter;
     */
    public function generateRenterData($user_id)
    {
        $faker = Faker::create();
        $renter = new Renter();
        $renter->fill([
            'user_id' => $user_id,
            'phone' => random_int(10000000, 999999999999),
            'whatsapp_phone' => random_int(10000000, 999999999999),
            'commercial_email' => $faker->unique()->safeEmail(),
            'address' => $faker->address(),
        ]);
        return $renter;
    }

    /**
     * Setup a user for every type of role.
     * @return array<array<User && string>>
     */
    public function setupCompleteUsers()
    {
        $users = [];
        // Administrator
        $role = Role::RoleAdministrator;
        $users[$role]['user'] = Administrator::createAdministrator(User::factory()->generateUserData($role))->user;
        $users[$role]['token'] = $this->generateToken($users, $role);

        // Client
        $role = Role::RoleClient;
        $users[$role]['user'] = Client::createClient(User::factory()->generateUserData($role))->user;
        $users[$role]['token'] = $this->generateToken($users, $role);

        // Moderator
        $role = Role::RoleModerator;
        $users[$role]['user'] = Moderator::createModerator(User::factory()->generateUserData($role))->user;
        $users[$role]['token'] = $this->generateToken($users, $role);

        // Staff
        $role = Role::RoleStaff;
        $users[$role]['user'] = Staff::createStaff(User::factory()->generateUserData($role))->user;
        $users[$role]['token'] = $this->generateToken($users, $role);

        // Renter
        $role = Role::RoleRenter;
        $newRenter = User::factory()->generateUserData($role);
        $newRenter[] = Renter::factory()->generateRandomData();
        $users[$role]['user'] = Renter::createRenter($newRenter)->user;
        $users[$role]['token'] = $this->generateToken($users, $role);
        return $users;
    }

    /**
     * Format API route according to version and route name.
     * @param string routeName
     * @param string version
     * @return string
     */
    public function formatApiRoute($routeName, $version = 'v1')
    {
        return '/api/' . $version . '/' . $routeName;
    }

    private function generateToken($users, $role)
    {
        return $users[$role]['user']->createToken('TestToken')->plainTextToken;
    }
}
