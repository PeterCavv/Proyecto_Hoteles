<?php

use App\Models\Customer;
use App\Models\Follow;
use App\Models\Hotel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->customer = Customer::factory()->create();
    $this->hotel = Hotel::factory()->create();
    $this->follow = Follow::factory()->create([
        'customer_id' => $this->customer->id,
        'hotel_id' => $this->hotel->id,
    ]);
});

it('belongs to a customer', function () {
    expect($this->follow->customer)->toBeInstanceOf(Customer::class)
        ->and($this->follow->customer->id)->toBe($this->customer->id);
})->uses(TestCase::class);

it('belongs to a hotel', function () {
    expect($this->follow->hotel)->toBeInstanceOf(Hotel::class)
        ->and($this->follow->hotel->id)->toBe($this->hotel->id);
})->uses(TestCase::class);

it('returns follows for a specific customer', function () {
    $follow1 = Follow::factory()->create(['customer_id' => $this->customer->id]);

    $followedFollows = Follow::followed($this->customer->id)->get();

    expect($followedFollows)->toHaveCount(2)
        ->and($followedFollows->pluck('id'))->toContain($this->follow->id)
        ->and($followedFollows->pluck('id'))->toContain($follow1->id);
});
