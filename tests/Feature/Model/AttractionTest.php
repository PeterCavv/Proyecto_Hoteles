<?php

use App\Models\City;
use App\Models\Attraction;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->city = City::factory()->create();
    $this->attraction = Attraction::factory()->create(['city_id' => $this->city->id]);
});

it('belongs to a city', function () {
    expect($this->attraction->city)->toBeInstanceOf(City::class)
        ->and($this->attraction->city->id)->toBe($this->city->id);
});

it('can filter attractions by city name and name of the attraction', function () {
    $madrid = City::factory()->create(['name' => 'Madrid']);
    $barcelona = City::factory()->create(['name' => 'Barcelona']);

    $attractionMadrid = Attraction::factory()->create([
        'name' => 'Museo del Prado',
        'city_id' => $madrid->id,
    ]);

    $attractionBarcelona = Attraction::factory()->create([
        'name' => 'Parc Güell',
        'city_id' => $barcelona->id,
    ]);

    $filtered = Attraction::filter(['city' => $madrid->id, 'name' => 'Museo'])->get();

    expect($filtered)->toHaveCount(1)
        ->and($filtered->first()->id)->toBe($attractionMadrid->id);
});

