<?php

namespace Database\Seeders;
use App\Enums\RoleEnum;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        foreach (RoleEnum::cases() as $roleEnum) {
            Role::firstOrCreate(['name' => $roleEnum->value]);
        }

        $this->call([
            RoleSeeder::class,
            UserSeeder::class,
            CustomerSeeder::class,
            HotelSeeder::class,
            ReservationSeeder::class,
            FriendshipSeeder::class,
            RoomTypeSeeder::class,
            ReviewSeeder::class,
        ]);
    }
}
