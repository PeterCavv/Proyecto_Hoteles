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
