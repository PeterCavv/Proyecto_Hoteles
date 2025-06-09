<?php

namespace Database\Seeders;

use App\Enums\FriendshipStatus;
use App\Models\Friendship;
use App\Models\User;
use Illuminate\Database\Seeder;

class FriendshipSeeder extends Seeder
{
    public function run()
    {
        $users = User::all();

        if ($users->count() < 2) {
            $this->command->warn('Se necesitan al menos 2 usuarios para crear amistades.');
            return;
        }

        for ($i = 0; $i < 4; $i++) {
            do {
                $user1 = $users->random();
                $user2 = $users->random();
            } while ($user1->id === $user2->id || Friendship::where([
                ['user_1_id', '=', $user1->id],
                ['user_2_id', '=', $user2->id],
            ])->orWhere(function ($query) use ($user1, $user2) {
                $query->where('user_1_id', $user2->id)
                    ->where('user_2_id', $user1->id);
            })->exists());

            Friendship::create([
                'user_1_id' => $user1->id,
                'user_2_id' => $user2->id,
                'status' => FriendshipStatus::PENDING->value,
            ]);
        }
    }
}
