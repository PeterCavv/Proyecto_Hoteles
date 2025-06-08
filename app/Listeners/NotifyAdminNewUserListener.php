<?php

namespace App\Listeners;

use App\Enums\RoleEnum;
use App\Events\CreatedUserEvent;
use App\Mail\NewUserAlertMail;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

class NotifyAdminNewUserListener
{
    public function handle(CreatedUserEvent $event): void
    {
        $admins = User::role(RoleEnum::ADMIN->value)->get();

        if(!$admins->isEmpty()){
            foreach($admins as $admin){
                Mail::to($admin->email)
                    ->queue(new NewUserAlertMail($event->user));
            }
        }
    }
}
