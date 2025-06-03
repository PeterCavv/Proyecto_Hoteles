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

it('permite al dueño gestionar su propio modelo', function () {
    $user = Mockery::mock(User::class)->makePartial();
    $user->id = 1;
    $user->shouldReceive('isAdmin')->andReturnFalse();
    $model = (object)['user_id' => 1];

    expect($this->policy->canManage($user, $model))->toBeTrue();
});

it('permite a un admin gestionar cualquier modelo', function () {
    $admin = Mockery::mock(User::class)->makePartial();
    $model = (object)['user_id' => 2];

    $admin->id = 99;
    $admin->shouldReceive('isAdmin')->andReturnTrue();

    expect($this->policy->canManage($admin, $model))->toBeTrue();
});

it('no permite a un usuario gestionar un modelo ajeno si no es admin', function () {
    $user = Mockery::mock(User::class)->makePartial();
    $model = (object)['user_id' => 2];

    $user->id = 1;
    $user->shouldReceive('isAdmin')->andReturnFalse();

    expect($this->policy->canManage($user, $model))->toBeFalse();
});

it('no permite gestionar un modelo sin user_id', function () {
    $user = Mockery::mock(User::class)->makePartial();
    $model = (object)[]; // sin user_id

    $user->id = 1;
    $user->shouldReceive('isAdmin')->andReturnFalse();

    expect($this->policy->canManage($user, $model))->toBeFalse();
});
