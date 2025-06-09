<?php

use App\Enums\RoleEnum;
use App\Models\Feature;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;

use function Pest\Laravel\{actingAs, get, post, put, delete};

uses(RefreshDatabase::class);

beforeEach(function () {
    Role::findOrCreate(RoleEnum::ADMIN->value);
    Role::findOrCreate(RoleEnum::CUSTOMER->value);

    $this->admin = User::factory()->create();
    $this->admin->assignRole(RoleEnum::ADMIN->value);
    actingAs($this->admin);
});

it('shows the feature index to admin', function () {
    Feature::factory()->create();

    get(route('feature.index'))
        ->assertOk()
        ->assertInertia(fn ($page) =>
        $page->component('Admin/FeaturesIndex')
            ->has('features', 1)
        );
});

it('shows a single feature', function () {
    $feature = Feature::factory()->create();

    get(route('feature.show', $feature))
        ->assertOk()
        ->assertInertia(fn ($page) =>
        $page->component('Admin/FeatureShow')
            ->where('feature.id', $feature->id)
        );
});

it('allows admin to store a new feature', function () {
    $data = [
        'name' => 'New Feature',
        'description' => 'Feature description',
        'icon' => 'icon-name',
    ];

    post(route('feature.store'), $data)
        ->assertRedirect(route('feature.index'));

    $this->assertDatabaseHas('features', [
        'name' => 'New Feature',
        'description' => 'Feature description',
        'icon' => 'icon-name',
    ]);
});

it('updates a feature as admin', function () {
    $feature = Feature::factory()->create();

    $data = [
        'name' => 'Updated Name',
        'description' => 'Updated description',
        'icon' => 'updated-icon',
    ];

    put(route('feature.update', $feature), $data)
        ->assertRedirect(route('feature.show', $feature));

    $this->assertDatabaseHas('features', [
        'id' => $feature->id,
        'name' => 'Updated Name',
    ]);
});

it('deletes a feature as admin', function () {
    $feature = Feature::factory()->create();

    delete(route('feature.destroy', $feature))
        ->assertRedirect(route('feature.index'));

    $this->assertDatabaseMissing('features', [
        'id' => $feature->id,
    ]);
});

it('forbids non-admin from accessing features', function () {
    $user = User::factory()->create();
    $user->assignRole(RoleEnum::CUSTOMER->value);
    actingAs($user);

    get(route('feature.index'))->assertForbidden();
    post(route('feature.store'), [])->assertForbidden();
    put(route('feature.update', Feature::factory()->create()), [])->assertForbidden();
    delete(route('feature.destroy', Feature::factory()->create()))->assertForbidden();
});

