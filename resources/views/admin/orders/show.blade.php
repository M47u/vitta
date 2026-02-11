@extends('layouts.admin')

@section('header-title', 'Pedido #' . $order->order_number)
@section('header-subtitle', 'Detalles del pedido')

@section('content')

    <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 24px;">

        <!-- Order Details -->
        <div>

            <!-- Order Items -->
            <div class="card-admin" style="margin-bottom: 24px;">
                <div class="card-header">
                    <h2 class="card-title">Productos del Pedido</h2>
                </div>

                <table class="table-vitta">
                    <thead>
                        <tr>
                            <th>Producto</th>
                            <th>SKU</th>
                            <th>Cantidad</th>
                            <th>Precio Unit.</th>
                            <th>Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($order->items as $item)
                            <tr>
                                <td>
                                    <div style="display: flex; align-items: center; gap: 12px;">
                                        <img src="{{ $item->product->main_image ? asset('storage/' . $item->product->main_image) : 'https://via.placeholder.com/50' }}"
                                            style="width: 50px; height: 50px; object-fit: cover; border-radius: 4px;">
                                        <div>
                                            <p style="font-weight: 600; margin-bottom: 4px;">{{ $item->product_name }}</p>
                                            @if($item->variant_name)
                                                <p style="font-size: 12px; color: #D4AF37;">{{ $item->variant_name }}</p>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <code
                                        style="background: rgba(212, 175, 55, 0.1); padding: 4px 8px; border-radius: 4px; color: #D4AF37; font-size: 11px;">
                                        {{ $item->sku }}
                                    </code>
                                </td>
                                <td>
                                    <span style="font-weight: 600;">{{ $item->quantity }}</span>
                                </td>
                                <td>${{ number_format($item->price, 0, ',', '.') }}</td>
                                <td style="font-weight: 700; color: #D4AF37;">
                                    ${{ number_format($item->subtotal, 0, ',', '.') }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <div style="padding: 24px; border-top: 1px solid rgba(212, 175, 55, 0.2);">
                    <div style="display: flex; justify-content: flex-end;">
                        <div style="width: 300px;">
                            <div
                                style="display: flex; justify-content: space-between; margin-bottom: 12px; color: rgba(248, 245, 240, 0.8);">
                                <span>Subtotal:</span>
                                <span>${{ number_format($order->subtotal, 0, ',', '.') }}</span>
                            </div>
                            <div
                                style="display: flex; justify-content: space-between; margin-bottom: 12px; color: rgba(248, 245, 240, 0.8);">
                                <span>Envío:</span>
                                <span>${{ number_format($order->shipping, 0, ',', '.') }}</span>
                            </div>
                            <div
                                style="display: flex; justify-content: space-between; margin-bottom: 12px; color: rgba(248, 245, 240, 0.8);">
                                <span>IVA (21%):</span>
                                <span>${{ number_format($order->tax, 0, ',', '.') }}</span>
                            </div>
                            @if($order->discount > 0)
                                <div
                                    style="display: flex; justify-content: space-between; margin-bottom: 12px; color: #D4AF37;">
                                    <span>Descuento:</span>
                                    <span>-${{ number_format($order->discount, 0, ',', '.') }}</span>
                                </div>
                            @endif
                            <div style="height: 1px; background: rgba(212, 175, 55, 0.3); margin: 16px 0;"></div>
                            <div
                                style="display: flex; justify-content: space-between; font-size: 20px; font-weight: 700; color: #D4AF37;">
                                <span>TOTAL:</span>
                                <span>${{ number_format($order->total, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Shipping Address -->
            <div class="card-admin">
                <div class="card-header">
                    <h2 class="card-title">Dirección de Envío</h2>
                </div>

                @if($order->address)
                    <div
                        style="padding: 16px; background: rgba(212, 175, 55, 0.05); border: 1px solid rgba(212, 175, 55, 0.2); border-radius: 6px;">
                        <p style="font-weight: 600; margin-bottom: 8px;">{{ $order->address->recipient_name }}</p>
                        <p style="color: rgba(248, 245, 240, 0.8); font-size: 14px; line-height: 1.6;">
                            {{ $order->address->street_address }} {{ $order->address->street_number }}<br>
                            {{ $order->address->city }}, {{ $order->address->state }}<br>
                            CP: {{ $order->address->postal_code }}
                        </p>
                        <p style="color: #D4AF37; font-size: 14px; margin-top: 8px;">
                            <i class="bi bi-telephone"></i> {{ $order->address->recipient_phone }}
                        </p>
                    </div>
                @else
                    <p style="color: rgba(248, 245, 240, 0.5); text-align: center; padding: 24px;">
                        No hay dirección registrada
                    </p>
                @endif

                @if($order->notes)
                    <div
                        style="margin-top: 16px; padding: 16px; background: rgba(59, 130, 246, 0.05); border: 1px solid rgba(59, 130, 246, 0.2); border-radius: 6px;">
                        <p style="font-weight: 600; color: #3b82f6; margin-bottom: 8px;">
                            <i class="bi bi-sticky"></i> Notas del cliente:
                        </p>
                        <p style="color: rgba(248, 245, 240, 0.8); font-size: 14px;">{{ $order->notes }}</p>
                    </div>
                @endif
            </div>

        </div>

        <!-- Sidebar -->
        <div>

            <!-- Order Status -->
            <div class="card-admin" style="margin-bottom: 24px;">
                <div class="card-header">
                    <h2 class="card-title">Estado del Pedido</h2>
                </div>

                <form action="{{ route('admin.orders.update-status', $order) }}" method="POST">
                    @csrf
                    @method('PATCH')

                    <div class="form-group">
                        <label class="form-label">Cambiar Estado</label>
                        <select name="status" class="form-control">
                            <option value="pending" {{ $order->status === 'pending' ? 'selected' : '' }}>Pendiente</option>
                            <option value="processing" {{ $order->status === 'processing' ? 'selected' : '' }}>Procesando
                            </option>
                            <option value="paid" {{ $order->status === 'paid' ? 'selected' : '' }}>Pagado</option>
                            <option value="shipped" {{ $order->status === 'shipped' ? 'selected' : '' }}>Enviado</option>
                            <option value="delivered" {{ $order->status === 'delivered' ? 'selected' : '' }}>Entregado
                            </option>
                            <option value="cancelled" {{ $order->status === 'cancelled' ? 'selected' : '' }}>Cancelado
                            </option>
                        </select>
                    </div>

                    <button type="submit" class="btn-primary" style="width: 100%;">
                        <i class="bi bi-check-circle"></i> Actualizar Estado
                    </button>
                </form>
            </div>

            <!-- Customer Info -->
            <div class="card-admin" style="margin-bottom: 24px;">
                <div class="card-header">
                    <h2 class="card-title">Cliente</h2>
                </div>

                <div style="text-align: center; margin-bottom: 16px;">
                    <div
                        style="width: 60px; height: 60px; background: rgba(212, 175, 55, 0.1); border: 2px solid #D4AF37; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 12px;">
                        <i class="bi bi-person" style="font-size: 28px; color: #D4AF37;"></i>
                    </div>
                    <p style="font-weight: 600; font-size: 16px; margin-bottom: 4px;">
                        {{ $order->user ? $order->user->name : ($order->guest_name ?? 'Cliente Invitado') }}
                    </p>
                    <p style="font-size: 13px; color: rgba(248, 245, 240, 0.6);">
                        {{ $order->user ? $order->user->email : ($order->guest_email ?? 'N/A') }}
                    </p>
                    @if($order->user && $order->user->phone)
                        <p style="font-size: 13px; color: #D4AF37; margin-top: 8px;">
                            <i class="bi bi-whatsapp"></i> {{ $order->user->phone }}
                        </p>
                    @endif
                </div>
            </div>

            <!-- Payment Info -->
            <div class="card-admin">
                <div class="card-header">
                    <h2 class="card-title">Información de Pago</h2>
                </div>

                <div style="display: flex; flex-direction: column; gap: 12px;">
                    <div style="display: flex; justify-content: space-between;">
                        <span style="color: rgba(248, 245, 240, 0.7);">Método:</span>
                        <span style="font-weight: 600; text-transform: capitalize;">{{ $order->payment_method }}</span>
                    </div>

                    <div style="display: flex; justify-content: space-between;">
                        <span style="color: rgba(248, 245, 240, 0.7);">Estado:</span>
                        @php
                            $paymentStatusConfig = [
                                'pending' => ['label' => 'Pendiente', 'class' => 'badge-warning'],
                                'approved' => ['label' => 'Aprobado', 'class' => 'badge-success'],
                                'rejected' => ['label' => 'Rechazado', 'class' => 'badge-danger'],
                            ];
                            $config = $paymentStatusConfig[$order->payment_status] ?? ['label' => $order->payment_status, 'class' => 'badge-warning'];
                        @endphp
                        <span class="badge {{ $config['class'] }}">{{ $config['label'] }}</span>
                    </div>

                    @if($order->paid_at)
                        <div style="display: flex; justify-content: space-between;">
                            <span style="color: rgba(248, 245, 240, 0.7);">Pagado el:</span>
                            <span style="font-weight: 600;">{{ $order->paid_at->format('d/m/Y H:i') }}</span>
                        </div>
                    @endif
                </div>
            </div>

        </div>

    </div>

@endsection