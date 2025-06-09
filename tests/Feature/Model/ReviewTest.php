<?php

use App\Models\Customer;
use App\Models\Follow;
use App\Models\Hotel;
use App\Models\Like;
use App\Models\Reservation;
use App\Models\Review;
use app\Models\User;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->customer = Customer::factory()->create();
    $this->hotel = Hotel::factory()->create();
    $this->review = Review::factory()->create([
        'user_id' => $this->customer->id,
        'hotel_id' => $this->hotel->id,
    ]);
    $this->parentReview = Review::factory()->create();
});

it('belongs to a user', function () {
    expect($this->review->user)->toBeInstanceOf(User::class)
        ->and($this->review->user->id)->toBe($this->customer->user->id);
});

it('belongs to a hotel', function () {
    expect($this->review->hotel)->toBeInstanceOf(Hotel::class)
        ->and($this->review->hotel->id)->toBe($this->hotel->id);
});

it('belongs to a parent review', function () {
    $childReview = Review::factory()->create(['parent_id' => $this->parentReview->id]);

    expect($childReview->parent)->toBeInstanceOf(Review::class)
        ->and($childReview->parent->id)->toBe($this->parentReview->id);
});

it('has many children reviews', function () {
    $child1 = Review::factory()->create(['parent_id' => $this->parentReview->id]);
    $child2 = Review::factory()->create(['parent_id' => $this->parentReview->id]);

    expect($this->parentReview->children)->toHaveCount(2)
        ->and($this->parentReview->children->pluck('id'))->toContain($child1->id)
        ->and($this->parentReview->children->pluck('id'))->toContain($child2->id);
});

it('has many likes', function () {
    $like1 = Like::factory()->create(['review_id' => $this->review->id]);
    $like2 = Like::factory()->create(['review_id' => $this->review->id]);

    expect($this->review->likes)->toHaveCount(2)
        ->and($this->review->likes->pluck('id'))->toContain($like1->id)
        ->and($this->review->likes->pluck('id'))->toContain($like2->id);
});

