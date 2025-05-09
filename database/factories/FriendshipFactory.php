<?php

namespace Database\Factories;

use App\Enums\FriendshipStatus;
use App\Models\Friendship;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class FriendshipFactory extends Factory
{
    protected $model = Friendship::class;

    public function definition(): array
    {

        $statuses = FriendshipStatus::cases();

        // Verifica que no esté vacío
        if (empty($statuses)) {
            dd('Enum vacío'); // Debug: esto NO debería ejecutarse
        }

        return [
            'user_1_id' => User::factory()->create()->id,
            'user_2_id' => User::factory()->create()->id,
            'status' => FriendshipStatus::cases()[array_rand(FriendshipStatus::cases())],
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}
