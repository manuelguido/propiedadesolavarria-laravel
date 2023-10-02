<?php

namespace Database\Seeders;

use App\Models\Administrator;
use App\Models\Client;
use App\Models\Moderator;
use App\Models\Renter;
use App\Models\Role;
use App\Models\Staff;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Create 100 Users
        $users = \App\Models\User::factory(100)->create();

        // Create Administartor
        Administrator::create(['user_id' => $users[0]->user_id]);
        Role::assignRoleToUser($users[0]->user_id, Role::RoleAdministrator);

        // Create Staff
        Staff::create(['user_id' => $users[1]->user_id]);
        Role::assignRoleToUser($users[1]->user_id, Role::RoleStaff);

        // Create Modator
        Moderator::create(['user_id' => $users[2]->user_id]);
        Role::assignRoleToUser($users[2]->user_id, Role::RoleModerator);

        // Create 17 renters
        for ($i = 3; $i < 20; $i++) {
            // Data
            $data = Renter::factory()->generateRandomData();
            $data['user_id'] = $users[$i]->user_id;
            // Image
            $imageFile = Renter::factory()->generateRandomImage();
            $data['image'] = Str::random(32) . '.' . $imageFile->getClientOriginalExtension();
            $imageFile->storeAs('public/images/renters/', $data['image']);
            // Create
            Renter::create($data);
            Role::assignRoleToUser($users[$i]->user_id, Role::RoleRenter);
        }

        // Create 80 clients
        for ($i = 20; $i < 100; $i++) {
            Client::create(['user_id' => $users[$i]->user_id]);
            Role::assignRoleToUser($users[$i]->user_id, Role::RoleClient);
        }
    }
}
