<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="UTF-8">
        <title>{{ __('emails.pdf_reservation.title', ['id' => $reservation->id]) }}</title>
        <style>
            body {
                font-family: DejaVu Sans, sans-serif;
                margin: 40px;
                color: #2c3e50;
            }

            h1 {
                text-align: center;
                color: #34495e;
                margin-bottom: 10px;
            }

            h3 {
                margin-top: 40px;
                color: #2c3e50;
                border-bottom: 1px solid #ccc;
                padding-bottom: 5px;
            }

            .reservation-info {
                margin-top: 20px;
                border: 1px solid #ccc;
                border-radius: 6px;
                overflow: hidden;
            }

            table {
                width: 100%;
                border-collapse: collapse;
                background-color: #fdfdfd;
            }

            th, td {
                padding: 12px 15px;
                border-bottom: 1px solid #eee;
            }

            th {
                background-color: #ecf0f1;
                text-align: left;
                font-weight: bold;
            }

            tr:last-child td {
                border-bottom: none;
            }

            ul {
                list-style: none;
                padding: 0;
            }

            ul li {
                margin-bottom: 8px;
                padding-left: 15px;
                position: relative;
            }

            ul li::before {
                content: '✓';
                color: #27ae60;
                position: absolute;
                left: 0;
            }

            .qr-section {
                margin-top: 40px;
                text-align: center;
            }

            .qr-label {
                margin-top: 10px;
                font-size: 14px;
                color: #555;
            }

            footer {
                margin-top: 60px;
                text-align: center;
                font-style: italic;
                color: #888;
                font-size: 13px;
            }
        </style>
    </head>
    <body>
    <h1>{{ __('emails.pdf_reservation.title', ['id' => $reservation->id]) }}</h1>

    <div class="reservation-info">
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
    </div>

    <h3>{{ __('emails.pdf_reservation.hotel_features') }}</h3>
    <ul>
        @foreach ($reservation->hotel->features as $feature)
            <li><strong>{{ $feature->name }}:</strong> {{ $feature->description }}</li>
        @endforeach
    </ul>

    <div class="qr-section">
        {!! QrCode::size(120)->generate(route('reservations.show', $reservation)) !!}
        <div class="qr-label">
            {{ __('emails.pdf_reservation.qr_text') }}
        </div>
    </div>

    <footer>
        {{ __('emails.pdf_reservation.footer') }}
    </footer>
    </body>
</html>
