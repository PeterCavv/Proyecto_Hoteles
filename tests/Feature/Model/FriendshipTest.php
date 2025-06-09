<?php

use App\Enums\FriendshipStatus;
use App\Models\Friendship;
use app\Models\User;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->user1 = User::factory()->create();
    $this->user2 = User::factory()->create();
    $this->friendship = Friendship::factory()->create([
        'user_1_id' => $this->user1->id,
        'user_2_id' => $this->user2->id,
        'status' => FriendshipStatus::PENDING,
    ]);;
});

it('belongs to user1 and user2', function () {
    expect($this->friendship->user1)->toBeInstanceOf(User::class)
        ->and($this->friendship->user1->id)->toBe($this->user1->id)
        ->and($this->friendship->user2)->toBeInstanceOf(User::class)
        ->and($this->friendship->user2->id)->toBe($this->user2->id);
});

it('can accept a friendship request', function () {
    $this->friendship->accept();

    expect($this->friendship->status)->toBe(FriendshipStatus::ACCEPTED)
        ->and(Friendship::find($this->friendship->id)->status)->toBe(FriendshipStatus::ACCEPTED);
});

it('can block a friendship', function () {
    $this->friendship->block();

    expect($this->friendship->status)->toBe(FriendshipStatus::BLOCKED)
        ->and(Friendship::find($this->friendship->id)->status)->toBe(FriendshipStatus::BLOCKED);
});

it('can reject a friendship, deleting the record', function () {
    $this->friendship->reject();

    expect(Friendship::find($this->friendship->id))->toBeNull();
});


