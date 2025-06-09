<?php

namespace Database\Seeders;

use App\Enums\RoleEnum;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        if ($this->isDataAlreadyGiven()) {
            return;
        }

        Role::create(['name' => RoleEnum::ADMIN->value]);
        Role::create(['name' => RoleEnum::CUSTOMER->value]);
        Role::create(['name' => RoleEnum::HOTEL->value]);
    }

    private function isDataAlreadyGiven(): bool
    {
        return Role::where('name', RoleEnum::ADMIN->value)->exists()
            && Role::where('name', RoleEnum::CUSTOMER->value)->exists()
            && Role::where('name', RoleEnum::HOTEL->value)->exists();
    }
}
