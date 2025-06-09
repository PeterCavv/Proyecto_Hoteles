<?php

namespace Database\Seeders;

use App\Models\Hotel;
use App\Models\Review;
use App\Models\User;
use Illuminate\Database\Seeder;

class ReviewSeeder extends Seeder
{
    public function run()
    {
        $users = User::all();
        $existingReviews = Review::all();

        if ($users->isEmpty()) {
            $this->command->warn('Se necesitan usuarios existentes para crear reviews.');
            return;
        }

        for ($i = 0; $i < 4; $i++) {
            $user = $users->random();

            $review = Review::factory()->create([
                'user_id' => $user->id,
                'hotel_id' => Hotel::inRandomOrder()->first()?->id,
                'parent_id' => $existingReviews->isNotEmpty() && rand(0, 1)
                    ? $existingReviews->random()->id
                    : null,
            ]);

            $existingReviews->push($review);
        }
    }
}
