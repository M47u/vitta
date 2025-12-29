@extends('layouts.app')

@section('title', 'Pedido #' . $order->order_number . ' - Vitta Perfumes')

@section('content')
<div style="min-height: 100vh; padding: 40px 0; background: var(--vitta-black);">
    <div class="container">
        <div style="display: grid; grid-template-columns: 280px 1fr; gap: 32px;">
            
            <!-- Sidebar -->
            @include('customer.partials.sidebar')

            <!-- Main Content -->
            <div>
                
                <!-- Header with Back Button -->
                <div style="margin-bottom: 32px;">
                    <a href="{{ route('customer.orders') }}" 
                       style="display: inline-flex; align-items: center; gap: 8px; color: var(--vitta-gold); text-decoration: none; margin-bottom: 16px; transition: all 0.3s;"
                       onmouseover="this.style.transform='translateX(-4px)';"
                       onmouseout="this.style.transform='translateX(0)';">
                        <i class="bi bi-arrow-left"></i>
                        Volver a Mis Pedidos
                    </a>
                    <h1 style="font-size: 28px; font-weight: 700; color: var(--vitta-gold); margin-bottom: 8px;">
                        Pedido #{{ $order->order_number }}
                    </h1>
                    <p style="color: var(--vitta-pearl); opacity: 0.7;">
                        Realizado el {{ $order->created_at->format('d/m/Y') }} a las {{ $order->created_at->format('H:i') }}
                    </p>
                </div>

                <div style="display: grid; gap: 24px;">

                    <!-- Order Status Card -->
                    <div style="background: var(--vitta-black-soft); border: 1px solid rgba(212, 175, 55, 0.2); border-radius: 8px; padding: 24px;">
                        <h2 style="font-size: 18px; font-weight: 600; color: var(--vitta-gold); margin-bottom: 20px;">
                            Estado del Pedido
                        </h2>
                        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px;">
                            
                            @php
                                $statusColors = [
                                    'pending' => '#6c757d',
                                    'processing' => '#ffc107',
                                    'shipped' => '#0dcaf0',
                                    'delivered' => '#198754',
                                    'cancelled' => '#dc3545'
                                ];
                                $statusLabels = [
                                    'pending' => 'Pendiente',
                                    'processing' => 'Procesando',
                                    'shipped' => 'Enviado',
                                    'delivered' => 'Entregado',
                                    'cancelled' => 'Cancelado'
                                ];
                                $paymentColors = [
                                    'pending' => '#6c757d',
                                    'paid' => '#198754',
                                    'failed' => '#dc3545'
                                ];
                                $paymentLabels = [
                                    'pending' => 'Pendiente',
                                    'paid' => 'Pagado',
                                    'failed' => 'Fallido'
                                ];
                            @endphp

                            <div>
                                <p style="color: var(--vitta-pearl); opacity: 0.6; font-size: 13px; margin-bottom: 8px;">
                                    Estado del Pedido
                                </p>
                                <span style="display: inline-block; padding: 8px 16px; background: {{ $statusColors[$order->status] ?? '#6c757d' }}; color: white; border-radius: 20px; font-size: 14px; font-weight: 600;">
                                    {{ $statusLabels[$order->status] ?? ucfirst($order->status) }}
                                </span>
                            </div>

                            <div>
                                <p style="color: var(--vitta-pearl); opacity: 0.6; font-size: 13px; margin-bottom: 8px;">
                                    Estado del Pago
                                </p>
                                <span style="display: inline-block; padding: 8px 16px; background: {{ $paymentColors[$order->payment_status] ?? '#6c757d' }}; color: white; border-radius: 20px; font-size: 14px; font-weight: 600;">
                                    {{ $paymentLabels[$order->payment_status] ?? ucfirst($order->payment_status) }}
                                </span>
                            </div>

                            @if($order->tracking_number)
                            <div>
                                <p style="color: var(--vitta-pearl); opacity: 0.6; font-size: 13px; margin-bottom: 8px;">
                                    Número de Seguimiento
                                </p>
                                <p style="color: var(--vitta-gold); font-size: 16px; font-weight: 600; font-family: monospace;">
                                    {{ $order->tracking_number }}
                                </p>
                            </div>
                            @endif

                        </div>
                    </div>

                    <!-- Items Card -->
                    <div style="background: var(--vitta-black-soft); border: 1px solid rgba(212, 175, 55, 0.2); border-radius: 8px; padding: 24px;">
                        <h2 style="font-size: 18px; font-weight: 600; color: var(--vitta-gold); margin-bottom: 20px;">
                            Productos del Pedido
                        </h2>
                        <div style="display: flex; flex-direction: column; gap: 16px;">
                            @foreach($order->items as $item)
                                <div style="display: flex; gap: 20px; padding: 16px; background: var(--vitta-black); border: 1px solid rgba(212, 175, 55, 0.1); border-radius: 6px;">
                                    
                                    <!-- Product Image -->
                                    @if($item->product->main_image)
                                        <img src="{{ Storage::url($item->product->main_image) }}" 
                                             alt="{{ $item->product->name }}"
                                             style="width: 80px; height: 80px; object-fit: cover; border-radius: 6px; border: 1px solid rgba(212, 175, 55, 0.2);">
                                    @else
                                        <div style="width: 80px; height: 80px; background: rgba(212, 175, 55, 0.1); border-radius: 6px; display: flex; align-items: center; justify-content: center;">
                                            <i class="bi bi-image" style="font-size: 24px; color: var(--vitta-gold); opacity: 0.3;"></i>
                                        </div>
                                    @endif

                                    <!-- Product Info -->
                                    <div style="flex: 1;">
                                        <h3 style="font-size: 16px; font-weight: 600; color: var(--vitta-pearl); margin-bottom: 8px;">
                                            {{ $item->product->name }}
                                        </h3>
                                        @if($item->variant)
                                            <p style="color: var(--vitta-pearl); opacity: 0.7; font-size: 14px; margin-bottom: 4px;">
                                                Presentación: {{ $item->variant->ml_size }}ml
                                            </p>
                                            <p style="color: var(--vitta-pearl); opacity: 0.6; font-size: 13px;">
                                                SKU: {{ $item->variant->sku }}
                                            </p>
                                        @endif
                                    </div>

                                    <!-- Quantity & Price -->
                                    <div style="text-align: right;">
                                        <p style="color: var(--vitta-pearl); opacity: 0.6; font-size: 13px; margin-bottom: 4px;">
                                            Cantidad: {{ $item->quantity }}
                                        </p>
                                        <p style="color: var(--vitta-gold); font-size: 18px; font-weight: 700;">
                                            ${{ number_format($item->price * $item->quantity, 0, ',', '.') }}
                                        </p>
                                        <p style="color: var(--vitta-pearl); opacity: 0.5; font-size: 12px;">
                                            ${{ number_format($item->price, 0, ',', '.') }} c/u
                                        </p>
                                    </div>

                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Summary & Address Grid -->
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 24px;">

                        <!-- Shipping Address -->
                        <div style="background: var(--vitta-black-soft); border: 1px solid rgba(212, 175, 55, 0.2); border-radius: 8px; padding: 24px;">
                            <h2 style="font-size: 18px; font-weight: 600; color: var(--vitta-gold); margin-bottom: 20px;">
                                Dirección de Envío
                            </h2>
                            @if($order->address)
                                <div style="color: var(--vitta-pearl); line-height: 1.6;">
                                    <p style="margin-bottom: 8px;">
                                        <strong>{{ $order->address->recipient_name }}</strong>
                                    </p>
                                    <p style="opacity: 0.8; margin-bottom: 4px;">
                                        {{ $order->address->street }}, {{ $order->address->number }}
                                    </p>
                                    @if($order->address->apartment)
                                        <p style="opacity: 0.8; margin-bottom: 4px;">
                                            {{ $order->address->apartment }}
                                        </p>
                                    @endif
                                    <p style="opacity: 0.8; margin-bottom: 4px;">
                                        {{ $order->address->city }}, {{ $order->address->state }}
                                    </p>
                                    <p style="opacity: 0.8; margin-bottom: 8px;">
                                        CP: {{ $order->address->postal_code }}
                                    </p>
                                    @if($order->address->phone)
                                        <p style="opacity: 0.7; font-size: 14px;">
                                            <i class="bi bi-telephone"></i> {{ $order->address->phone }}
                                        </p>
                                    @endif
                                </div>
                            @else
                                <p style="color: var(--vitta-pearl); opacity: 0.6;">
                                    No hay dirección registrada
                                </p>
                            @endif
                        </div>

                        <!-- Order Summary -->
                        <div style="background: var(--vitta-black-soft); border: 1px solid rgba(212, 175, 55, 0.2); border-radius: 8px; padding: 24px;">
                            <h2 style="font-size: 18px; font-weight: 600; color: var(--vitta-gold); margin-bottom: 20px;">
                                Resumen del Pedido
                            </h2>
                            <div style="display: flex; flex-direction: column; gap: 12px;">
                                
                                <div style="display: flex; justify-content: space-between; padding-bottom: 12px; border-bottom: 1px solid rgba(212, 175, 55, 0.1);">
                                    <span style="color: var(--vitta-pearl); opacity: 0.7;">Subtotal:</span>
                                    <span style="color: var(--vitta-pearl); font-weight: 600;">
                                        ${{ number_format($order->subtotal, 0, ',', '.') }}
                                    </span>
                                </div>

                                <div style="display: flex; justify-content: space-between; padding-bottom: 12px; border-bottom: 1px solid rgba(212, 175, 55, 0.1);">
                                    <span style="color: var(--vitta-pearl); opacity: 0.7;">IVA ({{ $order->tax_rate }}%):</span>
                                    <span style="color: var(--vitta-pearl); font-weight: 600;">
                                        ${{ number_format($order->tax, 0, ',', '.') }}
                                    </span>
                                </div>

                                <div style="display: flex; justify-content: space-between; padding-bottom: 12px; border-bottom: 1px solid rgba(212, 175, 55, 0.1);">
                                    <span style="color: var(--vitta-pearl); opacity: 0.7;">Envío:</span>
                                    <span style="color: var(--vitta-pearl); font-weight: 600;">
                                        @if($order->shipping_cost > 0)
                                            ${{ number_format($order->shipping_cost, 0, ',', '.') }}
                                        @else
                                            <span style="color: #198754;">Gratis</span>
                                        @endif
                                    </span>
                                </div>

                                <div style="display: flex; justify-content: space-between; padding-top: 12px;">
                                    <span style="color: var(--vitta-gold); font-size: 18px; font-weight: 700;">Total:</span>
                                    <span style="color: var(--vitta-gold); font-size: 24px; font-weight: 700;">
                                        ${{ number_format($order->total, 0, ',', '.') }}
                                    </span>
                                </div>

                                <div style="margin-top: 12px; padding-top: 12px; border-top: 1px solid rgba(212, 175, 55, 0.1);">
                                    <p style="color: var(--vitta-pearl); opacity: 0.7; font-size: 13px; margin-bottom: 4px;">
                                        Método de Pago
                                    </p>
                                    <p style="color: var(--vitta-pearl); font-weight: 600;">
                                        {{ ucfirst($order->payment_method) }}
                                    </p>
                                </div>

                            </div>
                        </div>

                    </div>

                    <!-- Transactions (if any) -->
                    @if($order->transactions && $order->transactions->count() > 0)
                        <div style="background: var(--vitta-black-soft); border: 1px solid rgba(212, 175, 55, 0.2); border-radius: 8px; padding: 24px;">
                            <h2 style="font-size: 18px; font-weight: 600; color: var(--vitta-gold); margin-bottom: 20px;">
                                Historial de Transacciones
                            </h2>
                            <div style="display: flex; flex-direction: column; gap: 12px;">
                                @foreach($order->transactions as $transaction)
                                    <div style="padding: 14px; background: var(--vitta-black); border: 1px solid rgba(212, 175, 55, 0.1); border-radius: 6px;">
                                        <div style="display: flex; justify-content: space-between; align-items: center;">
                                            <div>
                                                <p style="color: var(--vitta-pearl); font-weight: 600; margin-bottom: 4px;">
                                                    {{ ucfirst($transaction->status) }}
                                                </p>
                                                <p style="color: var(--vitta-pearl); opacity: 0.6; font-size: 13px;">
                                                    {{ $transaction->created_at->format('d/m/Y H:i') }}
                                                </p>
                                            </div>
                                            <p style="color: var(--vitta-gold); font-weight: 700; font-family: monospace; font-size: 13px;">
                                                #{{ $transaction->transaction_id }}
                                            </p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                </div>

            </div>

        </div>
    </div>
</div>
@endsection
