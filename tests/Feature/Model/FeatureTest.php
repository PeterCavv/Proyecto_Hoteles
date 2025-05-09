<?php

use App\Models\Feature;
use App\Models\Hotel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

uses(RefreshDatabase::class);

it('belongs to many hotels', function () {
    $feature = Feature::factory()->create();
    $hotel1 = Hotel::factory()->create();
    $hotel2 = Hotel::factory()->create();

    $feature->hotels()->attach([$hotel1->id, $hotel2->id]);

    expect($feature->hotels)->toHaveCount(2)
        ->and($feature->hotels->first()->id)->toBe($hotel1->id)
        ->and($hotel1->features->first()->id)->toBe($feature->id);
})->uses(TestCase::class);


