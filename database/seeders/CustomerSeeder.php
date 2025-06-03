<?php

namespace Database\Seeders;

use App\Enums\RoleEnum;
use App\Models\Customer;
use Illuminate\Database\Seeder;

class CustomerSeeder extends Seeder
{
    public function run()
    {
        Customer::factory(4)->create()->each(function ($customer) {
            $customer->user->assignRole(RoleEnum::CUSTOMER->value);
        });
    }
}
