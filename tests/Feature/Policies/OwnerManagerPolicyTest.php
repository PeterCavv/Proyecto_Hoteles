<?php

use App\Models\User;
use App\Traits\OwnerManagerPolicy;

class DummyPolicy
{
    use OwnerManagerPolicy;
}

beforeEach(function () {
    $this->policy = new DummyPolicy();
});

it('allows to manage a user his own model', function () {
    $user = Mockery::mock(User::class)->makePartial();
    $user->id = 1;
    $user->shouldReceive('isAdmin')->andReturnFalse();
    $model = (object)['user_id' => 1];

    expect($this->policy->canManage($user, $model))->toBeTrue();
});

it('allows an admin to manage every model', function () {
    $admin = Mockery::mock(User::class)->makePartial();
    $model = (object)['user_id' => 2];

    $admin->id = 99;
    $admin->shouldReceive('isAdmin')->andReturnTrue();

    expect($this->policy->canManage($admin, $model))->toBeTrue();
});

it('forbid a user to manage a foreign model if he is not an admin', function () {
    $user = Mockery::mock(User::class)->makePartial();
    $model = (object)['user_id' => 2];

    $user->id = 1;
    $user->shouldReceive('isAdmin')->andReturnFalse();

    expect($this->policy->canManage($user, $model))->toBeFalse();
});

it('forbid to manage without user_id', function () {
    $user = Mockery::mock(User::class)->makePartial();
    $model = (object)[]; // sin user_id

    $user->id = 1;
    $user->shouldReceive('isAdmin')->andReturnFalse();

    expect($this->policy->canManage($user, $model))->toBeFalse();
});
