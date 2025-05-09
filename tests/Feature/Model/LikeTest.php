<?php

use App\Models\Like;
use App\Models\Review;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

uses(RefreshDatabase::class);

it('belongs to a user', function () {
    $user = User::factory()->create();
    $like = Like::factory()->create(['user_id' => $user->id]);

    expect($like->user)->toBeInstanceOf(User::class)
        ->and($like->user->id)->toBe($user->id);
})->uses(TestCase::class);

it('belongs to a review', function () {
    $like = Like::factory()->create();
    $review = $like->review;

    expect($like->review)->toBeInstanceOf(Review::class)
        ->and($like->review->id)->toBe($review->id);
})->uses(TestCase::class);

it('can be created with a user and review', function () {
    $user = User::factory()->create();
    $review = Review::factory()->create();

    $like = Like::factory()->create([
        'user_id' => $user->id,
        'review_id' => $review->id,
    ]);

    expect($like)->toBeInstanceOf(Like::class)
        ->and($like->user_id)->toBe($user->id)
        ->and($like->review_id)->toBe($review->id);
})->uses(TestCase::class);

it('can be created with a user and review using the factory', function () {
    $user = User::factory()->create();
    $review = Review::factory()->create();

    $like = Like::factory()->create([
        'user_id' => $user->id,
        'review_id' => $review->id,
    ]);

    expect($like)->toBeInstanceOf(Like::class)
        ->and($like->user_id)->toBe($user->id)
        ->and($like->review_id)->toBe($review->id);
})->uses(TestCase::class);

it('can be updated', function () {
    $like = Like::factory()->create();
    $newUser = User::factory()->create();
    $newReview = Review::factory()->create();

    $like->update([
        'user_id' => $newUser->id,
        'review_id' => $newReview->id,
    ]);

    expect($like->user_id)->toBe($newUser->id)
        ->and($like->review_id)->toBe($newReview->id);
})->uses(TestCase::class);

it('can be deleted', function () {
    $like = Like::factory()->create();

    $likeId = $like->id;
    $like->delete();

    expect(Like::find($likeId))->toBeNull();
})->uses(TestCase::class);


