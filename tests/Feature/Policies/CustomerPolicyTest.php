<?php

use App\Models\User;
use App\Models\Customer;
use App\Policies\CustomerPolicy;

beforeEach(function () {
    $this->policy = new CustomerPolicy();
});

it('allows admin to view customer index', function () {
    $admin = Mockery::mock(User::class)->makePartial();
    $admin->shouldReceive('isAdmin')->andReturnTrue();

    expect($this->policy->index($admin))->toBeTrue();
});

it('denies non-admin to view customer index', function () {
    $user = Mockery::mock(User::class)->makePartial();
    $user->shouldReceive('isAdmin')->andReturnFalse();

    expect($this->policy->index($user))->toBeFalse();
});

it('allows admin to update customer', function () {
    $admin = Mockery::mock(User::class)->makePartial();
    $admin->shouldReceive('isAdmin')->andReturnTrue();

    $customer = Mockery::mock(Customer::class)->makePartial();

    // Simula que el método canManage devuelve true
    $this->policy = Mockery::mock(CustomerPolicy::class)->makePartial();
    $this->policy->shouldAllowMockingProtectedMethods()
        ->shouldReceive('canManage')
        ->with($admin, $customer)
        ->andReturnTrue();

    expect($this->policy->update($admin, $customer))->toBeTrue();
});

it('denies user who cannot manage customer', function () {
    $user = Mockery::mock(User::class)->makePartial();
    $user->shouldReceive('isAdmin')->andReturnFalse();

    $customer = Mockery::mock(Customer::class)->makePartial();

    $this->policy = Mockery::mock(CustomerPolicy::class)->makePartial();
    $this->policy->shouldAllowMockingProtectedMethods()
        ->shouldReceive('canManage')
        ->with($user, $customer)
        ->andReturnFalse();

    expect($this->policy->update($user, $customer))->toBeFalse();
});

it('allows admin to destroy customer', function () {
    $admin = Mockery::mock(User::class)->makePartial();
    $admin->shouldReceive('isAdmin')->andReturnTrue();

    $customer = Mockery::mock(Customer::class)->makePartial();

    $this->policy = Mockery::mock(CustomerPolicy::class)->makePartial();
    $this->policy->shouldAllowMockingProtectedMethods()
        ->shouldReceive('canManage')
        ->with($admin, $customer)
        ->andReturnTrue();

    expect($this->policy->destroy($admin, $customer))->toBeTrue();
});


