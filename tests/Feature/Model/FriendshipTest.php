<?php

use App\Enums\FriendshipStatus;
use App\Models\Friendship;
use app\Models\User;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

uses(RefreshDatabase::class);

it('can create a friendship', function () {
    $user1 = User::factory()->create();
    $user2 = User::factory()->create();

    $friendship = Friendship::factory()->create([
        'user_1_id' => $user1->id,
        'user_2_id' => $user2->id,
        'status' => FriendshipStatus::PENDING,
    ]);

    expect($friendship)->toBeInstanceOf(Friendship::class)
        ->and($friendship->status)->toBe(FriendshipStatus::PENDING);
})->uses(TestCase::class);

it('can accept a friendship', function () {
    $friendship = Friendship::factory()->create([
        'status' => FriendshipStatus::PENDING,
    ]);

    $friendship->accept();

    expect($friendship->fresh()->status)->toBe(FriendshipStatus::ACCEPTED);
})->uses(TestCase::class);

it('can block a friendship', function () {
    $friendship = Friendship::factory()->create([
        'status' => FriendshipStatus::PENDING,
    ]);

    $friendship->block();

    expect($friendship->fresh()->status)->toBe(FriendshipStatus::BLOCKED);
})->uses(TestCase::class);

it('can reject a friendship', function () {
    $friendship = Friendship::factory()->create();

    $friendship->reject();

    expect(Friendship::find($friendship->id))->toBeNull();
})->uses(TestCase::class);

it('has user1 and user2 relationships', function () {
    $user1 = User::factory()->create();
    $user2 = User::factory()->create();

    $friendship = Friendship::create([
        'user_1_id' => $user1->id,
        'user_2_id' => $user2->id,
        'status' => FriendshipStatus::PENDING,
    ]);

    expect($friendship->user1->id)->toBe($user1->id)
        ->and($friendship->user2->id)->toBe($user2->id);
})->uses(TestCase::class);

