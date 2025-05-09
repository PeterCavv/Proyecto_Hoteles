<?php

use App\Models\Customer;
use App\Models\Follow;
use App\Models\Hotel;
use App\Models\Reservation;
use App\Models\RoomType;
use app\Models\User;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

uses(RefreshDatabase::class);

it('has many reservations', function () {
    $roomType = RoomType::factory()->create();
    $reservation = Reservation::factory()->create(['room_type_id' => $roomType->id]);
    $reservation2 = Reservation::factory()->create(['room_type_id' => $roomType->id]);

    expect($roomType->reservations)->toHaveCount(2)
        ->and($roomType->reservations->first()->id)->toBe($reservation->id)
        ->and($roomType->reservations->last()->id)->toBe($reservation2->id);
});
