<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>{{ __('emails.welcome.subject', ['app' => config('app.name')]) }}</title>
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
        <h2>{{ __('emails.welcome.greeting', ['name' => $user->name]) }}</h2>

        <p>{{ __('emails.welcome.message_intro', ['app' => config('app.name')]) }}</p>

        <p>{{ __('emails.welcome.account_info', ['email' => $user->email]) }}</p>

        <a href="{{ url('/index') }}" class="btn">{{ __('emails.welcome.button') }}</a>

        <p>{{ __('emails.welcome.thanks') }}</p>
    </div>
    </body>
</html>


