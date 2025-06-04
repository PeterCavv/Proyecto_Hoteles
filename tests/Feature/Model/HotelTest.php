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

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->hotel = Hotel::factory()->create();
});

it('belongs to user', function () {
    $hotel = Hotel::factory()->create(['user_id' => $this->user->id]);

    expect($hotel->user)->toBeInstanceOf(User::class)
        ->and($hotel->user->id)->toBe($this->user->id);
});

it('has many features', function () {
    $feature1 = Feature::factory()->create();
    $feature2 = Feature::factory()->create();

    $this->hotel->features()->attach([$feature1->id, $feature2->id]);

    $features = $this->hotel->features;

    expect($features)->toHaveCount(2)
        ->and($features->pluck('id'))->toContain($feature1->id);
});

it('has many reviews', function () {
    $review1 = Review::factory()->create(['user_id' => $this->user->id, 'hotel_id' => $this->hotel->id]);
    $review2 = Review::factory()->create(['user_id' => $this->user->id, 'hotel_id' => $this->hotel->id]);

    $reviews = $this->hotel->reviews;

    expect($reviews)->toHaveCount(2)
        ->and($reviews->pluck('id'))->toContain($review1->id);
});

it('has many follows', function () {
    $customer1 = Customer::factory()->create();
    $customer2 = Customer::factory()->create();

    Follow::factory()->create(['hotel_id' => $this->hotel->id, 'customer_id' => $customer1->id]);
    Follow::factory()->create(['hotel_id' => $this->hotel->id, 'customer_id' => $customer2->id]);

    $follows = $this->hotel->follows;

    expect($follows)->toHaveCount(2)
        ->and($follows->pluck('customer_id'))->toContain($customer1->id)
        ->and($follows->pluck('customer_id'))->toContain($customer2->id);
});

it('filters hotels by city', function () {
    Hotel::factory()->create(['city' => 'New York']);
    Hotel::factory()->create(['city' => 'Los Angeles']);

    $hotels = Hotel::filter(['city' => 'New'])->get();

    expect($hotels)->toHaveCount(1)
        ->and($hotels->first()->city)->toBe('New York');
});

it('filters hotels by name', function () {
    Hotel::factory()->create(['name' => 'Grand Plaza']);
    Hotel::factory()->create(['name' => 'Ocean View']);

    $hotels = Hotel::filter(['name' => 'Ocean'])->get();

    expect($hotels)->toHaveCount(1)
        ->and($hotels->first()->name)->toBe('Ocean View');
});

it('filters hotels by city and name combined', function () {
    Hotel::factory()->create(['name' => 'Grand Plaza', 'city' => 'New York']);
    Hotel::factory()->create(['name' => 'Ocean View', 'city' => 'Los Angeles']);
    Hotel::factory()->create(['name' => 'City Center', 'city' => 'New York']);

    $hotels = Hotel::filter(['city' => 'New York', 'name' => 'City'])->get();

    expect($hotels)->toHaveCount(1)
        ->and($hotels->first()->name)->toBe('City Center');
});

it('returns all hotels if no filter is applied', function () {
    Hotel::factory()->count(3)->create();

    $hotels = Hotel::filter([])->get();

    expect($hotels)->toHaveCount(4);
});
