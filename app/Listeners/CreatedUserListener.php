<?php

namespace App\Listeners;

use App\Events\CreatedUserEvent;
use App\Mail\CreatedUserConfirmationMail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;

class CreatedUserListener implements ShouldQueue
{
    public function handle(CreatedUserEvent $event): void
    {
        Mail::to($event->user->email)
            ->send(new CreatedUserConfirmationMail($event->user));
    }
}
