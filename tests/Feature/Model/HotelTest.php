<?php

use App\Models\Customer;
use App\Models\Feature;
use App\Models\Follow;
use App\Models\Hotel;
use App\Models\Review;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

uses(RefreshDatabase::class);

it('belongs to user', function () {
    $user = User::factory()->create();
    $hotel = Hotel::factory()->create(['user_id' => $user->id]);

    expect($hotel->user)->toBeInstanceOf(User::class)
        ->and($hotel->user->id)->toBe($user->id);
})->uses(TestCase::class);

it('has many features', function () {
    $feature1 = Feature::factory()->create();
    $feature2 = Feature::factory()->create();
    $hotel = Hotel::factory()->create();

    $hotel->features()->attach([$feature1->id, $feature2->id]);

    expect($hotel->features)->toHaveCount(2)
        ->and($hotel->features->first()->id)->toBe($feature1->id);
})->uses(TestCase::class);

it('has many reviews', function () {
    $hotel = Hotel::factory()->create();
    $user = User::factory()->create();
    $review1 = Review::factory()->create(['user_id' => $user->id, 'hotel_id' => $hotel->id]);
    $review2 = Review::factory()->create(['user_id' => $user->id, 'hotel_id' => $hotel->id]);

    expect($hotel->reviews)->toHaveCount(2)
        ->and($hotel->reviews->first()->id)->toBe($review1->id);
})->uses(TestCase::class);

it('has many follows', function () {
    $hotel = Hotel::factory()->create();
    $customer1 = Customer::factory()->create();
    $customer2 = Customer::factory()->create();

    $hotel->customers()->attach($customer1->id, ['followed_at' => now()]);
    $hotel->customers()->attach($customer2->id, ['followed_at' => now()]);

    $follows = $hotel->customers;

    expect($follows)->toHaveCount(2)
        ->and($follows->first()->id)->toBe($customer1->id)
        ->and($follows->last()->id)->toBe($customer2->id);
})->uses(TestCase::class);
