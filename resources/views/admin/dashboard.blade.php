@extends('layouts.admin')

@section('header-title', 'Dashboard')
@section('header-subtitle', 'Vista general de tu tienda')

@section('content')

    <!-- Stats Grid -->
    <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 24px; margin-bottom: 32px;">

        <!-- Total Orders -->
        <div class="stat-card">
            <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 16px;">
                <div
                    style="width: 48px; height: 48px; background: rgba(212, 175, 55, 0.1); border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                    <i class="bi bi-receipt" style="font-size: 24px; color: #D4AF37;"></i>
                </div>
            </div>
            <h3 style="font-size: 32px; font-weight: 700; color: #F8F5F0; margin-bottom: 8px;">
                {{ $stats['total_orders'] }}
            </h3>
            <p style="color: rgba(248, 245, 240, 0.6); font-size: 14px;">Total Pedidos</p>
        </div>

        <!-- Pending Orders -->
        <div class="stat-card">
            <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 16px;">
                <div
                    style="width: 48px; height: 48px; background: rgba(251, 191, 36, 0.1); border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                    <i class="bi bi-clock-history" style="font-size: 24px; color: #fbbf24;"></i>
                </div>
                @if($stats['pending_orders'] > 0)
                    <span
                        style="background: #ef4444; color: white; padding: 4px 8px; border-radius: 12px; font-size: 12px; font-weight: 700;">
                        {{ $stats['pending_orders'] }}
                    </span>
                @endif
            </div>
            <h3 style="font-size: 32px; font-weight: 700; color: #F8F5F0; margin-bottom: 8px;">
                {{ $stats['pending_orders'] }}
            </h3>
            <p style="color: rgba(248, 245, 240, 0.6); font-size: 14px;">Pedidos Pendientes</p>
        </div>

        <!-- Revenue -->
        <div class="stat-card">
            <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 16px;">
                <div
                    style="width: 48px; height: 48px; background: rgba(34, 197, 94, 0.1); border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                    <i class="bi bi-currency-dollar" style="font-size: 24px; color: #22c55e;"></i>
                </div>
            </div>
            <h3 style="font-size: 32px; font-weight: 700; color: #F8F5F0; margin-bottom: 8px;">
                ${{ number_format($stats['total_revenue'], 0, ',', '.') }}
            </h3>
            <p style="color: rgba(248, 245, 240, 0.6); font-size: 14px;">Ingresos Totales</p>
        </div>

        <!-- Low Stock -->
        <div class="stat-card">
            <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 16px;">
                <div
                    style="width: 48px; height: 48px; background: rgba(239, 68, 68, 0.1); border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                    <i class="bi bi-exclamation-triangle" style="font-size: 24px; color: #ef4444;"></i>
                </div>
            </div>
            <h3 style="font-size: 32px; font-weight: 700; color: #F8F5F0; margin-bottom: 8px;">
                {{ $stats['low_stock'] }}
            </h3>
            <p style="color: rgba(248, 245, 240, 0.6); font-size: 14px;">Productos con Stock Bajo</p>
        </div>

    </div>

    <!-- Recent Orders Card -->
    <div class="card-admin">
        <div class="card-header">
            <h2 class="card-title">Pedidos Recientes</h2>
        </div>

        @if($recentOrders->count() > 0)
            <table class="table-vitta">
                <thead>
                    <tr>
                        <th>Nº Pedido</th>
                        <th>Cliente</th>
                        <th>Total</th>
                        <th>Estado</th>
                        <th>Fecha</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($recentOrders as $order)
                        <tr>
                            <td style="color: #D4AF37; font-weight: 600;">#{{ $order->order_number }}</td>
                            <td>{{ $order->user->name }}</td>
                            <td>${{ number_format($order->total, 0, ',', '.') }}</td>
                            <td>
                                <span class="badge badge-warning">{{ $order->status }}</span>
                            </td>
                            <td>{{ $order->created_at->format('d/m/Y') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p style="text-align: center; color: rgba(248, 245, 240, 0.5); padding: 32px;">
                No hay pedidos aún. Los pedidos aparecerán aquí cuando los clientes realicen compras.
            </p>
        @endif
    </div>

    <!-- Top Products Card -->
    <div class="card-admin" style="margin-top: 32px;">
        <div class="card-header">
            <h2 class="card-title">Top Productos</h2>
        </div>

        <div style="display: flex; flex-direction: column; gap: 16px;">
            @foreach($topProducts as $product)
                <div
                    style="display: flex; align-items: center; gap: 12px; padding: 12px; background: rgba(212, 175, 55, 0.05); border-radius: 6px; border: 1px solid rgba(212, 175, 55, 0.1);">
                    <img src="{{ $product->main_image ?? '/images/placeholder.jpg' }}"
                        style="width: 50px; height: 50px; object-fit: cover; border-radius: 4px;">
                    <div style="flex: 1;">
                        <p style="font-weight: 600; font-size: 14px; margin-bottom: 4px;">{{ $product->name }}</p>
                        <p style="font-size: 12px; color: rgba(248, 245, 240, 0.6);">{{ $product->sales_count }} ventas</p>
                    </div>
                    <div style="text-align: right;">
                        <p style="font-weight: 700; color: #D4AF37; font-size: 14px;">
                            ${{ number_format($product->base_price, 0, ',', '.') }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

@endsection