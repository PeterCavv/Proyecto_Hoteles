<?php

use App\Jobs\DeleteReservationJob;
use App\Models\Reservation;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Bus;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('dispatches jobs to delete old reservations', function () {
    Bus::fake();

    $oldReservation1 = Reservation::factory()->create([
        'check_out' => Carbon::now()->subYears(2),
    ]);
    $oldReservation2 = Reservation::factory()->create([
        'check_out' => Carbon::now()->subYear()->subDay(),
    ]);

    $recentReservation = Reservation::factory()->create([
        'check_out' => Carbon::now()->subMonths(6),
    ]);

    $this->artisan('delete:old-reservations')
        ->expectsOutput(__('commands.old_reservations.starting'))
        ->expectsOutput(__('commands.old_reservations.found', ['count' => 2]))
        ->expectsOutput(__('commands.old_reservations.completed'))
        ->assertExitCode(0);

    Bus::assertDispatched(DeleteReservationJob::class, 2);
    Bus::assertDispatched(function (DeleteReservationJob $job) use ($oldReservation1, $oldReservation2) {
        return $job->reservation->is($oldReservation1) || $job->reservation->is($oldReservation2);
    });
    Bus::assertNotDispatched(function (DeleteReservationJob $job) use ($recentReservation) {
        return $job->reservation->is($recentReservation);
    });
});
