<?php

use App\Models\Hotel;
use App\Models\User;
use App\Policies\HotelPolicy;

beforeEach(function () {
    $this->policy = new HotelPolicy();
});

it('allows a user to update their own hotel', function () {
    $user = Mockery::mock(User::class)->makePartial();
    $user->id = 1;
    $user->shouldReceive('isAdmin')->andReturnFalse();

    $hotel = Mockery::mock(Hotel::class)->makePartial();
    $hotel->user_id = 1;

    expect($this->policy->update($user, $hotel))->toBeTrue();
});

it('allows an admin to update any hotel', function () {
    $admin = Mockery::mock(User::class)->makePartial();
    $admin->id = 99;
    $admin->shouldReceive('isAdmin')->andReturnTrue();

    $hotel = Mockery::mock(Hotel::class)->makePartial();
    $hotel->user_id = 1;

    expect($this->policy->update($admin, $hotel))->toBeTrue();
});

it('denies a user from updating someone else\'s hotel if not admin', function () {
    $user = Mockery::mock(User::class)->makePartial();
    $user->id = 1;
    $user->shouldReceive('isAdmin')->andReturnFalse();

    $hotel = Mockery::mock(Hotel::class)->makePartial();
    $hotel->user_id = 2;

    expect($this->policy->update($user, $hotel))->toBeFalse();
});

it('denies update if the hotel has no user_id', function () {
    $user = Mockery::mock(User::class)->makePartial();
    $user->id = 1;
    $user->shouldReceive('isAdmin')->andReturnFalse();

    $hotel = Mockery::mock(Hotel::class)->makePartial();

    unset($hotel->user_id);

    expect($this->policy->update($user, $hotel))->toBeFalse();
});


