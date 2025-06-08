<?php

use App\Enums\RoleEnum;
use App\Models\City;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;

uses(RefreshDatabase::class);

beforeEach(function () {
    Role::findOrCreate(RoleEnum::ADMIN->value);
    Role::findOrCreate(RoleEnum::CUSTOMER->value);

    $this->admin = User::factory()->create()->assignRole(RoleEnum::ADMIN->value);
    $this->nonAdmin = User::factory()->create()->assignRole(RoleEnum::CUSTOMER->value);
});

it('returns all cities on index', function () {
    City::factory()->count(3)->create();

    $response = $this->actingAs($this->admin)->getJson(route('cities.index'));

    $response->assertOk()
        ->assertJsonCount(3);
});

it('allows admin to store a city', function () {
    $payload = [
        'name' => 'New City',
        'country' => 'Wonderland'
    ];

    $response = $this->actingAs($this->admin)->postJson(route('cities.store'), $payload);

    $response->assertCreated()
        ->assertJsonFragment(['name' => 'New City']);

    $this->assertDatabaseHas('cities', ['name' => 'New City']);
});

it('denies non-admin from storing a city', function () {
    $payload = [
        'name' => 'Forbidden City',
        'country' => 'Nowhere'
    ];

    $response = $this->actingAs($this->nonAdmin)->postJson(route('cities.store'), $payload);

    $response->assertForbidden();

    $this->assertDatabaseMissing('cities', ['name' => 'Forbidden City']);
});

it('allows admin to update a city', function () {
    $city = City::factory()->create([
        'name' => 'Old Name',
        'country' => 'Oldland'
    ]);

    $payload = [
        'name' => 'Updated Name',
        'country' => 'Newland'
    ];

    $response = $this->actingAs($this->admin)->putJson(route('cities.update', $city), $payload);

    $response->assertOk()
        ->assertJsonFragment(['name' => 'Updated Name']);

    $this->assertDatabaseHas('cities', ['name' => 'Updated Name']);
});

it('denies non-admin from updating a city', function () {
    $city = City::factory()->create([
        'name' => 'Do Not Change',
        'country' => 'Noland'
    ]);

    $payload = [
        'name' => 'Changed Name',
        'country' => 'Changedland'
    ];

    $response = $this->actingAs($this->nonAdmin)->putJson(route('cities.update', $city), $payload);

    $response->assertForbidden();

    $this->assertDatabaseHas('cities', ['name' => 'Do Not Change']);
});

it('allows an admin to delete a city', function () {
    $city = City::factory()->create();

    $response =  $this->actingAs($this->admin)->deleteJson(route('cities.destroy', $city));

    $response->assertNoContent();

    $this->assertDatabaseMissing('cities', ['id' => $city->id]);
});

it('denies non-admin to delete a city', function () {
    $city = City::factory()->create();

    $response =  $this->actingAs($this->nonAdmin)->deleteJson(route('cities.destroy', $city));

    $response->assertForbidden();

    $this->assertDatabaseHas('cities', ['id' => $city->id]);
});

