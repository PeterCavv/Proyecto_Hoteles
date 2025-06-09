<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\Hotel;
use App\Models\Reservation;
use Illuminate\Database\Seeder;

class ReservationSeeder extends Seeder
{
    public function run()
    {
        $customers = Customer::all();
        $hotels = Hotel::all();

        if ($customers->isEmpty() || $hotels->isEmpty()) {
            $this->command->warn('Se necesitan al menos un hotel y un cliente para crear reservas.');
            return;
        }

        for ($i = 0; $i < 4; $i++) {
            Reservation::factory()->create([
                'customer_id' => $customers->random()->id,
                'hotel_id' => $hotels->random()->id,
            ]);
        }
    }
}
