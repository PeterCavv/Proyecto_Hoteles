<?php

use App\Models\City;
use App\Models\Customer;
use App\Models\Follow;
use App\Models\Hotel;
use App\Models\Reservation;
use App\Models\RoomType;
use app\Models\User;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->customer = Customer::factory()->create();
    $this->hotel = Hotel::factory()->create();
    $this->roomType = RoomType::factory()->create();
    $this->reservation = Reservation::factory()->create([
        'customer_id' => $this->customer->id,
        'hotel_id' => $this->hotel->id,
        'room_type_id' => $this->roomType->id,
    ]);
});

it('belongs to a customer', function () {
    expect($this->reservation->customer)->toBeInstanceOf(Customer::class)
        ->and($this->reservation->customer->id)->toBe($this->customer->id);
});

it('belongs to a room type', function () {
    expect($this->reservation->roomType)->toBeInstanceOf(RoomType::class)
        ->and($this->reservation->roomType->id)->toBe($this->roomType->id);
});

it('belongs to a hotel', function () {
    expect($this->reservation->hotel)->toBeInstanceOf(Hotel::class)
        ->and($this->reservation->hotel->id)->toBe($this->hotel->id);
});

it('filters reservations by customer name, email, hotel city and price', function () {
    Reservation::query()->delete();

    $city1 = City::factory()->create(['name' => 'New York']);
    $user1 = User::factory()->create(['name' => 'John Doe', 'email' => 'john@example.com']);
    $customer1 = Customer::factory()->create(['user_id' => $user1->id]);
    $hotel1 = Hotel::factory()->create(['city_id' => $city1->id]);

    Reservation::factory()->create([
        'customer_id' => $customer1->id,
        'hotel_id' => $hotel1->id,
        'price' => 100,
    ]);

    $city2 = City::factory()->create(['name' => 'London']);
    $user2 = User::factory()->create(['name' => 'Jane Smith', 'email' => 'jane@example.com']);
    $customer2 = Customer::factory()->create(['user_id' => $user2->id]);
    $hotel2 = Hotel::factory()->create(['city_id' => $city2->id]);

    Reservation::factory()->create([
        'customer_id' => $customer2->id,
        'hotel_id' => $hotel2->id,
        'price' => 200,
    ]);

    $reservations = Reservation::filter(['customer_name' => 'John'])->get();
    expect($reservations)->toHaveCount(1)
        ->and($reservations->first()->customer->user->name)->toBe('John Doe');

    $reservations = Reservation::filter(['customer_email' => 'jane@example.com'])->get();
    expect($reservations)->toHaveCount(1)
        ->and($reservations->first()->customer->user->email)->toBe('jane@example.com');

    $reservations = Reservation::filter(['hotel_city' => 'New York'])->get();
    expect($reservations)->toHaveCount(1)
        ->and($reservations->first()->hotel->city->name)->toBe('New York');

    $reservations = Reservation::filter(['min_price' => 150])->get();
    expect($reservations)->toHaveCount(1)
        ->and($reservations->first()->price)->toBe(200);

    $reservations = Reservation::filter(['max_price' => 150])->get();
    expect($reservations)->toHaveCount(1)
        ->and($reservations->first()->price)->toBe(100);
});

