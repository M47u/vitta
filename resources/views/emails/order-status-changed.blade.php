@extends('emails.layout')

@section('title', 'Actualización de Pedido')

@section('content')
    @php
        $statusLabels = [
            'pending' => 'Pendiente',
            'processing' => 'En Proceso',
            'shipped' => 'Enviado',
            'delivered' => 'Entregado',
            'cancelled' => 'Cancelado',
        ];
        
        $statusIcons = [
            'pending' => '⏳',
            'processing' => '⚙️',
            'shipped' => '📦',
            'delivered' => '✅',
            'cancelled' => '❌',
        ];
        
        $currentStatus = $statusLabels[$order->status] ?? ucfirst($order->status);
        $icon = $statusIcons[$order->status] ?? '📋';
    @endphp

    <h2>{{ $icon }} Estado de tu Pedido Actualizado</h2>
    
    <p>Hola <strong>{{ $order->user->name }}</strong>,</p>
    
    <p>Tu pedido <strong>#{{ $order->order_number }}</strong> ha sido actualizado.</p>

    <div class="order-details" style="text-align: center; padding: 30px;">
        <p style="font-size: 14px; color: rgba(248, 245, 240, 0.6); margin-bottom: 10px;">Estado actual:</p>
        <p style="font-size: 28px; font-weight: 700; color: #D4AF37; margin: 0;">
            {{ $icon }} {{ $currentStatus }}
        </p>
    </div>

    @if($order->status === 'shipped')
        <div style="background: rgba(13, 202, 240, 0.1); border-left: 3px solid #0dcaf0; padding: 20px; border-radius: 6px; margin: 20px 0;">
            <p style="margin: 0; color: #0dcaf0; font-weight: 600;">📦 ¡Tu pedido está en camino!</p>
            <p style="margin: 10px 0 0; font-size: 14px;">
                Tu paquete ha sido enviado y llegará pronto a tu domicilio.
                @if($order->tracking_number)
                    <br><br>
                    <strong>Código de seguimiento:</strong> {{ $order->tracking_number }}
                @endif
            </p>
        </div>
    @endif

    @if($order->status === 'delivered')
        <div style="background: rgba(25, 135, 84, 0.1); border-left: 3px solid #198754; padding: 20px; border-radius: 6px; margin: 20px 0;">
            <p style="margin: 0; color: #198754; font-weight: 600;">✅ ¡Tu pedido ha sido entregado!</p>
            <p style="margin: 10px 0 0; font-size: 14px;">
                Esperamos que disfrutes tus fragancias de Vitta Perfumes.
                <br><br>
                ¿Te gustaría dejarnos una reseña sobre tu experiencia?
            </p>
        </div>
    @endif

    @if($order->status === 'cancelled')
        <div style="background: rgba(220, 53, 69, 0.1); border-left: 3px solid #dc3545; padding: 20px; border-radius: 6px; margin: 20px 0;">
            <p style="margin: 0; color: #dc3545; font-weight: 600;">❌ Pedido Cancelado</p>
            <p style="margin: 10px 0 0; font-size: 14px;">
                Tu pedido ha sido cancelado. Si esto fue un error o tienes alguna pregunta, por favor contáctanos.
            </p>
        </div>
    @endif

    <div class="divider"></div>

    <h3 style="color: #D4AF37; margin-bottom: 15px;">Resumen del Pedido</h3>
    
    <div class="order-details">
        <table>
            <tr>
                <td>Número de Pedido:</td>
                <td>#{{ $order->order_number }}</td>
            </tr>
            <tr>
                <td>Fecha:</td>
                <td>{{ $order->created_at->format('d/m/Y') }}</td>
            </tr>
            <tr>
                <td>Total:</td>
                <td>${{ number_format($order->total, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td>Productos:</td>
                <td>{{ $order->items->sum('quantity') }} artículo(s)</td>
            </tr>
        </table>
    </div>

    <div style="text-align: center; margin-top: 40px;">
        <a href="{{ route('customer.orders.show', $order->id) }}" class="button">
            Ver Detalles Completos
        </a>
    </div>

    <div class="divider"></div>

    <p style="color: rgba(248, 245, 240, 0.7); font-size: 13px; text-align: center;">
        Si tienes alguna pregunta sobre tu pedido, no dudes en contactarnos.<br>
        Estamos aquí para ayudarte.
    </p>
@endsection
