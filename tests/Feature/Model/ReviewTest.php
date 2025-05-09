<?php

use App\Models\Customer;
use App\Models\Follow;
use App\Models\Hotel;
use App\Models\Reservation;
use App\Models\Review;
use app\Models\User;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

uses(RefreshDatabase::class);

it('belongs to a user', function () {
    $user = User::factory()->create();
    $review = Review::factory()->create(['user_id' => $user->id]);

    expect($review->user)->toBeInstanceOf(User::class)
        ->and($review->user->id)->toBe($user->id);
});

it('belongs to a hotel', function () {
    $hotel = Hotel::factory()->create();
    $review = Review::factory()->create(['hotel_id' => $hotel->id]);

    expect($review->hotel)->toBeInstanceOf(Hotel::class)
        ->and($review->hotel->id)->toBe($hotel->id);
});

it('has children reviews', function () {
    $parentReview = Review::factory()->create();
    $childReview = Review::factory()->create(['parent_id' => $parentReview->id]);

    expect($parentReview->children)->toHaveCount(1)
        ->and($parentReview->children->first()->id)->toBe($childReview->id);
});

it('has a parent review', function () {
    $parentReview = Review::factory()->create();
    $childReview = Review::factory()->create(['parent_id' => $parentReview->id]);

    expect($childReview->parent)->toBeInstanceOf(Review::class)
        ->and($childReview->parent->id)->toBe($parentReview->id);
});

