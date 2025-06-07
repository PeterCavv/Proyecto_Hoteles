<?php

namespace App\Mail;

use App\Models\Reservation;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CreateReservationMail extends Mailable
{
    use Queueable, SerializesModels;

    public Reservation $reservation;

    public function __construct($reservation)
    {
        $this->reservation = $reservation;
    }

    public function build()
    {
        return $this->subject(__('emails.reservation_confirmation.subject'))
            ->view('emails.create-reservation')
            ->with([
                'reservation' => $this->reservation,
            ]);
    }
}
