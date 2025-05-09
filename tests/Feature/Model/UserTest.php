<?php

namespace Tests\Feature\Model;

use App\Models\Customer;
use App\Models\Hotel;
use App\Models\Like;
use App\Models\Review;
use App\Models\User;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

uses(RefreshDatabase::class);

it('can be a customer but not an hotel', function () {
    $user = User::factory()->create();
    Customer::factory()->create(['user_id' => $user->id]);

    expect($user->isCustomer())->toBeTrue()
        ->and($user->isHotel())->toBeFalse();
})->uses(TestCase::class);

it('can be a hotel but not a customer', function () {
    $user = User::factory()->create();
    Hotel::factory()->create(['user_id' => $user->id]);

    expect($user->isHotel())->toBeTrue()
        ->and($user->isCustomer())->toBeFalse();
})->uses(TestCase::class);

it('has one customer', function () {
    $user = User::factory()->create();
    $customer = Customer::factory()->create(['user_id' => $user->id]);

    expect($user->customer)->toBeInstanceOf(Customer::class)
        ->and($user->customer->id)->toBe($customer->id);
})->uses(TestCase::class);

it('has one hotel', function () {
    $user = User::factory()->create();
    $hotel = Hotel::factory()->create(['user_id' => $user->id]);

    expect($user->hotel)->toBeInstanceOf(Hotel::class)
        ->and($user->hotel->id)->toBe($hotel->id);
})->uses(TestCase::class);

it('has many reviews', function () {
    $user = User::factory()->create();
    $hotel = Hotel::factory()->create();
    $review1 = Review::factory()->create(['user_id' => $user->id, 'hotel_id' => $hotel->id]);
    $review2 = Review::factory()->create(['user_id' => $user->id, 'hotel_id' => $hotel->id]);

    expect($user->reviews)->toHaveCount(2)
        ->and($user->reviews->first()->id)->toBe($review1->id);
})->uses(TestCase::class);

it('has many likes', function () {
    $user = User::factory()->create();
    $review = Review::factory()->create();
    $like = Like::factory()->create(['user_id' => $user->id, 'review_id' => $review->id]);

    expect($user->likes)->toHaveCount(1)
        ->and($user->likes->first()->id)->toBe($like->id);
})->uses(TestCase::class);

it('has many friends', function () {
    $user1 = User::factory()->create();
    $user2 = User::factory()->create();
    $user3 = User::factory()->create();

    $user1->friends()->attach($user2->id, ['status' => 'accepted']);
    $user1->friends()->attach($user3->id, ['status' => 'pending']);

    expect($user1->friends)->toHaveCount(1)
        ->and($user1->friends->first()->id)->toBe($user2->id);
})->uses(TestCase::class);

it('has many pending friends', function () {
    $user1 = User::factory()->create();
    $user2 = User::factory()->create();
    $user3 = User::factory()->create();

    $user1->friends()->attach($user2->id, ['status' => 'pending']);
    $user1->friends->first->accepted = true;

    expect($user1->pendingFriends)->toHaveCount(1)
        ->and($user1->pendingFriends->first()->id)->toBe($user2->id);
})->uses(TestCase::class);

it('has many blocked friends', function () {
    $user1 = User::factory()->create();
    $user2 = User::factory()->create();
    $user3 = User::factory()->create();

    $user1->friends()->attach($user2->id, ['status' => 'blocked']);
    $user1->friends()->attach($user3->id, ['status' => 'accepted']);

    expect($user1->blockedFriends)->toHaveCount(1)
        ->and($user1->blockedFriends->first()->id)->toBe($user2->id);
})->uses(TestCase::class);
