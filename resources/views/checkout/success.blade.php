@extends('layouts.app')

@section('title', 'Pedido Confirmado')

@section('content')
    <div class="vitta-container" style="padding: 80px 24px;">
        <div style="max-width: 800px; margin: 0 auto; text-align: center;">

            <!-- Success Icon -->
            <div
                style="width: 120px; height: 120px; border-radius: 50%; background: rgba(34, 197, 94, 0.1); border: 3px solid #22c55e; margin: 0 auto 32px; display: flex; align-items: center; justify-content: center;">
                <i class="bi bi-check-circle-fill" style="font-size: 64px; color: #22c55e;"></i>
            </div>

            <h1 style="font-size: 42px; color: var(--vitta-gold); margin-bottom: 16px;">
                ¡Pedido Confirmado!
            </h1>

            <p style="font-size: 18px; color: var(--vitta-pearl); opacity: 0.8; margin-bottom: 32px;">
                Tu pago ha sido procesado exitosamente
            </p>

            <!-- Order Number -->
            <div
                style="background: var(--vitta-black-soft); border: 2px solid var(--vitta-gold); border-radius: 8px; padding: 24px; margin-bottom: 32px;">
                <p style="color: var(--vitta-pearl); opacity: 0.7; font-size: 14px; margin-bottom: 8px;">
                    Número de Pedido
                </p>
                <p style="font-size: 28px; color: var(--vitta-gold); font-weight: 600; letter-spacing: 2px;">
                    {{ $order->order_number }}
                </p>
            </div>

            <!-- Order Details -->
            <div
                style="background: var(--vitta-black-soft); border: 1px solid var(--vitta-gray); border-radius: 8px; padding: 32px; text-align: left; margin-bottom: 32px;">
                <h3 style="font-size: 20px; color: var(--vitta-pearl); margin-bottom: 24px; text-align: center;">
                    Detalles del Pedido
                </h3>

                <div style="display: grid; gap: 16px; margin-bottom: 24px;">
                    @foreach($order->items as $item)
                        <div
                            style="display: flex; justify-content: space-between; align-items: center; padding-bottom: 16px; border-bottom: 1px solid var(--vitta-gray);">
                            <div>
                                <p style="color: var(--vitta-pearl); font-size: 15px; margin-bottom: 4px;">
                                    {{ $item->product_name }}
                                    @if($item->variant_name)
                                        <span style="opacity: 0.7; font-size: 13px;">- {{ $item->variant_name }}</span>
                                    @endif
                                </p>
                                <p style="color: var(--vitta-pearl); opacity: 0.6; font-size: 13px;">
                                    Cantidad: {{ $item->quantity }}
                                </p>
                            </div>
                            <p style="color: var(--vitta-gold); font-weight: 600;">
                                ${{ number_format($item->subtotal, 2) }}
                            </p>
                        </div>
                    @endforeach
                </div>

                <div style="display: flex; flex-direction: column; gap: 12px;">
                    <div style="display: flex; justify-content: space-between;">
                        <span style="color: var(--vitta-pearl); opacity: 0.7;">Subtotal:</span>
                        <span style="color: var(--vitta-pearl);">${{ number_format($order->subtotal, 2) }}</span>
                    </div>
                    <div style="display: flex; justify-content: space-between;">
                        <span style="color: var(--vitta-pearl); opacity: 0.7;">Envío:</span>
                        <span style="color: var(--vitta-pearl);">${{ number_format($order->shipping, 2) }}</span>
                    </div>
                    <div class="golden-line" style="margin: 8px 0;"></div>
                    <div style="display: flex; justify-content: space-between; font-size: 20px;">
                        <span style="color: var(--vitta-gold); font-weight: 600;">Total:</span>
                        <span
                            style="color: var(--vitta-gold); font-weight: 600;">${{ number_format($order->total, 2) }}</span>
                    </div>
                </div>
            </div>

            <!-- Shipping Address -->
            <div
                style="background: var(--vitta-black-soft); border: 1px solid var(--vitta-gray); border-radius: 8px; padding: 24px; text-align: left; margin-bottom: 32px;">
                <h4 style="color: var(--vitta-gold); font-size: 16px; margin-bottom: 12px;">
                    <i class="bi bi-geo-alt-fill"></i> Dirección de Envío
                </h4>
                <p style="color: var(--vitta-pearl); margin-bottom: 8px;">
                    <strong>{{ $order->shipping_address['recipient_name'] ?? 'N/A' }}</strong>
                </p>
                <p style="color: var(--vitta-pearl); opacity: 0.8; font-size: 14px;">
                    {{ $order->shipping_address['full_address'] ?? 'N/A' }}
                </p>
            </div>

            <!-- What's Next -->
            <div
                style="background: rgba(212, 175, 55, 0.1); border: 1px solid var(--vitta-gold); border-radius: 8px; padding: 24px; margin-bottom: 32px;">
                <h4 style="color: var(--vitta-gold); font-size: 18px; margin-bottom: 16px;">
                    ¿Qué sigue ahora?
                </h4>
                <div style="display: flex; flex-direction: column; gap: 12px; text-align: left;">
                    <div style="display: flex; align-items: start; gap: 12px;">
                        <i class="bi bi-1-circle-fill"
                            style="color: var(--vitta-gold); font-size: 24px; flex-shrink: 0;"></i>
                        <p style="color: var(--vitta-pearl); font-size: 14px;">
                            Recibirás un correo de confirmación con los detalles de tu pedido
                        </p>
                    </div>
                    <div style="display: flex; align-items: start; gap: 12px;">
                        <i class="bi bi-2-circle-fill"
                            style="color: var(--vitta-gold); font-size: 24px; flex-shrink: 0;"></i>
                        <p style="color: var(--vitta-pearl); font-size: 14px;">
                            Prepararemos tu pedido con el máximo cuidado
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
                Si tenés alguna pregunta sobre tu pedido, contactanos a
                <a href="mailto:contacto@vittaperfumes.com" style="color: var(--vitta-gold);">contacto@vittaperfumes.com</a>
            </p>
        </div>
    </div>
@endsection