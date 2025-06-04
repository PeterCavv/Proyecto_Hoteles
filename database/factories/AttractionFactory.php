<?php

namespace Database\Factories;

use App\Models\Attraction;
use App\Models\City;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class AttractionFactory extends Factory
{
    protected $model = Attraction::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->sentence(3),
            'description' => $this->faker->paragraph(),
            'city_id' => City::factory(),
        ];
    }
}
