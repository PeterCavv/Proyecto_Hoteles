<?php

namespace Database\Factories;

use App\Models\Hotel;
use App\Models\Review;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class ReviewFactory extends Factory
{
    protected $model = Review::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory()->create()->id,
            'hotel_id' => Hotel::factory()->create()->id,
            'review' => $this->faker->text(200),
            'rating' => $this->faker->numberBetween(1, 5),
            'parent_id' => null,
            'published_at' => Carbon::now(),
        ];
    }
}
