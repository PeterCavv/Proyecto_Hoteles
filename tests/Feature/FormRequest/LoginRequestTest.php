<?php

use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;

beforeEach(function () {
    $this->request = new LoginRequest();
});

it('validates required email and password', function () {
    $rules = $this->request->rules();

    $validator = validator([], $rules);
    expect($validator->fails())->toBeTrue();

    $validator = validator(['email' => 'invalid-email', 'password' => ''], $rules);
    expect($validator->fails())->toBeTrue();

    $validator = validator(['email' => 'user@example.com', 'password' => 'secret'], $rules);
    expect($validator->passes())->toBeTrue();
});

it('throws validation exception when rate limited', function () {
    RateLimiter::shouldReceive('tooManyAttempts')->once()->andReturnTrue();
    RateLimiter::shouldReceive('availableIn')->once()->andReturn(30);

    $this->request->merge(['email' => 'user@example.com']);

    $this->expectException(ValidationException::class);
    $this->expectExceptionMessage(trans('auth.throttle', ['seconds' => 30, 'minutes' => 1]));

    $this->request->ensureIsNotRateLimited();
});

it('authenticates user successfully', function () {
    RateLimiter::shouldReceive('tooManyAttempts')->once()->andReturnFalse();
    RateLimiter::shouldReceive('clear')->once();

    Auth::shouldReceive('attempt')->once()->with(['email' => 'user@example.com', 'password' => 'secret'], false)->andReturnTrue();

    $this->request->merge(['email' => 'user@example.com', 'password' => 'secret', 'remember' => false]);

    $this->request->authenticate();
});

it('fails authentication and hits rate limiter', function () {
    RateLimiter::shouldReceive('tooManyAttempts')->once()->andReturnFalse();
    RateLimiter::shouldReceive('hit')->once();

    Auth::shouldReceive('attempt')->once()->andReturnFalse();

    $this->request->merge(['email' => 'user@example.com', 'password' => 'wrongpass', 'remember' => false]);

    $this->expectException(ValidationException::class);
    $this->expectExceptionMessage(trans('auth.failed'));

    $this->request->authenticate();
});

