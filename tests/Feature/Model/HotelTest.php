<?php

use App\Models\City;
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
    $this->city = City::factory()->create();
    $this->hotel = Hotel::factory()->create([
        'user_id' => $this->user->id,
        'city_id' => $this->city->id,  // Asignamos ciudad
    ]);
});

it('belongs to user', function () {
    $hotel = Hotel::factory()->create(['user_id' => $this->user->id]);

    expect($hotel->user)->toBeInstanceOf(User::class)
        ->and($hotel->user->id)->toBe($this->user->id);
});

it('belongs to city', function () {
    $hotel = Hotel::factory()->create(['city_id' => $this->city->id]);

    expect($hotel->city)->toBeInstanceOf(City::class)
        ->and($hotel->city->id)->toBe($this->city->id);
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

it('filters hotels by city using relation', function () {
    Hotel::query()->delete();

    $city1 = City::factory()->create(['name' => 'New York']);
    $city2 = City::factory()->create(['name' => 'Los Angeles']);

    Hotel::factory()->create(['city_id' => $city1->id]);
    Hotel::factory()->create(['city_id' => $city2->id]);

    $hotels = Hotel::filter(['city_id' => $city1->id])->get();

    expect($hotels)->toHaveCount(1)
        ->and($hotels->first()->city->name)->toBe('New York');
});

it('filters hotels by name', function () {
    Hotel::factory()->create(['name' => 'Grand Plaza']);
    Hotel::factory()->create(['name' => 'Ocean View']);

    $hotels = Hotel::filter(['name' => 'Ocean'])->get();

    expect($hotels)->toHaveCount(1)
        ->and($hotels->first()->name)->toBe('Ocean View');
});

it('filters hotels by city and name combined', function () {
    $cityNY = City::factory()->create(['name' => 'New York']);
    $cityLA = City::factory()->create(['name' => 'Los Angeles']);

    Hotel::factory()->create(['name' => 'Grand Plaza', 'city_id' => $cityNY->id]);
    Hotel::factory()->create(['name' => 'Ocean View', 'city_id' => $cityLA->id]);
    Hotel::factory()->create(['name' => 'City Center', 'city_id' => $cityNY->id]);

    $hotels = Hotel::filter(['city_id' => $cityNY->id, 'name' => 'City'])->get();

    expect($hotels)->toHaveCount(1)
        ->and($hotels->first()->name)->toBe('City Center');
});

it('returns all hotels if no filter is applied', function () {
    Hotel::factory()->count(3)->create();

    $hotels = Hotel::filter([])->get();

    expect($hotels)->toHaveCount(4);
});
