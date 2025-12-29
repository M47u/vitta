@extends('layouts.app')

@section('title', 'Mis Pedidos - Vitta Perfumes')

@section('content')
<div style="min-height: 100vh; padding: 40px 0; background: var(--vitta-black);">
    <div class="container">
        <div style="display: grid; grid-template-columns: 280px 1fr; gap: 32px;">
            
            <!-- Sidebar -->
            @include('customer.partials.sidebar')

            <!-- Main Content -->
            <div>
                <!-- Header -->
                <div style="margin-bottom: 32px;">
                    <h1 style="font-size: 28px; font-weight: 700; color: var(--vitta-gold); margin-bottom: 8px;">
                        Mis Pedidos
                    </h1>
                    <p style="color: var(--vitta-pearl); opacity: 0.7;">
                        Historial completo de tus compras
                    </p>
                </div>

                <!-- Filters -->
                <div style="background: var(--vitta-black-soft); border: 1px solid rgba(212, 175, 55, 0.2); border-radius: 8px; padding: 24px; margin-bottom: 24px;">
                    <form method="GET" action="{{ route('customer.orders') }}">
                        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px; align-items: end;">
                            
                            <!-- Search -->
                            <div>
                                <label style="display: block; color: var(--vitta-pearl); font-size: 13px; margin-bottom: 8px;">
                                    Buscar Pedido
                                </label>
                                <input type="text" 
                                       name="search" 
                                       value="{{ request('search') }}" 
                                       placeholder="Número de pedido..."
                                       style="width: 100%; padding: 10px 14px; background: var(--vitta-black); border: 1px solid rgba(212, 175, 55, 0.3); border-radius: 6px; color: var(--vitta-pearl);">
                            </div>

                            <!-- Status Filter -->
                            <div>
                                <label style="display: block; color: var(--vitta-pearl); font-size: 13px; margin-bottom: 8px;">
                                    Estado del Pedido
                                </label>
                                <select name="status" 
                                        style="width: 100%; padding: 10px 14px; background: var(--vitta-black); border: 1px solid rgba(212, 175, 55, 0.3); border-radius: 6px; color: var(--vitta-pearl);">
                                    <option value="">Todos los estados</option>
                                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pendiente</option>
                                    <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>Procesando</option>
                                    <option value="shipped" {{ request('status') == 'shipped' ? 'selected' : '' }}>Enviado</option>
                                    <option value="delivered" {{ request('status') == 'delivered' ? 'selected' : '' }}>Entregado</option>
                                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelado</option>
                                </select>
                            </div>

                            <!-- Payment Status Filter -->
                            <div>
                                <label style="display: block; color: var(--vitta-pearl); font-size: 13px; margin-bottom: 8px;">
                                    Estado de Pago
                                </label>
                                <select name="payment_status" 
                                        style="width: 100%; padding: 10px 14px; background: var(--vitta-black); border: 1px solid rgba(212, 175, 55, 0.3); border-radius: 6px; color: var(--vitta-pearl);">
                                    <option value="">Todos los pagos</option>
                                    <option value="pending" {{ request('payment_status') == 'pending' ? 'selected' : '' }}>Pendiente</option>
                                    <option value="paid" {{ request('payment_status') == 'paid' ? 'selected' : '' }}>Pagado</option>
                                    <option value="failed" {{ request('payment_status') == 'failed' ? 'selected' : '' }}>Fallido</option>
                                </select>
                            </div>

                            <!-- Buttons -->
                            <div style="display: flex; gap: 8px;">
                                <button type="submit" 
                                        style="flex: 1; padding: 10px 20px; background: var(--vitta-gold); color: var(--vitta-black); border: none; border-radius: 6px; font-weight: 600; cursor: pointer; transition: all 0.3s;"
                                        onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 4px 12px rgba(212, 175, 55, 0.3)';"
                                        onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none';">
                                    <i class="bi bi-funnel"></i> Filtrar
                                </button>
                                <a href="{{ route('customer.orders') }}" 
                                   style="padding: 10px 20px; background: var(--vitta-black); color: var(--vitta-pearl); border: 1px solid rgba(212, 175, 55, 0.3); border-radius: 6px; text-decoration: none; display: flex; align-items: center; justify-content: center; transition: all 0.3s;"
                                   onmouseover="this.style.borderColor='var(--vitta-gold)';"
                                   onmouseout="this.style.borderColor='rgba(212, 175, 55, 0.3)';">
                                    <i class="bi bi-x-lg"></i>
                                </a>
                            </div>

                        </div>
                    </form>
                </div>

                <!-- Orders List -->
                @if($orders->count() > 0)
                    <div style="display: flex; flex-direction: column; gap: 16px;">
                        @foreach($orders as $order)
                            <div style="background: var(--vitta-black-soft); border: 1px solid rgba(212, 175, 55, 0.2); border-radius: 8px; overflow: hidden; transition: all 0.3s;"
                                 onmouseover="this.style.borderColor='var(--vitta-gold)'; this.style.transform='translateY(-2px)'; this.style.boxShadow='0 8px 24px rgba(0,0,0,0.3)';"
                                 onmouseout="this.style.borderColor='rgba(212, 175, 55, 0.2)'; this.style.transform='translateY(0)'; this.style.boxShadow='none';">
                                
                                <div style="padding: 24px;">
                                    
                                    <!-- Order Header -->
                                    <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 20px; padding-bottom: 16px; border-bottom: 1px solid rgba(212, 175, 55, 0.1);">
                                        <div>
                                            <h3 style="font-size: 18px; font-weight: 600; color: var(--vitta-gold); margin-bottom: 8px;">
                                                Pedido #{{ $order->order_number }}
                                            </h3>
                                            <p style="color: var(--vitta-pearl); opacity: 0.6; font-size: 14px;">
                                                <i class="bi bi-calendar"></i> 
                                                {{ $order->created_at->format('d/m/Y H:i') }}
                                            </p>
                                        </div>
                                        <div style="text-align: right;">
                                            <!-- Order Status -->
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
                                            @endphp
                                            <span style="display: inline-block; padding: 6px 14px; background: {{ $statusColors[$order->status] ?? '#6c757d' }}; color: white; border-radius: 20px; font-size: 12px; font-weight: 600; margin-bottom: 8px;">
                                                {{ $statusLabels[$order->status] ?? ucfirst($order->status) }}
                                            </span>
                                            
                                            <!-- Payment Status -->
                                            @php
                                                $paymentColors = [
                                                    'pending' => '#6c757d',
                                                    'paid' => '#198754',
                                                    'failed' => '#dc3545'
                                                ];
                                                $paymentLabels = [
                                                    'pending' => 'Pago Pendiente',
                                                    'paid' => 'Pagado',
                                                    'failed' => 'Pago Fallido'
                                                ];
                                            @endphp
                                            <br>
                                            <span style="display: inline-block; padding: 4px 12px; background: rgba({{ $order->payment_status === 'paid' ? '25, 135, 84' : ($order->payment_status === 'failed' ? '220, 53, 69' : '108, 117, 125') }}, 0.2); color: {{ $paymentColors[$order->payment_status] ?? '#6c757d' }}; border-radius: 12px; font-size: 11px; font-weight: 600;">
                                                {{ $paymentLabels[$order->payment_status] ?? ucfirst($order->payment_status) }}
                                            </span>
                                        </div>
                                    </div>

                                    <!-- Order Details -->
                                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 16px; margin-bottom: 20px;">
                                        <div>
                                            <p style="color: var(--vitta-pearl); opacity: 0.6; font-size: 13px; margin-bottom: 4px;">
                                                Total del Pedido
                                            </p>
                                            <p style="color: var(--vitta-gold); font-size: 20px; font-weight: 700;">
                                                ${{ number_format($order->total, 0, ',', '.') }}
                                            </p>
                                        </div>
                                        <div>
                                            <p style="color: var(--vitta-pearl); opacity: 0.6; font-size: 13px; margin-bottom: 4px;">
                                                Método de Pago
                                            </p>
                                            <p style="color: var(--vitta-pearl); font-size: 14px; font-weight: 500;">
                                                {{ ucfirst($order->payment_method) }}
                                            </p>
                                        </div>
                                        <div>
                                            <p style="color: var(--vitta-pearl); opacity: 0.6; font-size: 13px; margin-bottom: 4px;">
                                                Artículos
                                            </p>
                                            <p style="color: var(--vitta-pearl); font-size: 14px; font-weight: 500;">
                                                {{ $order->items->sum('quantity') }} producto(s)
                                            </p>
                                        </div>
                                    </div>

                                    <!-- Action Button -->
                                    <a href="{{ route('customer.orders.show', $order->id) }}" 
                                       style="display: inline-flex; align-items: center; gap: 8px; padding: 10px 24px; background: var(--vitta-gold); color: var(--vitta-black); text-decoration: none; border-radius: 6px; font-weight: 600; transition: all 0.3s;"
                                       onmouseover="this.style.transform='translateX(4px)'; this.style.boxShadow='0 4px 12px rgba(212, 175, 55, 0.3)';"
                                       onmouseout="this.style.transform='translateX(0)'; this.style.boxShadow='none';">
                                        Ver Detalles
                                        <i class="bi bi-arrow-right"></i>
                                    </a>

                                </div>

                            </div>
                        @endforeach
                    </div>

                    <!-- Pagination -->
                    <div style="margin-top: 32px;">
                        {{ $orders->links() }}
                    </div>

                @else
                    <!-- Empty State -->
                    <div style="background: var(--vitta-black-soft); border: 1px solid rgba(212, 175, 55, 0.2); border-radius: 8px; padding: 60px 40px; text-align: center;">
                        <div style="width: 80px; height: 80px; margin: 0 auto 24px; background: rgba(212, 175, 55, 0.1); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                            <i class="bi bi-receipt" style="font-size: 36px; color: var(--vitta-gold);"></i>
                        </div>
                        <h3 style="font-size: 20px; font-weight: 600; color: var(--vitta-pearl); margin-bottom: 12px;">
                            No se encontraron pedidos
                        </h3>
                        <p style="color: var(--vitta-pearl); opacity: 0.7; margin-bottom: 24px;">
                            @if(request()->hasAny(['search', 'status', 'payment_status']))
                                No hay pedidos que coincidan con tu búsqueda.
                            @else
                                Aún no has realizado ninguna compra.
                            @endif
                        </p>
                        <a href="{{ route('home') }}" 
                           style="display: inline-flex; align-items: center; gap: 8px; padding: 12px 28px; background: var(--vitta-gold); color: var(--vitta-black); text-decoration: none; border-radius: 6px; font-weight: 600; transition: all 0.3s;"
                           onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 4px 12px rgba(212, 175, 55, 0.3)';"
                           onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none';">
                            <i class="bi bi-shop"></i>
                            Ir a la Tienda
                        </a>
                    </div>
                @endif

            </div>

        </div>
    </div>
</div>
@endsection
