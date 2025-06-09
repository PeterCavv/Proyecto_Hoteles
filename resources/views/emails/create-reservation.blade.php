<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>{{ __('emails.reservation_confirmation.subject') }}</title>
    </head>
    <body>
    <h1>{{ __('emails.reservation_confirmation.greeting', ['name' => $reservation->customer->user->name]) }}</h1>

    <p>{{ __('emails.reservation_confirmation.body') }}</p>

    <ul>
        <li><strong>{{ __('emails.reservation_confirmation.hotel') }}:</strong> {{ $reservation->hotel->name }}</li>
        <li><strong>{{ __('emails.reservation_confirmation.check_in') }}:</strong> {{ $reservation->check_in->format('d/m/Y') }}</li>
        <li><strong>{{ __('emails.reservation_confirmation.check_out') }}:</strong> {{ $reservation->check_out->format('d/m/Y') }}</li>
        <li><strong>{{ __('emails.reservation_confirmation.guests') }}:</strong> {{ $reservation->adults + $reservation->children }}</li>
        <li><strong>{{ __('emails.reservation_confirmation.price') }}:</strong> ${{ number_format($reservation->price, 2) }}</li>
    </ul>

    <p>{{ __('emails.reservation_confirmation.thank_you') }}</p>
    </body>
</html>
