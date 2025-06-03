<?php


use App\Enums\RoleEnum;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;

use function Pest\Laravel\actingAs;

uses(RefreshDatabase::class);

beforeEach(function () {
    Role::findOrCreate(RoleEnum::ADMIN->value);
    Role::findOrCreate(RoleEnum::CUSTOMER->value);
});

it('allows admin to access the user index', function () {
    $admin = User::factory()->create();
    $admin->assignRole(RoleEnum::ADMIN->value);

    actingAs($admin)
        ->get(route('users.index'))
        ->assertOk()
        ->assertInertia(fn ($page) =>
        $page->component('Admin/UserIndex')
            ->has('users')
            ->where('role.0', 'admin')
        );
});

it('forbids non-admin users from accessing the user index', function () {
    $user = User::factory()->create();
    $user->assignRole(RoleEnum::CUSTOMER->value);

    actingAs($user)
        ->get(route('users.index'))
        ->assertForbidden();
});

it('returns users with customer relation loaded', function () {
    $admin = User::factory()->create();
    $admin->assignRole('admin');

    $user = User::factory()->create();
    $user->assignRole(RoleEnum::CUSTOMER->value);
    $user->customer()->create([
        'dni' => '12345678A',
    ]);

    actingAs($admin)
        ->get(route('users.index'))
        ->assertOk()
        ->assertInertia(fn ($page) =>
        $page->component('Admin/UserIndex')
            ->has('users', 2)
            ->where('users.1.customer.user_id', $user->id)
        );
});

it('allows the user to update their own profile', function () {
    $user = User::factory()->create();

    $this->actingAs($user);

    $data = [
        'name' => 'Updated Name',
        'email' => 'updated@example.com',
        'phone_number' => '123456789',
        'city' => 'Updated City',
        'dni' => '12345678Z',
    ];

    $response = $this->put(route('profile.update', $user), $data);

    $response->assertRedirect(route('profile.show', $user));
    $response->assertSessionHas('success', 'Profile updated successfully.');

    $this->assertDatabaseHas('users', [
        'id' => $user->id,
        'name' => 'Updated Name',
        'email' => 'updated@example.com',
    ]);
});

it('allows an admin to update any user profile', function () {
    $admin = User::factory()->create();
    $user = User::factory()->create();

    $admin->assignRole(RoleEnum::ADMIN->value);
    $user->assignRole(RoleEnum::CUSTOMER->value);

    $this->actingAs($admin);

    $data = [
        'name' => 'Admin Updated Name',
        'email' => 'adminupdated@example.com',
        'phone_number' => '123456789',
        'city' => 'Admin Updated City',
    ];

    $response = $this->put(route('profile.update', $user), $data);

    $response->assertRedirect(route('profile.show', $user));
    $response->assertSessionHas('success', 'Profile updated successfully.');

    $this->assertDatabaseHas('users', [
        'id' => $user->id,
        'name' => 'Admin Updated Name',
        'email' => 'adminupdated@example.com',
        'phone_number' => '123456789',
        'city' => 'Admin Updated City',
    ]);
});

it('does not allow a non-admin user to update another user profile', function () {
    $user = User::factory()->create();
    $otherUser = User::factory()->create();

    $user->assignRole(RoleEnum::CUSTOMER->value);
    $otherUser->assignRole(RoleEnum::CUSTOMER->value);

    $this->actingAs($otherUser);

    $data = [
        'name' => 'Not Authorized Update',
        'email' => 'notauthorized@example.com',
        'phone_number' => '987654321',
        'city' => 'Unauthorized City',
    ];

    $response = $this->put(route('profile.update', $user), $data);

    $response->assertStatus(403);
});
