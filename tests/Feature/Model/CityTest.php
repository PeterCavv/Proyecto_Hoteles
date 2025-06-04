<?php

use App\Models\City;
use App\Models\Attraction;
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
