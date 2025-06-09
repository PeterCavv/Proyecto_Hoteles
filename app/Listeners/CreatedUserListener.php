<?php

namespace App\Listeners;

use App\Events\CreatedUserEvent;
use App\Jobs\SendWelcomeEmailJob;
use App\Mail\CreatedUserConfirmationMail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;

class CreatedUserListener implements ShouldQueue
{
    public function handle(CreatedUserEvent $event): void
    {
        SendWelcomeEmailJob::dispatch($event->user);
    }
}
