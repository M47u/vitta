<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link
        href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;500;600;700&family=Montserrat:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: 'Montserrat', sans-serif;
            background: #0A0A0A;
            color: #F8F5F0;
        }

        .auth-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 48px 24px;
        }

        .auth-card {
            width: 100%;
            max-width: 450px;
            background: #1A1A1A;
            border: 1px solid rgba(212, 175, 55, 0.2);
            border-radius: 12px;
            padding: 48px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.5);
        }

        .auth-logo {
            text-align: center;
            margin-bottom: 32px;
        }

        .auth-logo h1 {
            font-family: 'Cinzel', serif;
            color: #D4AF37;
            font-size: 32px;
            letter-spacing: 3px;
            margin-bottom: 8px;
        }

        .auth-logo p {
            color: rgba(212, 175, 55, 0.7);
            font-size: 11px;
            letter-spacing: 2px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-label {
            display: block;
            color: #D4AF37;
            font-weight: 600;
            margin-bottom: 8px;
            font-size: 14px;
            letter-spacing: 0.5px;
        }

        .form-control {
            width: 100%;
            padding: 14px 16px;
            background: #0A0A0A;
            border: 1px solid rgba(212, 175, 55, 0.3);
            color: #F8F5F0;
            border-radius: 6px;
            font-size: 14px;
            transition: all 0.3s;
        }

        .form-control:focus {
            outline: none;
            border-color: #D4AF37;
            box-shadow: 0 0 0 3px rgba(212, 175, 55, 0.1);
        }

        .btn-primary {
            width: 100%;
            background: linear-gradient(135deg, #D4AF37, #B8941F);
            color: #0A0A0A;
            border: none;
            padding: 14px 24px;
            font-weight: 600;
            border-radius: 6px;
            cursor: pointer;
            transition: all 0.3s;
            font-size: 15px;
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(212, 175, 55, 0.4);
        }

        .text-error {
            color: #ef4444;
            font-size: 13px;
            margin-top: 6px;
        }

        .link-gold {
            color: #D4AF37;
            text-decoration: none;
            font-size: 14px;
            transition: all 0.3s;
        }

        .link-gold:hover {
            color: #F4E4B8;
        }

        .divider {
            text-align: center;
            margin: 24px 0;
            position: relative;
        }

        .divider::before {
            content: '';
            position: absolute;
            left: 0;
            top: 50%;
            width: 100%;
            height: 1px;
            background: rgba(212, 175, 55, 0.2);
        }

        .divider span {
            position: relative;
            background: #1A1A1A;
            padding: 0 16px;
            color: rgba(248, 245, 240, 0.5);
            font-size: 13px;
        }
    </style>
</head>

<body>
    <div class="auth-container pattern-geometric">
        <div class="auth-card">

            <div class="auth-logo">
                <a href="{{ route('home') }}" style="text-decoration: none;">
                    <h1>VITTA</h1>
                    <p>PERFUMES</p>
                </a>
            </div>

            {{ $slot }}

        </div>
    </div>
</body>

</html>