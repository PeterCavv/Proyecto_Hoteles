<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('shows the profile page', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get(route('profile.show', $user));

    $response->assertOk();
});

it('allows to update profile information', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->put(route('profile.update', $user->id), [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'phone_number' => '123456789',
        'city' => 'Test City',
    ]);

    $response->assertSessionHasNoErrors()
        ->assertRedirect(route('profile.show', $user->id));

    $user->refresh();

    expect($user->name)->toBe('Test User')
        ->and($user->email)->toBe('test@example.com');
});

it('does not change the email verification status if the email is the same', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->put(route('profile.update', $user), [
        'name' => 'Test User',
        'email' => $user->email,
        'phone_number' => '123456789',
        'city' => 'Test City',
    ]);

    $response->assertSessionHasNoErrors()
        ->assertRedirect(route('profile.show', $user));

    expect($user->refresh()->email_verified_at)->not()->toBeNull();
});

it('allows user to delete his own account', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->delete(route('profile.destroy', $user->id), [
        'password' => 'password',
    ]);

    $response->assertSessionHasNoErrors()
        ->assertRedirect('/');

    $this->assertGuest();
    expect($user->fresh()->deleted_at)->not()->toBeNull()
        ->and($user->getRoleNames())->toBeEmpty();
});

it('requires the correct password to delete the account', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)
        ->from(route('profile.show', $user))
        ->delete(route('profile.show', $user), [
            'password' => 'wrong-password',
        ]);

    $response->assertSessionHasErrors('password')
        ->assertRedirect(route('profile.show', $user));

    expect($user->fresh()->deleted_at)->toBeNull();
});
