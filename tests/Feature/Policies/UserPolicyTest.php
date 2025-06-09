<?php

use App\Models\User;
use App\Policies\UserPolicy;

beforeEach(function () {
    $this->policy = new UserPolicy();
});

it('allows an admin to impersonate a non-admin user', function () {
    $admin = Mockery::mock(User::class)->makePartial();
    $target = Mockery::mock(User::class)->makePartial();

    $admin->shouldReceive('isAdmin')->andReturnTrue();
    $target->shouldReceive('isAdmin')->andReturnFalse();

    expect($this->policy->impersonate($admin, $target))->toBeTrue();
});

it('forbid an admin to impersonate another admin', function () {
    $admin1 = Mockery::mock(User::class)->makePartial();
    $admin2 = Mockery::mock(User::class)->makePartial();

    $admin1->shouldReceive('isAdmin')->andReturnTrue();
    $admin2->shouldReceive('isAdmin')->andReturnTrue();

    expect($this->policy->impersonate($admin1, $admin2))->toBeFalse();
});

it('forbid a non-admin user to impersonate another user', function () {
    $user = Mockery::mock(User::class)->makePartial();
    $target = Mockery::mock(User::class)->makePartial();

    $user->shouldReceive('isAdmin')->andReturnFalse();

    expect($this->policy->impersonate($user, $target))->toBeFalse();
});

it('allows an admin to use user index', function () {
    $admin = Mockery::mock(User::class)->makePartial();
    $nonAdmin = Mockery::mock(User::class)->makePartial();

    $admin->shouldReceive('isAdmin')->andReturnTrue();
    $nonAdmin->shouldReceive('isAdmin')->andReturnFalse();

    expect($this->policy->index($admin))->toBeTrue()
        ->and($this->policy->index($nonAdmin))->toBeFalse();
});

it('allows a user to update itself', function () {
    $user = new User(['id' => 1]);
    $target = new User(['id' => 1]);

    expect($this->policy->update($user, $target))->toBeTrue();
});

it('allows an admin to update any user', function () {
    $admin = Mockery::mock(User::class)->makePartial();
    $target = new User(['id' => 2]);

    $admin->id = 1;
    $admin->shouldReceive('isAdmin')->andReturnTrue();

    expect($this->policy->update($admin, $target))->toBeTrue();
});

it('forbid a non-admin user to update another user', function () {
    $user = Mockery::mock(User::class)->makePartial();
    $target = new User(['id' => 2]);

    $user->id = 1;
    $user->shouldReceive('isAdmin')->andReturnFalse();

    expect($this->policy->update($user, $target))->toBeFalse();
});
