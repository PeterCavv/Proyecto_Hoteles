<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>{{ __('emails.hotel_reservation_notification.subject') }}</title>
    </head>
    <body>
    <h1>{{ __('emails.hotel_reservation_notification.greeting', ['hotel' => $reservation->hotel->name]) }}</h1>

    <p>{{ __('emails.hotel_reservation_notification.body') }}</p>

    <ul>
        <li><strong>{{ __('emails.hotel_reservation_notification.customer') }}:</strong> {{ $reservation->customer->user->name }}</li>
        <li><strong>{{ __('emails.hotel_reservation_notification.check_in') }}:</strong> {{ $reservation->check_in->format('d/m/Y') }}</li>
        <li><strong>{{ __('emails.hotel_reservation_notification.check_out') }}:</strong> {{ $reservation->check_out->format('d/m/Y') }}</li>
        <li><strong>{{ __('emails.hotel_reservation_notification.guests') }}:</strong> {{ $reservation->adults + $reservation->children }}</li>
        <li><strong>{{ __('emails.hotel_reservation_notification.price') }}:</strong> ${{ number_format($reservation->price, 2) }}</li>
    </ul>

    <p>{{ __('emails.hotel_reservation_notification.thank_you') }}</p>
    </body>
</html>
