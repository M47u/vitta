@extends('emails.layout')

@section('title', 'Confirmación de Pedido')

@section('content')
    <h2>¡Pedido Confirmado! 🎉</h2>
    
    <p>Hola <strong>{{ $order->user->name }}</strong>,</p>
    
    <p>Gracias por tu compra en Vitta Perfumes. Hemos recibido tu pedido y ya estamos preparándolo para ti.</p>

    <div class="divider"></div>

    <!-- Order Summary -->
    <div class="order-details">
        <table>
            <tr>
                <td>Número de Pedido:</td>
                <td>#{{ $order->order_number }}</td>
            </tr>
            <tr>
                <td>Fecha:</td>
                <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
            </tr>
            <tr>
                <td>Estado:</td>
                <td>
                    @php
                        $statusLabels = [
                            'pending' => 'Pendiente',
                            'processing' => 'En Proceso',
                            'shipped' => 'Enviado',
                            'delivered' => 'Entregado',
                        ];
                    @endphp
                    {{ $statusLabels[$order->status] ?? ucfirst($order->status) }}
                </td>
            </tr>
            <tr>
                <td>Método de Pago:</td>
                <td>{{ ucfirst($order->payment_method) }}</td>
            </tr>
        </table>
    </div>

    <h3 style="color: #D4AF37; margin-top: 30px; margin-bottom: 15px;">Productos</h3>
    
    <div class="order-details">
        @foreach($order->items as $item)
            <div class="product-item">
                <div class="product-info">
                    <div class="product-name">{{ $item->product->name }}</div>
                    @if($item->variant)
                        <div class="product-variant">{{ $item->variant->ml_size }}ml - SKU: {{ $item->variant->sku }}</div>
                    @endif
                    <div class="product-variant">Cantidad: {{ $item->quantity }}</div>
                </div>
                <div class="product-price">
                    ${{ number_format($item->price * $item->quantity, 0, ',', '.') }}
                </div>
            </div>
        @endforeach

        <table style="margin-top: 20px;">
            <tr>
                <td>Subtotal:</td>
                <td>${{ number_format($order->subtotal, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td>IVA ({{ $order->tax_rate }}%):</td>
                <td>${{ number_format($order->tax, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td>Envío:</td>
                <td>
                    @if($order->shipping_cost > 0)
                        ${{ number_format($order->shipping_cost, 0, ',', '.') }}
                    @else
                        <span style="color: #198754;">Gratis</span>
                    @endif
                </td>
            </tr>
            <tr class="total-row">
                <td><strong>Total:</strong></td>
                <td><strong>${{ number_format($order->total, 0, ',', '.') }}</strong></td>
            </tr>
        </table>
    </div>

    @if($order->address)
        <h3 style="color: #D4AF37; margin-top: 30px; margin-bottom: 15px;">Dirección de Envío</h3>
        
        <div class="order-details">
            <p style="margin: 0; line-height: 1.8;">
                <strong>{{ $order->address->recipient_name }}</strong><br>
                {{ $order->address->street_address }}, {{ $order->address->street_number }}<br>
                @if($order->address->apartment)
                    {{ $order->address->apartment }}<br>
                @endif
                @if($order->address->neighborhood)
                    {{ $order->address->neighborhood }}<br>
                @endif
                {{ $order->address->city }}, {{ $order->address->state }}<br>
                CP: {{ $order->address->postal_code }}<br>
                Tel: {{ $order->address->recipient_phone }}
            </p>
        </div>
    @endif

    <div style="text-align: center; margin-top: 40px;">
        <a href="{{ route('customer.orders.show', $order->id) }}" class="button">
            Ver Detalles del Pedido
        </a>
    </div>

    <div class="divider"></div>

    <p style="color: rgba(248, 245, 240, 0.7); font-size: 13px;">
        Te mantendremos informado sobre el estado de tu pedido. Si tienes alguna pregunta, no dudes en contactarnos.
    </p>
@endsection
