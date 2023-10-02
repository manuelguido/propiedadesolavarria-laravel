<?php

namespace Database\Factories;

use App\Models\Renter;
use App\Models\Role;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'name' => $this->faker->name(),
            'email' => Str::random(10) . $this->faker->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
            'remember_token' => Str::random(10),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     *
     * @return static
     */
    public function unverified()
    {
        return $this->state(function (array $attributes) {
            return [
                'email_verified_at' => null,
            ];
        });
    }

    /**
     * Generate user data to save throught other methods.
     *
     * @return array<string, mixed>
     */
    public function generateUserData($role = null)
    {
        $baseData = [
            'name' => $this->faker->name(),
            'email' => Str::random(10) . $this->faker->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => 'password',
            'password_confirmation' => 'password',
            'remember_token' => Str::random(10),
        ];

        switch ($role) {
            case Role::RoleRenter:
                $baseData = array_merge($baseData, Renter::factory()->generateRandomData());
                break;
        }

        return $baseData;
    }

    private function addRenterData($userData)
    {
        $userData['phone'] = $this->faker->randomNumber(9);
        $userData['whatsapp_phone'] = $this->faker->randomNumber(9);
        $userData['commercial_email'] = Str::random(15) . '@' . Str::random(4) . 'com';
        $userData['address'] = Str::random(10);
        return $userData;
    }
}
