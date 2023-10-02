<?php

namespace Database\Factories;

use App\Models\Post;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Post>
 */
class PostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'title' => "Casa en microcentro",
            'value' => rand(10000, 100000),
            'expenses' => 0,
            'rental_type_id' => 1,
            'value_currency_id' => 1,
            'expenses_currency_id' => 1,
        ];
    }

    public function generateRandomData()
    {
        return [
            'title' => 'Nueva propiedad ' . Str::random(10),
            'value' => rand(10000, 100000),
            'expenses' => 0,
            'rental_type_id' => 1,
            'value_currency_id' => 1,
            'expenses_currency_id' => 1,
        ];
    }

    public function createPostForProperty($property)
    {
        $postData = $this->generateRandomData();
        $postData['property_id'] = $property->property_id;
        $postData['renter_id'] = $property->renter_id;
        return Post::create($postData);
    }
}
