<?php

namespace Database\Factories;

use App\Models\Customer;
use App\Models\Hotel;
use App\Models\Reservation;
use App\Models\RoomType;
use Illuminate\Database\Eloquent\Factories\Factory;

class ReservationFactory extends Factory
{
    protected $model = Reservation::class;

    public function definition()
    {
        $checkIn = $this->faker->dateTimeBetween('now', '+1 month');
        $checkOut = $this->faker->dateTimeBetween($checkIn, $checkIn->format('Y-m-d').' +7 days');

        return [
            'hotel_id' => Hotel::factory()->create()->id,
            'customer_id' => Customer::factory()->create()->id,
            'check_in' => $checkIn,
            'check_out' => $checkOut,
            'adults' => $this->faker->numberBetween(1, 4),
            'children' => $this->faker->numberBetween(0, 3),
            'room_id' => RoomType::factory()->create()->id,
            'price' => $this->faker->randomFloat(2, 50, 500),
        ];
    }
}
