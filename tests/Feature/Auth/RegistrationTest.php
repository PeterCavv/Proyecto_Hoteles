<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use Spatie\Permission\Models\Role;
use App\Enums\RoleEnum;

uses(RefreshDatabase::class);

it('can register and authenticate a new customer', function () {
    Role::findOrCreate(RoleEnum::CUSTOMER->value);

    $response = $this->post(route('register'), [
        'name' => 'Test User',
        'email' => 'testuser@example.com',
        'password' => 'Password123!',
        'password_confirmation' => 'Password123!',
        'dni' => '12345678A',
    ]);

    $response->assertRedirect(route('common.index'));

    $user = User::where('email', 'testuser@example.com')->first();

    expect($user)->not()->toBeNull()
        ->and($user->name)->toBe('Test User')
        ->and($user->hasRole(RoleEnum::CUSTOMER->value))->toBeTrue()
        ->and($user->customer)->not()->toBeNull()
        ->and($user->customer->dni)->toBe('12345678A');

    $this->assertAuthenticatedAs($user);
});

