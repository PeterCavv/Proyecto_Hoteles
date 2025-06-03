<?php


use App\Models\Customer;
use App\Models\Follow;
use App\Models\Hotel;
use App\Models\Reservation;
use app\Models\User;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->customer = Customer::factory()->create();
});

it('has the correct fillable properties', function () {
    $customer = new Customer();

    expect($customer->getFillable())->toBe([
        'user_id',
        'name',
        'last_name',
        'dni',
    ]);
});

it('belongs to a user', function () {
    $customer = Customer::factory()->create(['user_id' => $this->user->id]);

    expect($customer->user)->toBeInstanceOf(User::class)
        ->and($customer->user->id)->toBe($this->user->id);
});

it('has many reservations', function () {
    Reservation::factory()->count(2)->create([
        'customer_id' => $this->customer->id,
    ]);

    expect($this->customer->reservations)->toHaveCount(2)
        ->and($this->customer->reservations->first())->toBeInstanceOf(Reservation::class);
});

it('has many follows', function () {
    $hotels = Hotel::factory()->count(2)->create();

    foreach ($hotels as $hotel) {
        Follow::create([
            'customer_id' => $this->customer->id,
            'hotel_id' => $hotel->id,
            'followed_at' => now(),
        ]);
    }

    $this->customer->refresh();

    expect($this->customer->follows)->toHaveCount(2)
        ->and($this->customer->follows->first())->toBeInstanceOf(Follow::class);
});

