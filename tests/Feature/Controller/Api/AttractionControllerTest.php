<?php

use App\Models\Attraction;
use App\Models\City;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->city = City::factory()->create();
    $this->user = User::factory()->create();
    $this->actingAs($this->user);
});

it('returns filtered attractions on index', function () {
    $city1 = City::factory()->create(['name' => 'Madrid']);
    $city2 = City::factory()->create(['name' => 'Barcelona']);

    Attraction::factory()->create([
        'city_id' => $city1->id,
        'name' => 'Parque Retiro',
    ]);
    Attraction::factory()->create([
        'city_id' => $city1->id,
        'name' => 'Museo Prado',
    ]);

    Attraction::factory()->create([
        'city_id' => $city2->id,
        'name' => 'La Sagrada Familia',
    ]);

    $response = $this->getJson(route('attractions.index'));
    $response->assertOk()->assertJsonCount(3);

    $response = $this->getJson(route('attractions.index', ['city' => 'Madrid']));
    $response->assertOk()
        ->assertJsonCount(2)
        ->assertJsonFragment(['name' => 'Parque Retiro'])
        ->assertJsonFragment(['name' => 'Museo Prado']);

    $response = $this->getJson(route('attractions.index', ['name' => 'Sagrada']));
    $response->assertOk()
        ->assertJsonCount(1)
        ->assertJsonFragment(['name' => 'La Sagrada Familia']);
});

it('returns a single attraction on show', function () {
    $attraction = Attraction::factory()->create(['city_id' => $this->city->id]);

    $response = $this->getJson(route('attractions.show', $attraction));

    $response->assertOk()
        ->assertJsonFragment(['id' => $attraction->id]);
});

it('stores a new attraction', function () {
    $payload = [
        'name' => 'New Attraction',
        'description' => 'Somewhere nice',
        'city_id' => $this->city->id,
    ];

    $response = $this->postJson(route('attractions.store'), $payload);

    $response->assertCreated()
        ->assertJsonFragment(['name' => 'New Attraction']);

    $this->assertDatabaseHas('attractions', ['name' => 'New Attraction']);
});

it('updates an attraction', function () {
    $attraction = Attraction::factory()->create(['city_id' => $this->city->id]);

    $payload = [
        'name' => 'Updated Name',
        'description' => 'Updated Desc',
        'city_id' => $this->city->id,
    ];

    $response = $this->putJson(route('attractions.update', $attraction), $payload);

    $response->assertOk()
        ->assertJsonFragment(['name' => 'Updated Name']);

    $this->assertDatabaseHas('attractions', ['name' => 'Updated Name']);
});

it('deletes an attraction', function () {
    $attraction = Attraction::factory()->create(['city_id' => $this->city->id]);

    $response = $this->deleteJson(route('attractions.destroy', $attraction));

    $response->assertNoContent();
    $this->assertDatabaseMissing('attractions', ['id' => $attraction->id]);
});
