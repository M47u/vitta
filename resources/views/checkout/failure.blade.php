@extends('layouts.app')

@section('title', 'Pago Rechazado')

@section('content')
    <div class="vitta-container" style="padding: 80px 24px;">
        <div style="max-width: 800px; margin: 0 auto; text-align: center;">

            <!-- Failure Icon -->
            <div
                style="width: 120px; height: 120px; border-radius: 50%; background: rgba(239, 68, 68, 0.1); border: 3px solid #ef4444; margin: 0 auto 32px; display: flex; align-items: center; justify-content: center;">
                <i class="bi bi-x-circle-fill" style="font-size: 64px; color: #ef4444;"></i>
            </div>

            <h1 style="font-size: 42px; color: #ef4444; margin-bottom: 16px;">
                Pago Rechazado
            </h1>

            <p style="font-size: 18px; color: var(--vitta-pearl); opacity: 0.8; margin-bottom: 32px;">
                No pudimos procesar tu pago
            </p>

            <!-- Order Number -->
            <div
                style="background: var(--vitta-black-soft); border: 2px solid #ef4444; border-radius: 8px; padding: 24px; margin-bottom: 32px;">
                <p style="color: var(--vitta-pearl); opacity: 0.7; font-size: 14px; margin-bottom: 8px;">
                    Número de Pedido
                </p>
                <p style="font-size: 28px; color: var(--vitta-pearl); font-weight: 600; letter-spacing: 2px;">
                    {{ $order->order_number }}
                </p>
            </div>

            <!-- Reasons -->
            <div
                style="background: rgba(239, 68, 68, 0.1); border: 1px solid #ef4444; border-radius: 8px; padding: 24px; margin-bottom: 32px; text-align: left;">
                <h4 style="color: #ef4444; font-size: 18px; margin-bottom: 16px;">
                    Posibles razones:
                </h4>
                <ul style="color: var(--vitta-pearl); font-size: 14px; line-height: 1.8; padding-left: 24px;">
                    <li>Fondos insuficientes en tu tarjeta</li>
                    <li>Datos de la tarjeta incorrectos</li>
                    <li>La tarjeta no está habilitada para compras online</li>
                    <li>Límite de compra excedido</li>
                    <li>Tu banco rechazó la transacción</li>
                </ul>
            </div>

            <!-- What to do -->
            <div
                style="background: var(--vitta-black-soft); border: 1px solid var(--vitta-gray); border-radius: 8px; padding: 24px; margin-bottom: 32px; text-align: left;">
                <h4 style="color: var(--vitta-gold); font-size: 18px; margin-bottom: 16px;">
                    ¿Qué puedo hacer?
                </h4>
                <div style="display: flex; flex-direction: column; gap: 12px;">
                    <div style="display: flex; align-items: start; gap: 12px;">
                        <i class="bi bi-check2" style="color: var(--vitta-gold); font-size: 20px; flex-shrink: 0;"></i>
                        <p style="color: var(--vitta-pearl); font-size: 14px;">
                            Verificá los datos de tu tarjeta e intentá nuevamente
                        </p>
                    </div>
                    <div style="display: flex; align-items: start; gap: 12px;">
                        <i class="bi bi-check2" style="color: var(--vitta-gold); font-size: 20px; flex-shrink: 0;"></i>
                        <p style="color: var(--vitta-pearl); font-size: 14px;">
                            Probá con otra tarjeta o medio de pago
                        </p>
                    </div>
                    <div style="display: flex; align-items: start; gap: 12px;">
                        <i class="bi bi-check2" style="color: var(--vitta-gold); font-size: 20px; flex-shrink: 0;"></i>
                        <p style="color: var(--vitta-pearl); font-size: 14px;">
                            Contactá a tu banco para verificar que la compra no haya sido bloqueada
                        </p>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div style="display: flex; gap: 16px; justify-content: center; flex-wrap: wrap;">
                <a href="{{ route('cart.index') }}" class="btn-gold" style="text-decoration: none;">
                    <i class="bi bi-arrow-clockwise"></i> INTENTAR NUEVAMENTE
                </a>
                <a href="{{ route('home') }}"
                    style="padding: 14px 32px; border: 2px solid var(--vitta-gold); color: var(--vitta-gold); text-decoration: none; font-weight: 600; letter-spacing: 0.5px; text-transform: uppercase; border-radius: 4px; display: inline-flex; align-items: center; gap: 8px;">
                    <i class="bi bi-house-fill"></i> VOLVER AL INICIO
                </a>
            </div>

            <p style="color: var(--vitta-pearl); opacity: 0.6; font-size: 13px; margin-top: 32px;">
                ¿Necesitás ayuda? Contactanos a
                <a href="mailto:contacto@vittaperfumes.com" style="color: var(--vitta-gold);">contacto@vittaperfumes.com</a>
            </p>
        </div>
    </div>
@endsection