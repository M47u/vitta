<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recordatorio de Pago</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #0A0A0A;
            color: #F8F5F0;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background: #1A1A1A;
            border-radius: 8px;
            overflow: hidden;
        }
        .header {
            background: linear-gradient(135deg, #D4AF37, #B8941F);
            padding: 40px 20px;
            text-align: center;
        }
        .header h1 {
            color: #0A0A0A;
            font-size: 32px;
            margin: 0 0 8px 0;
            letter-spacing: 3px;
        }
        .header p {
            color: rgba(10, 10, 10, 0.8);
            font-size: 14px;
            margin: 0;
            letter-spacing: 2px;
        }
        .content {
            padding: 40px 32px;
        }
        .alert-box {
            background: rgba(59, 130, 246, 0.1);
            border: 2px solid #3b82f6;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 32px;
            text-align: center;
        }
        .alert-icon {
            font-size: 48px;
            margin-bottom: 12px;
        }
        .alert-box h2 {
            color: #3b82f6;
            font-size: 22px;
            margin: 0 0 12px 0;
        }
        .alert-box p {
            color: #F8F5F0;
            opacity: 0.9;
            margin: 0;
            line-height: 1.6;
        }
        .order-box {
            background: rgba(212, 175, 55, 0.1);
            border: 1px solid rgba(212, 175, 55, 0.3);
            border-radius: 8px;
            padding: 24px;
            margin-bottom: 24px;
        }
        .order-number {
            text-align: center;
            font-size: 28px;
            color: #D4AF37;
            font-weight: bold;
            margin-bottom: 16px;
            letter-spacing: 2px;
        }
        .bank-details {
            background: rgba(16, 185, 129, 0.05);
            border: 2px solid #10b981;
            border-radius: 8px;
            padding: 24px;
            margin-bottom: 24px;
        }
        .bank-details h3 {
            color: #10b981;
            font-size: 18px;
            margin: 0 0 20px 0;
        }
        .detail-row {
            display: flex;
            justify-content: space-between;
            padding: 12px 0;
            border-bottom: 1px solid rgba(16, 185, 129, 0.2);
        }
        .detail-row:last-child {
            border-bottom: none;
        }
        .detail-label {
            color: rgba(248, 245, 240, 0.7);
            font-size: 14px;
        }
        .detail-value {
            color: #F8F5F0;
            font-weight: 600;
            text-align: right;
        }
        .cbu {
            color: #10b981 !important;
            font-family: 'Courier New', monospace;
            letter-spacing: 1px;
            font-weight: 700;
        }
        .alias {
            color: #10b981 !important;
            font-size: 16px;
            font-weight: 700;
        }
        .btn {
            display: inline-block;
            padding: 16px 40px;
            background: linear-gradient(135deg, #3b82f6, #2563eb);
            color: white !important;
            text-decoration: none;
            border-radius: 6px;
            font-weight: 600;
            font-size: 16px;
            text-align: center;
            margin: 20px auto;
            display: block;
            width: fit-content;
        }
        .info-box {
            background: rgba(212, 175, 55, 0.1);
            border-left: 4px solid #D4AF37;
            padding: 16px;
            margin: 24px 0;
            border-radius: 4px;
        }
        .info-box p {
            margin: 0;
            color: #F8F5F0;
            opacity: 0.9;
            font-size: 14px;
            line-height: 1.6;
        }
        .footer {
            background: #0A0A0A;
            padding: 24px;
            text-align: center;
            border-top: 1px solid rgba(212, 175, 55, 0.2);
        }
        .footer p {
            color: rgba(248, 245, 240, 0.6);
            font-size: 13px;
            margin: 8px 0;
        }
        .footer a {
            color: #D4AF37;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <h1>VITTA</h1>
            <p>PERFUMES</p>
        </div>

        <!-- Content -->
        <div class="content">
            <!-- Alert -->
            <div class="alert-box">
                <div class="alert-icon">⏰</div>
                <h2>¡Tu pedido está esperando!</h2>
                <p>
                    Hola <strong>{{ $order->user ? $order->user->name : $order->guest_name }}</strong>,<br>
                    Notamos que aún no confirmaste el pago de tu pedido. 
                </p>
            </div>

            <!-- Order Number -->
            <div class="order-box">
                <div class="order-number">
                    #{{ $order->order_number }}
                </div>
                <div style="text-align: center;">
                    <p style="color: #F8F5F0; opacity: 0.7; font-size: 14px; margin: 0 0 8px 0;">Monto a transferir:</p>
                    <p style="font-size: 32px; color: #D4AF37; font-weight: 700; margin: 0;">
                        ${{ number_format($order->total, 0, ',', '.') }}
                    </p>
                </div>
            </div>

            @php
                $bankName = \App\Models\Setting::get('bank_name', '');
                $bankHolder = \App\Models\Setting::get('bank_holder', '');
                $bankCbu = \App\Models\Setting::get('bank_cbu', '');
                $bankAlias = \App\Models\Setting::get('bank_alias', '');
                $bankCuit = \App\Models\Setting::get('bank_cuit', '');
                $bankAccType = \App\Models\Setting::get('bank_account_type', '');
            @endphp

            <!-- Bank Details -->
            <div class="bank-details">
                <h3>💳 Datos para la Transferencia</h3>
                <div class="detail-row">
                    <span class="detail-label">Banco:</span>
                    <span class="detail-value">{{ $bankName }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Tipo de cuenta:</span>
                    <span class="detail-value">{{ $bankAccType }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Titular:</span>
                    <span class="detail-value">{{ $bankHolder }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">CUIT/CUIL:</span>
                    <span class="detail-value">{{ $bankCuit }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">CBU:</span>
                    <span class="detail-value cbu">{{ $bankCbu }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Alias:</span>
                    <span class="detail-value alias">{{ $bankAlias }}</span>
                </div>
            </div>

            <div class="info-box">
                <p>
                    <strong>💡 Importante:</strong> Recordá indicar el número de pedido 
                    <strong style="color: #D4AF37;">{{ $order->order_number }}</strong> 
                    en el concepto de la transferencia.
                </p>
            </div>

            <!-- CTA Button -->
            <a href="{{ route('checkout.success', $order) }}" class="btn">
                📤 Subir Comprobante de Pago
            </a>

            <div style="text-align: center; margin-top: 24px;">
                <p style="color: rgba(248, 245, 240, 0.7); font-size: 14px; line-height: 1.6;">
                    Una vez que realices la transferencia, <strong>subí tu comprobante</strong> desde el botón de arriba 
                    para que podamos confirmar tu pedido más rápido. ⚡
                </p>
            </div>

            <!-- Order Summary -->
            <div style="margin-top: 32px; padding-top: 24px; border-top: 1px solid rgba(212, 175, 55, 0.2);">
                <h3 style="color: #D4AF37; font-size: 16px; margin-bottom: 16px;">Resumen del Pedido</h3>
                @foreach($order->items as $item)
                <div style="display: flex; justify-content: space-between; margin-bottom: 12px;">
                    <span style="color: #F8F5F0; font-size: 14px;">
                        {{ $item->product_name }} 
                        @if($item->variant_name)
                            <span style="opacity: 0.6;">- {{ $item->variant_name }}</span>
                        @endif
                        <span style="opacity: 0.6;"> x{{ $item->quantity }}</span>
                    </span>
                    <span style="color: #D4AF37; font-weight: 600;">
                        ${{ number_format($item->subtotal, 0, ',', '.') }}
                    </span>
                </div>
                @endforeach
                
                <div style="border-top: 1px solid rgba(212, 175, 55, 0.2); margin-top: 16px; padding-top: 16px;">
                    <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
                        <span style="color: rgba(248, 245, 240, 0.7); font-size: 14px;">Subtotal:</span>
                        <span style="color: #F8F5F0;">${{ number_format($order->subtotal, 0, ',', '.') }}</span>
                    </div>
                    <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
                        <span style="color: rgba(248, 245, 240, 0.7); font-size: 14px;">Envío:</span>
                        <span style="color: #F8F5F0;">${{ number_format($order->shipping, 0, ',', '.') }}</span>
                    </div>
                    @if($order->discount > 0)
                    <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
                        <span style="color: #10b981; font-weight: 600; font-size: 14px;">Descuento (5%):</span>
                        <span style="color: #10b981; font-weight: 600;">-${{ number_format($order->discount, 0, ',', '.') }}</span>
                    </div>
                    @endif
                    <div style="display: flex; justify-content: space-between; margin-top: 12px; padding-top: 12px; border-top: 2px solid #D4AF37;">
                        <span style="color: #D4AF37; font-weight: 700; font-size: 18px;">Total:</span>
                        <span style="color: #D4AF37; font-weight: 700; font-size: 18px;">${{ number_format($order->total, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p>¿Necesitás ayuda? <a href="mailto:contacto@vittaperfumes.com">Contactanos</a></p>
            <p style="margin-top: 16px;">
                © {{ date('Y') }} Vitta Perfumes. Todos los derechos reservados.
            </p>
        </div>
    </div>
</body>
</html>
