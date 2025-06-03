<?php


use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;

use function Pest\Laravel\actingAs;

uses(RefreshDatabase::class);

beforeEach(function () {
    Role::findOrCreate('admin');
    Role::findOrCreate('customer');
});

it('allows admin to access the user index', function () {
    $admin = User::factory()->create();
    $admin->assignRole('admin');

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
    $user->assignRole('customer');

    actingAs($user)
        ->get(route('users.index'))
        ->assertForbidden();
});

it('returns users with customer relation loaded', function () {
    $admin = User::factory()->create();
    $admin->assignRole('admin');

    $user = User::factory()->create();
    $user->assignRole('customer');
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
