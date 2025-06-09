<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $appName }} - {{ __('messages.pdf.report_title') }}</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; margin: 40px; }
        h2 { margin-bottom: 0; }
        p.description {
            margin-top: 0;
            font-size: 13px;
            color: #444;
            line-height: 1.4;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            page-break-inside: auto;
        }
        th, td {
            border: 1px solid #ccc;
            padding: 8px;
            text-align: left;
            vertical-align: top;
        }
        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }
        /* Anchos personalizados para columnas */
        th.comment, td.comment {
            width: 50%;
        }
        th.hotel, td.hotel {
            width: 30%;
        }
        th.date, td.date {
            width: 20%;
        }

        p.purpose {
            margin-top: 30px;
            font-size: 12px;
            color: #333;
            line-height: 1.5;
            font-style: italic;
        }

        p.signature {
            margin-top: 40px;
            font-style: italic;
            color: #333;
        }
    </style>
</head>
<body>
<h2>{{ $appName }} - {{ __('messages.pdf.report_title') }}</h2>
<p>{{ __('messages.pdf.report_subtitle', ['name' => $user->name]) }}</p>

<p class="description">
    {{ __('messages.pdf.description') }}
</p>

<table>
    <thead>
    <tr>
        <th class="comment">{{ __('messages.pdf.comment') }}</th>
        <th class="hotel">{{ __('messages.pdf.hotel') }}</th>
        <th class="date">{{ __('messages.pdf.date') }}</th>
    </tr>
    </thead>
    <tbody>
    @foreach ($reviews as $review)
        <tr>
            <td class="comment">{{ $review->review }}</td>
            <td class="hotel">{{ $review->hotel->name ?? __('messages.pdf.no_hotel') }}</td>
            <td class="date">{{ $review->published_at }}</td>
        </tr>
    @endforeach
    </tbody>
</table>

<p class="purpose">
    {{ __('messages.pdf.purpose_text') }}
</p>

<p class="signature">
    {{ __('messages.pdf.signature') }}
</p>
</body>
</html>

