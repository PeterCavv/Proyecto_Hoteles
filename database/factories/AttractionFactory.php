<?php

namespace Database\Factories;

use App\Enums\AttractionType;
use App\Models\Attraction;
use App\Models\City;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class AttractionFactory extends Factory
{
    protected $model = Attraction::class;

    public function definition(): array
    {
        $types = AttractionType::cases();

        return [
            'name' => $this->faker->sentence(3),
            'description' => $this->faker->paragraph(),
            'type' => AttractionType::cases()[array_rand($types)],
            'city_id' => City::factory(),
        ];
    }
}
