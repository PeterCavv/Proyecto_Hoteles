<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Bienvenido a {{ config('app.name') }}</title>
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
    <h2>¡Hola {{ $user->name }}!</h2>

    <p>Bienvenido a <strong>Hotelfinder</strong>. Tu cuenta ha sido creada con éxito.</p>

    <p>Puedes ingresar a tu cuenta con tu correo: <strong>{{ $user->email }}</strong>.</p>

    <a href="{{ url('/index') }}" class="btn">Iniciar sesión</a>

    <p>¡Gracias por registrarte!</p>
</div>
</body>
</html>

