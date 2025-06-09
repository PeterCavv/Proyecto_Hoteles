<?php

use App\Enums\FriendshipStatus;

it('returns all enum values in getValues', function () {
    $values = FriendshipStatus::getValues();

    expect($values)->toBeArray()
        ->and($values)->toHaveCount(3)
        ->and($values)->toContain(FriendshipStatus::PENDING)
        ->and($values)->toContain(FriendshipStatus::ACCEPTED)
        ->and($values)->toContain(FriendshipStatus::BLOCKED);
});

it('has correct string values', function () {
    expect(FriendshipStatus::PENDING->value)->toBe('pending')
        ->and(FriendshipStatus::ACCEPTED->value)->toBe('accepted')
        ->and(FriendshipStatus::BLOCKED->value)->toBe('blocked');
});

it('has correct names', function () {
    expect(FriendshipStatus::PENDING->name)->toBe('PENDING')
        ->and(FriendshipStatus::ACCEPTED->name)->toBe('ACCEPTED')
        ->and(FriendshipStatus::BLOCKED->name)->toBe('BLOCKED');
});
