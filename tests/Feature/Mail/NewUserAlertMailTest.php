<?php

use App\Mail\NewUserAlertMail;
use App\Models\User;

it('builds the new user alert mail correctly', function () {
    $user = User::factory()->make();

    $mail = new NewUserAlertMail($user);

    expect($mail->subject)->toBeNull();

    $mail->build();

    expect($mail->subject)->toBe(__('emails.new_user.subject'));

    $markdown = (function () {
        return $this->markdown;
    })->call($mail);

    expect($markdown)->toBe('emails.users.new-user-alert');

    $viewData = (function () {
        return $this->viewData;
    })->call($mail);

    expect($viewData['user'])->toBe($user);
});
