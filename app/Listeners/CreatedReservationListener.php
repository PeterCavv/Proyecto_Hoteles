<?php

namespace App\Listeners;

use App\Events\CreateReservationEvent;
use App\Jobs\SendReservationEmailJob;

class CreatedReservationListener
{
    public function handle(CreateReservationEvent $event): void
    {
        SendReservationEmailJob::dispatch($event->reservation);
    }
}
