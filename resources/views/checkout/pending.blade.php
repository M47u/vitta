@extends('layouts.app')

@section('title', 'Pago Pendiente')

@section('content')
    <div class="vitta-container" style="padding: 80px 24px;">
        <div style="max-width: 800px; margin: 0 auto; text-align: center;">

            <!-- Pending Icon -->
            <div
                style="width: 120px; height: 120px; border-radius: 50%; background: rgba(245, 158, 11, 0.1); border: 3px solid #f59e0b; margin: 0 auto 32px; display: flex; align-items: center; justify-content: center;">
                <i class="bi bi-clock-history" style="font-size: 64px; color: #f59e0b;"></i>
            </div>

            <h1 style="font-size: 42px; color: #f59e0b; margin-bottom: 16px;">
                Pago Pendiente
            </h1>

            <p style="font-size: 18px; color: var(--vitta-pearl); opacity: 0.8; margin-bottom: 32px;">
                Tu pago está siendo procesado
            </p>

            <!-- Order Number -->
            <div
                style="background: var(--vitta-black-soft); border: 2px solid #f59e0b; border-radius: 8px; padding: 24px; margin-bottom: 32px;">
                <p style="color: var(--vitta-pearl); opacity: 0.7; font-size: 14px; margin-bottom: 8px;">
                    Número de Pedido
                </p>
                <p style="font-size: 28px; color: #f59e0b; font-weight: 600; letter-spacing: 2px;">
                    {{ $order->order_number }}
                </p>
            </div>

            <!-- Info -->
            <div
                style="background: rgba(245, 158, 11, 0.1); border: 1px solid #f59e0b; border-radius: 8px; padding: 24px; margin-bottom: 32px;">
                <div style="display: flex; align-items: start; gap: 16px;">
                    <i class="bi bi-info-circle-fill" style="color: #f59e0b; font-size: 32px; flex-shrink: 0;"></i>
                    <div style="text-align: left;">
                        <h4 style="color: #f59e0b; font-size: 18px; margin-bottom: 12px;">
                            ¿Qué significa esto?
                        </h4>
                        <p style="color: var(--vitta-pearl); font-size: 14px; line-height: 1.6; margin-bottom: 12px;">
                            Tu pago está siendo procesado por MercadoPago. Esto puede ocurrir cuando:
                        </p>
                        <ul style="color: var(--vitta-pearl); font-size: 14px; line-height: 1.8; padding-left: 24px;">
                            <li>Elegiste pagar en efectivo (Rapipago, Pago Fácil, etc.)</li>
                            <li>La transacción con tarjeta requiere verificación adicional</li>
                            <li>Tu banco está procesando la transacción</li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- What's Next -->
            <div
                style="background: var(--vitta-black-soft); border: 1px solid var(--vitta-gray); border-radius: 8px; padding: 24px; margin-bottom: 32px; text-align: left;">
                <h4 style="color: var(--vitta-gold); font-size: 18px; margin-bottom: 16px;">
                    ¿Qué sigue ahora?
                </h4>
                <div style="display: flex; flex-direction: column; gap: 12px;">
                    <div style="display: flex; align-items: start; gap: 12px;">
                        <i class="bi bi-1-circle-fill"
                            style="color: var(--vitta-gold); font-size: 24px; flex-shrink: 0;"></i>
                        <p style="color: var(--vitta-pearl); font-size: 14px;">
                            Recibirás un correo con el estado de tu pago
                        </p>
                    </div>
                    <div style="display: flex; align-items: start; gap: 12px;">
                        <i class="bi bi-2-circle-fill"
                            style="color: var(--vitta-gold); font-size: 24px; flex-shrink: 0;"></i>
                        <p style="color: var(--vitta-pearl); font-size: 14px;">
                            Una vez aprobado, procesaremos tu pedido inmediatamente
                        </p>
                    </div>
                    <div style="display: flex; align-items: start; gap: 12px;">
                        <i class="bi bi-3-circle-fill"
                            style="color: var(--vitta-gold); font-size: 24px; flex-shrink: 0;"></i>
                        <p style="color: var(--vitta-pearl); font-size: 14px;">
                            Te notificaremos cuando tu pedido esté en camino
                        </p>
                    </div>
                </div>
            </div>

            <!-- Payment Details (if cash payment) -->
            <div
                style="background: rgba(212, 175, 55, 0.1); border: 1px solid var(--vitta-gold); border-radius: 8px; padding: 24px; margin-bottom: 32px; text-align: left;">
                <h4 style="color: var(--vitta-gold); font-size: 16px; margin-bottom: 12px;">
                    <i class="bi bi-cash-stack"></i> Información de Pago
                </h4>
                <p style="color: var(--vitta-pearl); font-size: 14px; line-height: 1.6;">
                    Si elegiste pagar en efectivo, revisa tu correo electrónico para encontrar las instrucciones de pago y
                    el código de barras.
                </p>
            </div>

            <!-- Actions -->
            <div style="display: flex; gap: 16px; justify-content: center; flex-wrap: wrap;">
                <a href="{{ route('home') }}" class="btn-gold" style="text-decoration: none;">
                    <i class="bi bi-house-fill"></i> VOLVER AL INICIO
                </a>
                <a href="{{ route('products.index') }}"
                    style="padding: 14px 32px; border: 2px solid var(--vitta-gold); color: var(--vitta-gold); text-decoration: none; font-weight: 600; letter-spacing: 0.5px; text-transform: uppercase; border-radius: 4px; display: inline-flex; align-items: center; gap: 8px;">
                    <i class="bi bi-bag-fill"></i> SEGUIR COMPRANDO
                </a>
            </div>

            <p style="color: var(--vitta-pearl); opacity: 0.6; font-size: 13px; margin-top: 32px;">
                Podés consultar el estado de tu pedido en
                <a href="mailto:contacto@vittaperfumes.com" style="color: var(--vitta-gold);">contacto@vittaperfumes.com</a>
            </p>
        </div>
    </div>
@endsection