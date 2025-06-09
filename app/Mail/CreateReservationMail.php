<?php

namespace App\Mail;

use App\Models\Reservation;
use Barryvdh\DomPDF\Facade\Pdf;
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
        $pdf = PDF::loadView('pdfs.reservation', ['reservation' => $this->reservation]);
        return $this->subject(__('emails.reservation_confirmation.subject'))
            ->view('emails.create-reservation')
            ->with([
                'reservation' => $this->reservation->load('hotel.features')
            ])
            ->attachData($pdf->output(), __('emails.reservation_confirmation.title_pdf')
                .$this->reservation->id.'.pdf',[
                    'mime' => 'application/pdf',
            ]);
    }
}
