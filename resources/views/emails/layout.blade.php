<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            background-color: #0A0A0A;
            color: #F8F5F0;
        }
        .email-container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #1A1A1A;
        }
        .email-header {
            background: linear-gradient(135deg, #D4AF37 0%, #B8941F 100%);
            padding: 40px 30px;
            text-align: center;
        }
        .email-header h1 {
            margin: 0;
            font-size: 32px;
            font-weight: 700;
            color: #0A0A0A;
            letter-spacing: 3px;
        }
        .email-header p {
            margin: 8px 0 0;
            font-size: 11px;
            color: #0A0A0A;
            letter-spacing: 2px;
            opacity: 0.8;
        }
        .email-body {
            padding: 40px 30px;
        }
        .email-body h2 {
            font-size: 24px;
            color: #D4AF37;
            margin: 0 0 20px;
            font-weight: 600;
        }
        .email-body p {
            line-height: 1.6;
            margin: 0 0 16px;
            color: #F8F5F0;
        }
        .button {
            display: inline-block;
            padding: 14px 32px;
            background: linear-gradient(135deg, #D4AF37, #B8941F);
            color: #0A0A0A;
            text-decoration: none;
            border-radius: 6px;
            font-weight: 600;
            letter-spacing: 0.5px;
            margin: 20px 0;
        }
        .order-details {
            background: #0A0A0A;
            border: 1px solid rgba(212, 175, 55, 0.2);
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
        }
        .order-details table {
            width: 100%;
            border-collapse: collapse;
        }
        .order-details td {
            padding: 10px 0;
            border-bottom: 1px solid rgba(212, 175, 55, 0.1);
        }
        .order-details td:first-child {
            color: rgba(248, 245, 240, 0.6);
            width: 40%;
        }
        .order-details td:last-child {
            color: #F8F5F0;
            font-weight: 600;
            text-align: right;
        }
        .order-details tr:last-child td {
            border-bottom: none;
        }
        .product-item {
            display: flex;
            padding: 15px 0;
            border-bottom: 1px solid rgba(212, 175, 55, 0.1);
        }
        .product-item:last-child {
            border-bottom: none;
        }
        .product-info {
            flex: 1;
        }
        .product-name {
            font-weight: 600;
            color: #D4AF37;
            margin-bottom: 5px;
        }
        .product-variant {
            font-size: 13px;
            color: rgba(248, 245, 240, 0.6);
        }
        .product-price {
            text-align: right;
            font-weight: 700;
            color: #D4AF37;
        }
        .total-row {
            background: rgba(212, 175, 55, 0.05);
            margin-top: 10px;
            padding: 15px;
            border-radius: 6px;
        }
        .total-row td:last-child {
            font-size: 20px;
            color: #D4AF37;
        }
        .email-footer {
            background: #0A0A0A;
            padding: 30px;
            text-align: center;
            border-top: 1px solid rgba(212, 175, 55, 0.2);
        }
        .email-footer p {
            margin: 5px 0;
            font-size: 13px;
            color: rgba(248, 245, 240, 0.6);
        }
        .social-links {
            margin: 20px 0;
        }
        .social-links a {
            display: inline-block;
            margin: 0 10px;
            color: #D4AF37;
            text-decoration: none;
            font-size: 20px;
        }
        .divider {
            height: 1px;
            background: linear-gradient(90deg, transparent, #D4AF37, transparent);
            margin: 30px 0;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <!-- Header -->
        <div class="email-header">
            <h1>VITTA</h1>
            <p>PERFUMES</p>
        </div>

        <!-- Body -->
        <div class="email-body">
            @yield('content')
        </div>

        <!-- Footer -->
        <div class="email-footer">
            <div class="social-links">
                <a href="#">📷</a>
                <a href="#">📘</a>
                <a href="#">🎵</a>
            </div>
            <p><strong>Vitta Perfumes</strong></p>
            <p>Fragancias árabes de lujo</p>
            <p style="margin-top: 20px;">
                ¿Necesitas ayuda? <a href="mailto:soporte@vittaperfumes.com" style="color: #D4AF37;">Contáctanos</a>
            </p>
            <p style="margin-top: 20px; font-size: 11px;">
                © {{ date('Y') }} Vitta Perfumes. Todos los derechos reservados.
            </p>
        </div>
    </div>
</body>
</html>
