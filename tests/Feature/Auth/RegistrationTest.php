<?php

namespace Tests\Feature\Auth;

use App\Events\CreatedUserEvent;
use Event;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use App\Enums\RoleEnum;

uses(RefreshDatabase::class);

it('can register and authenticate a new customer', function () {
    Role::findOrCreate(RoleEnum::ADMIN->value);
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

it('can restore a soft-deleted customer and log them in', function () {
    Event::fake();
    Role::findOrCreate(RoleEnum::ADMIN->value);
    Role::findOrCreate(RoleEnum::CUSTOMER->value);

    $user = User::factory()->create([
        'email' => 'deleted@example.com',
        'name' => 'Old Name',
        'password' => Hash::make('OldPassword123!'),
    ]);
    $user->assignRole(RoleEnum::CUSTOMER->value);
    $user->customer()->create([
        'dni' => '87654321Z',
    ]);
    $user->delete();
    $user->customer()->delete();

    $this->assertSoftDeleted('users', ['email' => 'deleted@example.com']);
    $this->assertSoftDeleted('customers', ['dni' => '87654321Z']);

    $response = $this->post(route('register'), [
        'name' => 'Restored Name',
        'email' => 'deleted@example.com',
        'password' => 'NewPassword123!',
        'password_confirmation' => 'NewPassword123!',
        'dni' => '11112222B',
    ]);

    Event::assertDispatched(Registered::class);
    Event::assertDispatched(CreatedUserEvent::class);

    $response->assertRedirect(route('common.index'));

    $restoredUser = User::where('email', 'deleted@example.com')->first();

    expect($restoredUser)->not()->toBeNull()
        ->and($restoredUser->trashed())->toBeFalse()
        ->and($restoredUser->name)->toBe('Restored Name')
        ->and($restoredUser->hasRole(RoleEnum::CUSTOMER->value))->toBeTrue()
        ->and($restoredUser->customer)->not()->toBeNull()
        ->and($restoredUser->customer->dni)->toBe('11112222B');

    $this->assertAuthenticatedAs($restoredUser);
});


