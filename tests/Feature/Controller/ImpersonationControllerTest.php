<?php
use App\Enums\RoleEnum;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Foundation\Testing\RefreshDatabase;

use Spatie\Permission\Models\Role;
use function Pest\Laravel\actingAs;

uses(RefreshDatabase::class);

beforeEach(function () {
    Role::findOrCreate(RoleEnum::ADMIN->value);
    Role::findOrCreate(RoleEnum::CUSTOMER->value);
});

it('allows an admin to impersonate another user', function () {
    $admin = User::factory()->create();
    $admin->assignRole(RoleEnum::ADMIN->value);

    $targetUser = User::factory()->create();
    $targetUser->assignRole(RoleEnum::CUSTOMER->value);

    actingAs($admin)
        ->get(route('impersonate.start', $targetUser))
        ->assertRedirect(route('welcome'));

    expect(Auth::id())->toBe($targetUser->id)
        ->and(Session::get('impersonator_id'))->toBe($admin->id);
});

it('forbids non-admin users from impersonating', function () {
    $nonAdmin = User::factory()->create();
    $nonAdmin->assignRole(RoleEnum::CUSTOMER->value);

    $targetUser = User::factory()->create();

    actingAs($nonAdmin)
        ->get(route('impersonate.start', $targetUser))
        ->assertForbidden();
});

it('restores the original admin user after impersonation', function () {
    $admin = User::factory()->create();
    $admin->assignRole(RoleEnum::ADMIN->value);

    $targetUser = User::factory()->create();
    $targetUser->assignRole(RoleEnum::CUSTOMER->value);

    Session::put('impersonator_id', $admin->id);

    actingAs($targetUser)
        ->get(route('impersonate.stop'))
        ->assertRedirect(route('users.index'));

    expect(Auth::id())->toBe($admin->id)
        ->and(Session::has('impersonator_id'))->toBeFalse();
});

it('does nothing if impersonator_id is not in session', function () {
    $user = User::factory()->create();

    actingAs($user)
        ->get(route('impersonate.stop'))
        ->assertRedirect(route('users.index'));

    expect(Auth::id())->toBe($user->id);
});

