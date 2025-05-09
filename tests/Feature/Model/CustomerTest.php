<?php


use App\Models\Customer;
use App\Models\Follow;
use App\Models\Reservation;
use app\Models\User;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

uses(RefreshDatabase::class);

it('can be created with a user', function () {
    $user = User::factory()->create();
    $customer = Customer::factory()->create(['user_id' => $user->id]);

    expect($customer)->toBeInstanceOf(Customer::class)
        ->and($customer->user_id)->toBe($user->id);
})->uses(TestCase::class);

it('belongs to a user', function () {
    $user = User::factory()->create();
    $customer = Customer::factory()->create(['user_id' => $user->id]);

    expect($customer->user)->toBeInstanceOf(User::class)
        ->and($customer->user->id)->toBe($user->id);
})->uses(TestCase::class);

it('has many reservations', function () {
    $customer = Customer::factory()->create();
    $reservation1 = Reservation::factory()->create(['customer_id' => $customer->id]);
    $reservation2 = Reservation::factory()->create(['customer_id' => $customer->id]);

    expect($customer->reservations)->toHaveCount(2)
        ->and($customer->reservations->first()->id)->toBe($reservation1->id);
})->uses(TestCase::class);

it('has many follows', function () {
    $customer = Customer::factory()->create();
    $follow1 = Follow::factory()->create(['customer_id' => $customer->id]);
    $follow2 = Follow::factory()->create(['customer_id' => $customer->id]);

    expect($customer->follows)->toHaveCount(2)
        ->and($customer->follows->first()->id)->toBe($follow1->id);
})->uses(TestCase::class);
