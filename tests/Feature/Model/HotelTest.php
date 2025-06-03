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
