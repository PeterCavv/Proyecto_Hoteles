<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="UTF-8" />
        <title>{{ __('emails.pdf_reservation.title', ['id' => $reservation->id]) }}</title>
        <style>
            body { font-family: DejaVu Sans, sans-serif; margin: 30px; }
            h1 { text-align: center; color: #2c3e50; }
            table { width: 100%; border-collapse: collapse; margin-top: 20px; }
            th, td { padding: 10px; border: 1px solid #ddd; text-align: left; }
            th { background-color: #f5f5f5; }
            footer { margin-top: 40px; text-align: center; font-style: italic; color: #888; }
        </style>
    </head>
    <body>
    <h1>{{ __('emails.pdf_reservation.title', ['id' => $reservation->id]) }}</h1>

    <table>
        <tr>
            <th>{{ __('emails.pdf_reservation.customer') }}</th>
            <td>{{ $reservation->customer->user->name }}</td>
        </tr>
        <tr>
            <th>{{ __('emails.pdf_reservation.hotel') }}</th>
            <td>{{ $reservation->hotel->name }}</td>
        </tr>
        <tr>
            <th>{{ __('emails.pdf_reservation.room_type') }}</th>
            <td>{{ $reservation->roomType->name }}</td>
        </tr>
        <tr>
            <th>{{ __('emails.pdf_reservation.check_in') }}</th>
            <td>{{ $reservation->check_in->format('d/m/Y') }}</td>
        </tr>
        <tr>
            <th>{{ __('emails.pdf_reservation.check_out') }}</th>
            <td>{{ $reservation->check_out->format('d/m/Y') }}</td>
        </tr>
        <tr>
            <th>{{ __('emails.pdf_reservation.adults') }}</th>
            <td>{{ $reservation->adults }}</td>
        </tr>
        <tr>
            <th>{{ __('emails.pdf_reservation.children') }}</th>
            <td>{{ $reservation->children }}</td>
        </tr>
        <tr>
            <th>{{ __('emails.pdf_reservation.price') }}</th>
            <td>{{ __('emails.pdf_reservation.currency') }} {{ number_format($reservation->price, 2) }}</td>
        </tr>
    </table>

    <h3>{{ __('emails.pdf_reservation.hotel_features') }}</h3>
    <ul>
        @foreach ($reservation->hotel->features as $feature)
            <li>{{ $feature->name }} - {{ $feature->description }}</li>
        @endforeach
    </ul>

    <footer>
        {{ __('emails.pdf_reservation.footer') }}
    </footer>
    </body>
</html>
