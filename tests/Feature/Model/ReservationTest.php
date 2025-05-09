<?php

use App\Models\Customer;
use App\Models\Follow;
use App\Models\Hotel;
use App\Models\Reservation;
use App\Models\RoomType;
use app\Models\User;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

uses(RefreshDatabase::class);

it('belongs to a customer', function () {
    $customer = Customer::factory()->create();
    $reservation = Reservation::factory()->create(['customer_id' => $customer->id]);

    expect($reservation->customer)->toBeInstanceOf(Customer::class)
        ->and($reservation->customer->id)->toBe($customer->id);
})->uses(TestCase::class);

it('belongs to a room', function () {
    $room = RoomType::factory()->create();
    $reservation = Reservation::factory()->create(['room_type_id' => $room->id]);

    expect($reservation->roomType)->toBeInstanceOf(RoomType::class)
        ->and($reservation->roomType->id)->toBe($room->id);
})->uses(TestCase::class);

it('belongs to a hotel', function () {
    $hotel = Hotel::factory()->create();
    $reservation = Reservation::factory()->create(['hotel_id' => $hotel->id]);

    expect($reservation->hotel)->toBeInstanceOf(Hotel::class)
        ->and($reservation->hotel->id)->toBe($hotel->id);
})->uses(TestCase::class);
