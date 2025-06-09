<?php

use App\Enums\RoleEnum;
use App\Models\User;
use App\Models\Customer;
use App\Models\Hotel;
use App\Models\RoomType;
use App\Models\Reservation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;

uses(RefreshDatabase::class);

beforeEach(function () {
    Role::findOrCreate(RoleEnum::ADMIN->value);
    Role::findOrCreate(RoleEnum::HOTEL->value);
    Role::findOrCreate(RoleEnum::CUSTOMER->value);

    $this->customerUser = User::factory()->create();
    $this->customerUser->assignRole(RoleEnum::CUSTOMER->value);
    $this->customer = Customer::factory()->for($this->customerUser)->create();

    $this->hotelUser = User::factory()->create();
    $this->hotelUser->assignRole(RoleEnum::HOTEL->value);
    $this->hotel = Hotel::factory()->for($this->hotelUser)->create();

    $this->roomType = RoomType::factory()->create();

    $this->adminUser = User::factory()->create();
    $this->adminUser->assignRole(RoleEnum::ADMIN->value);
});

it('admin can list all reservations', function () {
    Reservation::factory()->count(3)->create([
        'customer_id' => $this->customer->id,
        'hotel_id' => $this->hotel->id,
        'room_type_id' => $this->roomType->id,
    ]);

    $this->actingAs($this->adminUser);

    $response = $this->getJson(route('reservations.index'));

    $response->assertStatus(200);
    $response->assertJsonCount(3);
});

it('customer can list own reservations only', function () {
    Reservation::factory()->create([
        'customer_id' => $this->customer->id,
        'hotel_id' => $this->hotel->id,
        'room_type_id' => $this->roomType->id,
    ]);

    $otherCustomer = Customer::factory()->create();
    Reservation::factory()->create([
        'customer_id' => $otherCustomer->id,
        'hotel_id' => $this->hotel->id,
        'room_type_id' => $this->roomType->id,
    ]);

    $this->actingAs($this->customerUser);

    $response = $this->getJson(route('reservations.index'));

    $response->assertStatus(200);
    $response->assertJsonCount(1);
});

it('hotel user can list reservations for their hotel only', function () {
    Reservation::factory()->create([
        'customer_id' => $this->customer->id,
        'hotel_id' => $this->hotel->id,
        'room_type_id' => $this->roomType->id,
    ]);

    $otherHotel = Hotel::factory()->create();
    Reservation::factory()->create([
        'customer_id' => $this->customer->id,
        'hotel_id' => $otherHotel->id,
        'room_type_id' => $this->roomType->id,
    ]);

    $this->actingAs($this->hotelUser);

    $response = $this->getJson(route('reservations.index'));

    $response->assertStatus(200);
    $response->assertJsonCount(1);
});

it('can store a reservation with valid data', function () {
    $this->actingAs($this->adminUser);

    $payload = [
        'customer_id' => $this->customer->id,
        'hotel_id' => $this->hotel->id,
        'room_type_id' => $this->roomType->id,
        'check_in' => now()->addDay()->toDateString(),
        'check_out' => now()->addDays(2)->toDateString(),
        'price' => 150.50,
        'adults' => 2,
        'children' => 0,
    ];

    $response = $this->postJson(route('reservations.store'), $payload);

    $response->assertStatus(201);
    $this->assertDatabaseHas('reservations', [
        'customer_id' => $payload['customer_id'],
        'hotel_id' => $payload['hotel_id'],
    ]);
});

it('can update a reservation', function () {
    $this->actingAs($this->adminUser);

    $reservation = Reservation::factory()->create([
        'customer_id' => $this->customer->id,
        'hotel_id' => $this->hotel->id,
        'room_type_id' => $this->roomType->id,
        'price' => 100,
        'adults' => 2,
        'children' => 1,
        'check_in' => now()->addDay()->toDateString(),
        'check_out' => now()->addDays(2)->toDateString(),
    ]);

    $payload = [
        'price' => 200.00,
        'adults' => 3,
        'children' => 2,
        'check_in' => now()->addDays(3)->toDateString(),
        'check_out' => now()->addDays(4)->toDateString(),
        'customer_id' => $reservation->customer_id,
        'hotel_id' => $reservation->hotel_id,
        'room_type_id' => $reservation->room_type_id,
    ];

    $response = $this->putJson(route('reservations.update', $reservation), $payload);

    $response->assertStatus(200);
    $this->assertDatabaseHas('reservations', [
        'id' => $reservation->id,
        'price' => 200.00,
        'adults' => 3,
    ]);
});

it('can delete a reservation', function () {
    $this->actingAs($this->adminUser);

    $reservation = Reservation::factory()->create();

    $response = $this->deleteJson(route('reservations.destroy', $reservation));

    $response->assertStatus(204);
    $this->assertDatabaseMissing('reservations', ['id' => $reservation->id]);
});

