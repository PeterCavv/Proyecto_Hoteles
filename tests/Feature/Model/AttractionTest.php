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

