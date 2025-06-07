<?php

namespace App\Mail;

use App\Models\Reservation;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NewAlertHotelMail extends Mailable
{
    use Queueable, SerializesModels;

    public Reservation $reservation;

    public function __construct($reservation)
    {
        $this->reservation = $reservation;
    }

    public function build()
    {
        return $this->subject(__('emails.hotel_reservation_notification.subject'))
            ->view('emails.new-alert-hotel')
            ->with([
                'reservation' => $this->reservation,
            ]);
    }
}
