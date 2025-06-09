<?php

use App\Models\City;
use App\Models\Attraction;
use App\Models\Hotel;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->city = City::factory()->create();
});

it('can have many attractions', function () {
    $attraction1 = Attraction::factory()->create(['city_id' => $this->city->id]);
    $attraction2 = Attraction::factory()->create(['city_id' => $this->city->id]);

    $attractions = $this->city->attractions;

    expect($attractions)->toHaveCount(2)
        ->and($attractions->pluck('id'))->toContain($attraction1->id)
        ->and($attractions->pluck('id'))->toContain($attraction2->id);
});

it('can have many hotels', function () {
    $hotel1 = Hotel::factory()->create(['city_id' => $this->city->id]);
    $hotel2 = Hotel::factory()->create(['city_id' => $this->city->id]);

    $hotels = $this->city->hotels;

    expect($hotels)->toHaveCount(2)
        ->and($hotels->pluck('id'))->toContain($hotel1->id)
        ->and($hotels->pluck('id'))->toContain($hotel2->id);
});
