<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CreatedUserConfirmationMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public User $user;

    public function __construct($user)
    {
        $this->user = $user;
    }

    public function build()
    {
        return $this->subject('Confirmación de reserva')
            ->view('emails.created-user-confirmation');
    }
}
