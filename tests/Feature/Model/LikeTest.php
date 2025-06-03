<?php

use App\Models\Like;
use App\Models\Review;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->review = Review::factory()->create(['user_id' => $this->user->id]);
    $this->like = Like::factory()->create([
        'user_id' => $this->user->id,
        'review_id' => $this->review->id
    ]);
});

it('belongs to a user', function () {
    expect($this->like->user)->toBeInstanceOf(User::class)
        ->and($this->like->user->id)->toBe($this->user->id);
});

it('belongs to a review', function () {
    expect($this->like->review)->toBeInstanceOf(Review::class)
        ->and($this->like->review->id)->toBe($this->review->id);
});

it('has a liked_at timestamp', function () {
    expect($this->like->liked_at)->not->toBeNull()
        ->and($this->like->liked_at)->toBeInstanceOf(Carbon::class);
});


