<?php

namespace Tests\Feature\Listener;

use App\Enums\RoleEnum;
use App\Events\CreatedUserEvent;
use App\Listeners\NotifyAdminNewUserListener;
use App\Mail\NewUserAlertMail;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Spatie\Permission\Models\Role;

uses(RefreshDatabase::class);

beforeEach(function () {
    Role::findOrCreate(RoleEnum::ADMIN->value);
});

it('sends email notification to admin when new user registers', function () {
    Mail::fake();

    $admin = User::factory()->create();
    $admin->assignRole(RoleEnum::ADMIN->value);

    $user = User::factory()->create();

    $event = new CreatedUserEvent($user);
    $listener = new NotifyAdminNewUserListener();
    $listener->handle($event);

    Mail::assertQueued(NewUserAlertMail::class, function ($mail) use ($user, $admin) {
        return $mail->hasTo($admin->email) &&
            $mail->user->id === $user->id;
    });
});



