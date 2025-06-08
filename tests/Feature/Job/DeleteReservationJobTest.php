<?php

namespace Tests\Feature\Job;

use App\Jobs\DeleteReservationJob;
use App\Models\Reservation;
use Illuminate\Foundation\Testing\RefreshDatabase;


uses(RefreshDatabase::class);


it('deletes the reservation when the job is handled', function () {
    $reservation = Reservation::factory()->create();

    expect(Reservation::find($reservation->id))->not->toBeNull();

    (new DeleteReservationJob($reservation))->handle();

    expect(Reservation::find($reservation->id))->toBeNull();
});



