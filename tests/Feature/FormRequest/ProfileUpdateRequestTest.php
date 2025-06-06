<?php

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Unique;

uses(RefreshDatabase::class);

it('has the correct validation rules', function () {
    $user = User::factory()->create();

    $request = new ProfileUpdateRequest();
    $request->setUserResolver(fn () => $user);

    $rules = $request->rules();

    expect($rules)->toHaveKeys(['name', 'email'])
        ->and($rules['name'])->toBe(['required', 'string', 'max:255'])
        ->and($rules['email'])->toContain('required', 'string', 'lowercase', 'email', 'max:255');

    $emailRule = collect($rules['email'])->first(fn ($rule) => $rule instanceof Unique);
    expect($emailRule)->not->toBeNull();
});

it('passes validation with valid data', function () {
    $user = User::factory()->create();

    $data = [
        'name' => 'Test User',
        'email' => 'test@example.com',
    ];

    $request = new ProfileUpdateRequest();
    $request->setUserResolver(fn () => $user);

    $validator = Validator::make($data, $request->rules());

    expect($validator->passes())->toBeTrue();
});

it('fails validation with invalid email', function () {
    $user = User::factory()->create();

    $data = [
        'name' => 'Test User',
        'email' => 'invalid-email',
    ];

    $request = new ProfileUpdateRequest();
    $request->setUserResolver(fn () => $user);

    $validator = Validator::make($data, $request->rules());

    expect($validator->fails())->toBeTrue()
        ->and($validator->errors()->has('email'))->toBeTrue();
});
