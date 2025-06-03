<?php

namespace Database\Seeders;

use App\Enums\RoleEnum;
use App\Models\Hotel;
use Illuminate\Database\Seeder;

class HotelSeeder extends Seeder
{
    public function run()
    {
        Hotel::factory(4)->create()->each(function ($hotel) {
            $hotel->user->assignRole(RoleEnum::HOTEL->value);
        });
    }
}
