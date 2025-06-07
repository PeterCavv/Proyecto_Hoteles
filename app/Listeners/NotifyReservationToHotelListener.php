<?php

namespace App\Listeners;

use App\Events\CreateReservationEvent;
use App\Mail\NewAlertHotelMail;
use Illuminate\Support\Facades\Mail;

class NotifyReservationToHotelListener
{
    public function __construct()
    {
    }

    public function handle(CreateReservationEvent $event): void
    {
        Mail::to($event->reservation->hotel->user->email)
            ->send(new NewAlertHotelMail($event->reservation));
    }
}
