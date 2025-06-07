<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>{{ __('emails.new_user.subject') }}</title>
        <style>
            body {
                font-family: sans-serif;
                background-color: #f4f4f4;
                padding: 20px;
            }
            .container {
                background-color: white;
                border-radius: 8px;
                max-width: 600px;
                margin: auto;
                padding: 30px;
                box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            }
            .btn {
                display: inline-block;
                background-color: #2d6cdf;
                color: white;
                padding: 12px 20px;
                text-decoration: none;
                border-radius: 6px;
                margin-top: 20px;
            }
            .footer {
                margin-top: 40px;
                font-size: 12px;
                color: #777;
                text-align: center;
            }
        </style>
    </head>
    <body>
        <div class="container">
            <h2>{{ __('emails.new_user.subject') }}</h2>

            <p>{{ __('emails.new_user.intro') }}</p>

            <ul>
                <li><strong>{{ __('emails.new_user.name') }}:</strong> {{ $user->name }}</li>
                <li><strong>{{ __('emails.new_user.email') }}:</strong> {{ $user->email }}</li>
                <li><strong>{{ __('emails.new_user.role') }}:</strong> {{ ucfirst($user->role_name ?? 'N/A') }}</li>
            </ul>

            <a href="{{ url('/users') }}" class="btn">{{ __('emails.new_user.view_users') }}</a>

            <div class="footer">
                {{ __('emails.new_user.thanks') }},<br>
                {{ config('app.name') }}
            </div>
        </div>
    </body>
</html>

