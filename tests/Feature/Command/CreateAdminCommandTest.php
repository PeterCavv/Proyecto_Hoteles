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

it('crea un usuario admin correctamente con datos válidos', function () {
    $exitCode = Artisan::call('create:admin', [
        'name' => 'Admin Test',
        'email' => 'admin@test.com',
        'password' => 'password123',
    ]);

    $output = Artisan::output();

    expect($exitCode)->toBe(0)
        ->and($output)->toContain("Usuario admin 'Admin Test' creado correctamente.");

    $user = User::where('email', 'admin@test.com')->first();

    expect($user)->not()->toBeNull()
        ->and($user->name)->toBe('Admin Test')
        ->and(Hash::check('password123', $user->password))->toBeTrue()
        ->and($user->hasRole('admin'))->toBeTrue();
});

it('no permite crear un admin con email ya existente', function () {
    User::factory()->create(['email' => 'exists@test.com']);

    $exitCode = Artisan::call('create:admin', [
        'name' => 'Someone',
        'email' => 'exists@test.com',
        'password' => 'password123',
    ]);

    $output = Artisan::output();

    expect($exitCode)->toBe(1)
        ->and($output)->toContain('El usuario con email exists@test.com ya existe.');
});

it('valida los datos y falla si son inválidos', function () {
    $exitCode = Artisan::call('create:admin', [
        'name' => '',
        'email' => 'not-an-email',
        'password' => 'short',
    ]);

    $output = Artisan::output();

    dump($output);

    expect($exitCode)->toBe(1)
        ->and(
            str_contains($output, 'El campo name es obligatorio') ||
            str_contains($output, 'El campo nombre es requerido')
        )->toBeTrue()
        ->and(
            str_contains($output, 'El campo email es requerido') ||
            str_contains($output, 'El campo email no es un correo válido')
        )->toBeTrue()
        ->and(
            str_contains($output, 'El campo password debe contener al menos 8 caracteres') ||
            str_contains($output, 'El campo password debe tener al menos 8 caracteres')
        )->toBeTrue();
});


