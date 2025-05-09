<?php

namespace Database\Factories;

use App\Models\Customer;
use App\Models\Follow;
use App\Models\Hotel;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class FollowFactory extends Factory
{
    protected $model = Follow::class;

    public function definition(): array
    {
        return [
            'hotel_id' => Hotel::factory()->create()->id,
            'customer_id' => Customer::factory()->create()->id,
            'followed_at' => Carbon::now(),
        ];
    }
}
