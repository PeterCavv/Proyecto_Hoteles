<?php

use App\Models\City;
use App\Models\User;
use App\Policies\CityPolicy;

beforeEach(function () {
    $this->policy = new CityPolicy();
});

it('allows an admin to store a city', function () {
    $admin = Mockery::mock(User::class)->makePartial();
    $admin->shouldReceive('isAdmin')->andReturnTrue();

    expect($this->policy->create($admin))->toBeTrue();
});

it('denies a non-admin from storing a city', function () {
    $user = Mockery::mock(User::class)->makePartial();
    $user->shouldReceive('isAdmin')->andReturnFalse();

    expect($this->policy->create($user))->toBeFalse();
});

it('allows an admin to update any city', function () {
    $admin = Mockery::mock(User::class)->makePartial();
    $admin->shouldReceive('isAdmin')->andReturnTrue();

    $city = Mockery::mock(City::class)->makePartial();

    expect($this->policy->update($admin, $city))->toBeTrue();
});

it('denies a non-admin from updating a city', function () {
    $user = Mockery::mock(User::class)->makePartial();
    $user->shouldReceive('isAdmin')->andReturnFalse();

    $city = Mockery::mock(City::class)->makePartial();

    expect($this->policy->update($user, $city))->toBeFalse();
});

it('allows an admin to destroy a city', function () {
    $admin = Mockery::mock(User::class)->makePartial();
    $admin->shouldReceive('isAdmin')->andReturnTrue();

    $city = Mockery::mock(City::class)->makePartial();

    expect($this->policy->destroy($admin, $city))->toBeTrue();
});

it('denies a non-admin from destroying a city', function () {
    $user = Mockery::mock(User::class)->makePartial();
    $user->shouldReceive('isAdmin')->andReturnFalse();

    $city = Mockery::mock(City::class)->makePartial();

    expect($this->policy->destroy($user, $city))->toBeFalse();
});

