<?php

namespace Database\Factories;

use App\Models\Customer;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class CustomerFactory extends Factory
{
    protected $model = Customer::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory()->create()->id,
            'name' => $this->faker->firstName,
            'last_name' => $this->faker->lastName,
            'dni' => $this->faker->unique()->numerify('########'),
            'updated_at' => Carbon::now(),
        ];
    }
}
