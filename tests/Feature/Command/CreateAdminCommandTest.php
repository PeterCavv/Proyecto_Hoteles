<?php

use App\Enums\RoleEnum;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Artisan;
use Spatie\Permission\Models\Role;

uses(RefreshDatabase::class);

beforeEach(function () {
    Role::findOrCreate(RoleEnum::ADMIN->value);
});

it('creates an admin user correctly with validated data', function () {
    $exitCode = Artisan::call('create:admin', [
        'name' => 'Admin Test',
        'email' => 'admin@test.com',
        'password' => 'password123',
    ]);

    $output = Artisan::output();

    expect($exitCode)->toBe(0)
        ->and($output)->toContain(__('commands.create_admin.created', ['name' => 'Admin Test']));

    $user = User::where('email', 'admin@test.com')->first();

    expect($user)->not()->toBeNull()
        ->and($user->name)->toBe('Admin Test')
        ->and(Hash::check('password123', $user->password))->toBeTrue()
        ->and($user->hasRole('admin'))->toBeTrue();
});

it('forbids creating an admin with an existing email', function () {
    User::factory()->create(['email' => 'exists@test.com']);

    $exitCode = Artisan::call('create:admin', [
        'name' => 'Someone',
        'email' => 'exists@test.com',
        'password' => 'password123',
    ]);

    $output = Artisan::output();

    expect($exitCode)->toBe(1)
        ->and($output)->toContain(__('commands.create_admin.user_exists', ['email' => 'exists@test.com']));
});

it('validates data and fails if they are invalid', function () {
    $exitCode = Artisan::call('create:admin', [
        'name' => '',
        'email' => 'not-an-email',
        'password' => 'short',
    ]);

    $output = Artisan::output();

    expect($exitCode)->toBe(1)
        ->and(str_contains($output, __('validation.required', ['attribute' => 'name'])))
        ->and(str_contains($output, __('validation.email', ['attribute' => 'email'])))
        ->and(str_contains($output, __('validation.min.string', ['attribute' => 'password', 'min' => 8])));
});


