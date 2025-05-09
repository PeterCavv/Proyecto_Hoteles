<?php

namespace Database\Factories;

use App\Models\Like;
use App\Models\Review;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class LikeFactory extends Factory
{
    protected $model = Like::class;

    public function definition()
    {
        return [
            'user_id' => User::factory()->create()->id,
            'review_id' => Review::factory()->create()->id,
            'liked_at' => Carbon::now(),
        ];
    }
}
