<?php

use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('removes roles and permissions when a user is deleted', function () {
    $role = Role::create(['name' => 'editor']);
    $permission = Permission::create(['name' => 'edit articles']);

    $user = User::factory()->create();
    $user->assignRole($role);
    $user->givePermissionTo($permission);

    expect($user->roles)->toHaveCount(1)
        ->and($user->permissions)->toHaveCount(1);

    $user->delete();

    expect(DB::table('model_has_roles')->where('model_id', $user->id)->count())->toBe(0)
        ->and(DB::table('model_has_permissions')->where('model_id', $user->id)->count())->toBe(0);
});
