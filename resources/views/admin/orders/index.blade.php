@extends('layouts.admin')

@section('header-title', 'Pedidos')
@section('header-subtitle', 'Gestiona los pedidos de tu tienda')

@section('content')

    <!-- Filters -->
    <div
        style="display: flex; gap: 12px; margin-bottom: 24px; padding: 20px; background: #1A1A1A; border: 1px solid rgba(212, 175, 55, 0.2); border-radius: 8px;">

        <a href="{{ route('admin.orders.index') }}"
            style="padding: 10px 20px; border-radius: 6px; text-decoration: none; font-weight: 600; font-size: 14px; {{ !request('status') ? 'background: rgba(212, 175, 55, 0.1); color: #D4AF37; border: 1px solid #D4AF37;' : 'background: transparent; color: rgba(248, 245, 240, 0.7); border: 1px solid transparent;' }}">
            Todos
        </a>

        <a href="{{ route('admin.orders.index', ['status' => 'pending']) }}"
            style="padding: 10px 20px; border-radius: 6px; text-decoration: none; font-weight: 600; font-size: 14px; {{ request('status') === 'pending' ? 'background: rgba(251, 191, 36, 0.1); color: #fbbf24; border: 1px solid #fbbf24;' : 'background: transparent; color: rgba(248, 245, 240, 0.7); border: 1px solid transparent;' }}">
            Pendientes
        </a>

        <a href="{{ route('admin.orders.index', ['status' => 'paid']) }}"
            style="padding: 10px 20px; border-radius: 6px; text-decoration: none; font-weight: 600; font-size: 14px; {{ request('status') === 'paid' ? 'background: rgba(34, 197, 94, 0.1); color: #22c55e; border: 1px solid #22c55e;' : 'background: transparent; color: rgba(248, 245, 240, 0.7); border: 1px solid transparent;' }}">
            Pagados
        </a>

        <a href="{{ route('admin.orders.index', ['status' => 'shipped']) }}"
            style="padding: 10px 20px; border-radius: 6px; text-decoration: none; font-weight: 600; font-size: 14px; {{ request('status') === 'shipped' ? 'background: rgba(59, 130, 246, 0.1); color: #3b82f6; border: 1px solid #3b82f6;' : 'background: transparent; color: rgba(248, 245, 240, 0.7); border: 1px solid transparent;' }}">
            Enviados
        </a>

    </div>

    <!-- Orders Table -->
    <div class="card-admin">
        <table class="table-vitta">
            <thead>
                <tr>
                    <th>Nº Pedido</th>
                    <th>Cliente</th>
                    <th>Total</th>
                    <th>Método Pago</th>
                    <th>Estado</th>
                    <th>Fecha</th>
                    <th style="text-align: center;">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($orders as $order)
                    <tr>
                        <td>
                            <span style="font-weight: 700; color: #D4AF37; font-size: 15px;">#{{ $order->order_number }}</span>
                        </td>
                        <td>
                            <div>
                                <p style="font-weight: 600; margin-bottom: 4px;">{{ $order->user->name }}</p>
                                <p style="font-size: 12px; color: rgba(248, 245, 240, 0.6);">{{ $order->user->email }}</p>
                            </div>
                        </td>
                        <td>
                            <span style="font-weight: 700; color: #D4AF37; font-size: 16px;">
                                ${{ number_format($order->total, 0, ',', '.') }}
                            </span>
                        </td>
                        <td style="text-transform: capitalize;">
                            {{ $order->payment_method }}
                        </td>
                        <td>
                            @php
                                $statusConfig = [
                                    'pending' => ['label' => 'Pendiente', 'class' => 'badge-warning'],
                                    'processing' => ['label' => 'Procesando', 'class' => 'badge-info'],
                                    'paid' => ['label' => 'Pagado', 'class' => 'badge-success'],
                                    'shipped' => ['label' => 'Enviado', 'class' => 'badge-info'],
                                    'delivered' => ['label' => 'Entregado', 'class' => 'badge-success'],
                                    'cancelled' => ['label' => 'Cancelado', 'class' => 'badge-danger'],
                                ];
                                $config = $statusConfig[$order->status] ?? ['label' => $order->status, 'class' => 'badge-warning'];
                            @endphp
                            <span class="badge {{ $config['class'] }}">{{ $config['label'] }}</span>
                        </td>
                        <td>
                            <div>
                                <p style="font-size: 13px; margin-bottom: 4px;">{{ $order->created_at->format('d/m/Y') }}</p>
                                <p style="font-size: 11px; color: rgba(248, 245, 240, 0.5);">
                                    {{ $order->created_at->format('H:i') }}</p>
                            </div>
                        </td>
                        <td style="text-align: center;">
                            <a href="{{ route('admin.orders.show', $order) }}"
                                style="width: 32px; height: 32px; display: inline-flex; align-items: center; justify-content: center; background: rgba(212, 175, 55, 0.1); border: 1px solid rgba(212, 175, 55, 0.3); border-radius: 4px; color: #D4AF37; text-decoration: none;">
                                <i class="bi bi-eye"></i>
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" style="text-align: center; padding: 48px;">
                            <i class="bi bi-inbox"
                                style="font-size: 48px; color: rgba(212, 175, 55, 0.3); display: block; margin-bottom: 16px;"></i>
                            <p style="color: rgba(248, 245, 240, 0.5);">No hay pedidos para mostrar</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        @if($orders->hasPages())
            <div style="padding: 24px; border-top: 1px solid rgba(212, 175, 55, 0.2);">
                {{ $orders->links() }}
            </div>
        @endif
    </div>

@endsection