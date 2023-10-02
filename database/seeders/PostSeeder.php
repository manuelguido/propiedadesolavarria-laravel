<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\Renter;
use Illuminate\Database\Seeder;

class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $renters = Renter::all();

        foreach ($renters as $renterModel) {
            foreach ($renterModel->properties as $propertyModel) {
                Post::factory()->create([
                    'renter_id' => $renterModel->renter_id,
                    'rental_type_id' => rand(1, 2),
                    'property_id' => $propertyModel->property_id,
                ]);
            }
        }

        $posts = Post::inRandomOrder()->limit(8)->get();
        foreach ($posts as $post) {
            $post->featured = true;
            $post->save();
        }
    }
}
