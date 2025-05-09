<?php

use App\Models\Customer;
use App\Models\Follow;
use App\Models\Hotel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

uses(RefreshDatabase::class);

it('belongs to a customer', function () {
    $customer = Customer::factory()->create();
    $follow = Follow::factory()->create(['customer_id' => $customer->id]);

    expect($follow->customer)->toBeInstanceOf(Customer::class)
        ->and($follow->customer->id)->toBe($customer->id);
})->uses(TestCase::class);

it('belongs to a hotel', function () {
    $hotel = Hotel::factory()->create();
    $follow = Follow::factory()->create(['hotel_id' => $hotel->id]);

    expect($follow->hotel)->toBeInstanceOf(Hotel::class)
        ->and($follow->hotel->id)->toBe($hotel->id);
})->uses(TestCase::class);

it('returns follows for a specific customer', function () {
    $customer = Customer::factory()->create();
    $follow1 = Follow::factory()->create(['customer_id' => $customer->id]);
    $follow2 = Follow::factory()->create(['customer_id' => $customer->id]);
    $otherFollow = Follow::factory()->create();

    $followedFollows = Follow::followed($customer->id)->get();

    expect($followedFollows)->toHaveCount(2)
        ->and($followedFollows->first()->id)->toBe($follow1->id);
})->uses(TestCase::class);
