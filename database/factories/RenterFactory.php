<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Renter>
 */
class RenterFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'user_id' => 1,
            'address' => Str::random(10),
            'commercial_email' => $this->faker->unique()->safeEmail(),
            'estate_agent' => 'Inmobiliara '.$this->faker->name,
            'phone' => $this->faker->randomNumber(9),
            'whatsapp_phone' => $this->faker->randomNumber(9),
        ];
    }

    public function generateRandomData()
    {
        $fileName = 'companyexample' . rand(1, 4) . '.png';
        $image = new UploadedFile('storage/app/' . $fileName, $fileName, 'image/jpeg', null, true);
        return [
            'address' => Str::random(10),
            'commercial_email' => $this->faker->unique()->safeEmail(),
            'image' => $image,
            'estate_agent' => 'Inmobiliara '.$this->faker->name,
            'phone' => $this->faker->randomNumber(9),
            'whatsapp_phone' => $this->faker->randomNumber(9),
        ];
    }

    public function generateRandomImage()
    {
        $fileName = 'companyexample' . rand(1, 4) . '.png';
        return new UploadedFile('storage/app/' . $fileName, $fileName, 'image/jpeg', null, true);
    }
}
