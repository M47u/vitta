@extends('layouts.admin')

@section('title', 'Pagos Pendientes')

@section('content')
<div style="padding: 32px;">
    
    <!-- Header -->
    <div style="margin-bottom: 32px;">
        <h1 style="font-size: 32px; color: var(--vitta-gold); margin-bottom: 8px;">
            <i class="bi bi-hourglass-split" style="margin-right: 12px;"></i>
            Pagos por Transferencia Pendientes
        </h1>
        <p style="color: var(--vitta-pearl); opacity: 0.7;">
            Gestiona los pagos pendientes de confirmación
        </p>
    </div>

    <!-- Search Bar -->
    <div style="background: var(--vitta-black-soft); border: 1px solid rgba(212, 175, 55, 0.2); border-radius: 8px; padding: 20px; margin-bottom: 24px;">
        <form action="{{ route('admin.payments.pending') }}" method="GET">
            <div style="display: flex; gap: 12px; align-items: center;">
                <div style="flex: 1; position: relative;">
                    <i class="bi bi-search" style="position: absolute; left: 16px; top: 50%; transform: translateY(-50%); color: var(--vitta-gold); font-size: 16px;"></i>
                    <input 
                        type="text" 
                        name="search"
                        value="{{ request('search') }}"
                        placeholder="Buscar por número de pedido, cliente o email..."
                        style="width: 100%; padding: 12px 16px 12px 45px; background: var(--vitta-black); border: 1px solid rgba(212, 175, 55, 0.3); border-radius: 6px; color: var(--vitta-pearl); font-size: 15px;"
                    >
                </div>
                <button 
                    type="submit" 
                    style="padding: 12px 28px; background: linear-gradient(135deg, #D4AF37, #B8941F); color: #0A0A0A; border: none; border-radius: 6px; font-weight: 600; cursor: pointer; white-space: nowrap; display: flex; align-items: center; gap: 8px;"
                    onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 4px 12px rgba(212, 175, 55, 0.3)'"
                    onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none'">
                    <i class="bi bi-search"></i> Buscar
                </button>
                @if(request('search'))
                <a href="{{ route('admin.payments.pending') }}" 
                    style="padding: 12px 24px; background: transparent; border: 1px solid rgba(212, 175, 55, 0.3); color: var(--vitta-pearl); text-decoration: none; border-radius: 6px; font-weight: 600; white-space: nowrap; display: flex; align-items: center; gap: 8px;">
                    <i class="bi bi-x-circle"></i> Limpiar
                </a>
                @endif
            </div>
        </form>
        @if(request('search'))
        <p style="color: var(--vitta-pearl); opacity: 0.7; font-size: 14px; margin-top: 12px; margin-bottom: 0;">
            <i class="bi bi-info-circle"></i> Resultados para: <strong style="color: var(--vitta-gold);">"{{ request('search') }}"</strong>
        </p>
        @endif
    </div>

    <!-- Messages -->
    @if(session('success'))
    <div style="background: rgba(34, 197, 94, 0.1); border: 1px solid #22c55e; border-radius: 6px; padding: 16px; margin-bottom: 24px; color: #22c55e;">
        <i class="bi bi-check-circle-fill" style="margin-right: 8px;"></i>
        {{ session('success') }}
    </div>
    @endif

    @if(session('info'))
    <div style="background: rgba(59, 130, 246, 0.1); border: 1px solid #3b82f6; border-radius: 6px; padding: 16px; margin-bottom: 24px; color: #3b82f6;">
        <i class="bi bi-info-circle-fill" style="margin-right: 8px;"></i>
        {{ session('info') }}
    </div>
    @endif

    <!-- Statistics Cards -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 20px; margin-bottom: 32px;">
        <div class="stat-card">
            <div style="display: flex; justify-content: space-between; align-items: start;">
                <div>
                    <p style="color: var(--vitta-pearl); opacity: 0.7; font-size: 13px; margin-bottom: 8px;">
                        Total Pendientes
                        @if(request('search'))
                            <span style="display: block; color: var(--vitta-gold); font-size: 11px; margin-top: 4px;">
                                (filtrado)
                            </span>
                        @endif
                    </p>
                    <p style="font-size: 32px; color: var(--vitta-gold); font-weight: 700;">{{ $stats['total_pending'] }}</p>
                </div>
                <i class="bi bi-clock-history" style="font-size: 32px; color: rgba(212, 175, 55, 0.3);"></i>
            </div>
        </div>

        <div class="stat-card">
            <div style="display: flex; justify-content: space-between; align-items: start;">
                <div>
                    <p style="color: var(--vitta-pearl); opacity: 0.7; font-size: 13px; margin-bottom: 8px;">Con Comprobante</p>
                    <p style="font-size: 32px; color: #22c55e; font-weight: 700;">{{ $stats['with_proof'] }}</p>
                </div>
                <i class="bi bi-file-earmark-check" style="font-size: 32px; color: rgba(34, 197, 94, 0.3);"></i>
            </div>
        </div>

        <div class="stat-card">
            <div style="display: flex; justify-content: space-between; align-items: start;">
                <div>
                    <p style="color: var(--vitta-pearl); opacity: 0.7; font-size: 13px; margin-bottom: 8px;">Sin Comprobante</p>
                    <p style="font-size: 32px; color: #f59e0b; font-weight: 700;">{{ $stats['without_proof'] }}</p>
                </div>
                <i class="bi bi-exclamation-triangle" style="font-size: 32px; color: rgba(245, 158, 11, 0.3);"></i>
            </div>
        </div>

        <div class="stat-card">
            <div style="display: flex; justify-content: space-between; align-items: start;">
                <div>
                    <p style="color: var(--vitta-pearl); opacity: 0.7; font-size: 13px; margin-bottom: 8px;">Monto Total</p>
                    <p style="font-size: 28px; color: var(--vitta-gold); font-weight: 700;">${{ number_format($stats['total_amount'], 0, ',', '.') }}</p>
                </div>
                <i class="bi bi-cash-stack" style="font-size: 32px; color: rgba(212, 175, 55, 0.3);"></i>
            </div>
        </div>
    </div>

    <!-- Orders with Proof (Priority) -->
    @if($withProof->count() > 0)
    <div style="background: rgba(34, 197, 94, 0.05); border: 2px solid #22c55e; border-radius: 8px; padding: 24px; margin-bottom: 24px;">
        <h3 style="color: #22c55e; font-size: 20px; margin-bottom: 20px; display: flex; align-items: center; gap: 10px;">
            <i class="bi bi-file-earmark-check-fill"></i> 
            Pedidos con Comprobante ({{ $withProof->count() }})
            <span style="background: #22c55e; color: white; font-size: 11px; padding: 4px 10px; border-radius: 12px; font-weight: 600;">PRIORIDAD</span>
        </h3>

        <div style="display: flex; flex-direction: column; gap: 16px;">
            @foreach($withProof as $order)
            <div style="background: var(--vitta-black-soft); border: 1px solid rgba(34, 197, 94, 0.3); border-radius: 8px; padding: 20px;">
                <div style="display: grid; grid-template-columns: 1fr 1fr 1fr auto; gap: 20px; align-items: center;">
                    <!-- Order Info -->
                    <div>
                        <p style="color: var(--vitta-gold); font-weight: 600; font-size: 16px; margin-bottom: 4px;">
                            #{{ $order->order_number }}
                        </p>
                        <p style="color: var(--vitta-pearl); opacity: 0.7; font-size: 13px; margin-bottom: 4px;">
                            {{ $order->user ? $order->user->name : $order->guest_name }}
                        </p>
                        <p style="color: var(--vitta-pearl); opacity: 0.5; font-size: 12px;">
                            {{ $order->created_at->diffForHumans() }}
                        </p>
                    </div>

                    <!-- Payment Info -->
                    <div>
                        <p style="color: var(--vitta-pearl); opacity: 0.7; font-size: 13px; margin-bottom: 4px;">Monto</p>
                        <p style="color: var(--vitta-gold); font-weight: 700; font-size: 18px;">${{ number_format($order->total, 0, ',', '.') }}</p>
                    </div>

                    <!-- Proof Info -->
                    <div>
                        <p style="color: var(--vitta-pearl); opacity: 0.7; font-size: 13px; margin-bottom: 4px;">Comprobante</p>
                        <p style="color: #22c55e; font-weight: 600; font-size: 13px; margin-bottom: 4px;">
                            <i class="bi bi-check-circle-fill"></i> Recibido
                        </p>
                        <p style="color: var(--vitta-pearl); opacity: 0.5; font-size: 12px;">
                            {{ $order->payment_proof_uploaded_at->diffForHumans() }}
                        </p>
                    </div>

                    <!-- Actions -->
                    <div style="display: flex; gap: 8px; flex-direction: column;">
                        <a href="{{ route('payment.proof.view', $order) }}" target="_blank"
                            style="padding: 8px 16px; background: rgba(59, 130, 246, 0.2); border: 1px solid #3b82f6; color: #3b82f6; text-decoration: none; border-radius: 4px; font-size: 13px; font-weight: 600; text-align: center; white-space: nowrap;">
                            <i class="bi bi-eye"></i> Ver
                        </a>
                        <form action="{{ route('admin.payments.confirm', $order) }}" method="POST" style="margin: 0;">
                            @csrf
                            <button type="submit" onclick="return confirm('¿Confirmar pago de ${{ number_format($order->total, 0) }}?')"
                                style="width: 100%; padding: 8px 16px; background: #22c55e; color: white; border: none; border-radius: 4px; font-size: 13px; font-weight: 600; cursor: pointer;">
                                <i class="bi bi-check-lg"></i> Confirmar
                            </button>
                        </form>
                        <button onclick="showRejectModal('{{ $order->id }}', '{{ $order->order_number }}')"
                            style="padding: 8px 16px; background: transparent; border: 1px solid #ef4444; color: #ef4444; border-radius: 4px; font-size: 13px; font-weight: 600; cursor: pointer;">
                            <i class="bi bi-x-lg"></i> Rechazar
                        </button>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Orders without Proof -->
    @if($withoutProof->count() > 0)
    <div style="background: rgba(245, 158, 11, 0.05); border: 2px solid #f59e0b; border-radius: 8px; padding: 24px; margin-bottom: 24px;">
        <h3 style="color: #f59e0b; font-size: 20px; margin-bottom: 20px; display: flex; align-items: center; gap: 10px;">
            <i class="bi bi-exclamation-triangle-fill"></i> 
            Pedidos sin Comprobante ({{ $withoutProof->count() }})
        </h3>

        <div style="display: flex; flex-direction: column; gap: 16px;">
            @foreach($withoutProof as $order)
            <div style="background: var(--vitta-black-soft); border: 1px solid rgba(245, 158, 11, 0.3); border-radius: 8px; padding: 20px;">
                <div style="display: grid; grid-template-columns: 1fr 1fr 1fr auto; gap: 20px; align-items: center;">
                    <!-- Order Info -->
                    <div>
                        <p style="color: var(--vitta-gold); font-weight: 600; font-size: 16px; margin-bottom: 4px;">
                            #{{ $order->order_number }}
                        </p>
                        <p style="color: var(--vitta-pearl); opacity: 0.7; font-size: 13px; margin-bottom: 4px;">
                            {{ $order->user ? $order->user->name : $order->guest_name }}
                        </p>
                        <p style="color: var(--vitta-pearl); opacity: 0.5; font-size: 12px;">
                            Hace {{ $order->created_at->diffForHumans() }}
                        </p>
                    </div>

                    <!-- Payment Info -->
                    <div>
                        <p style="color: var(--vitta-pearl); opacity: 0.7; font-size: 13px; margin-bottom: 4px;">Monto</p>
                        <p style="color: var(--vitta-gold); font-weight: 700; font-size: 18px;">${{ number_format($order->total, 0, ',', '.') }}</p>
                    </div>

                    <!-- Status -->
                    <div>
                        <p style="color: var(--vitta-pearl); opacity: 0.7; font-size: 13px; margin-bottom: 4px;">Estado</p>
                        <p style="color: #f59e0b; font-weight: 600; font-size: 13px;">
                            <i class="bi bi-hourglass"></i> Esperando comprobante
                        </p>
                        @if($order->created_at->diffInHours(now()) >= 24)
                        <p style="color: #ef4444; font-size: 12px; margin-top: 4px;">
                            <i class="bi bi-exclamation-circle"></i> +24 horas
                        </p>
                        @endif
                    </div>

                    <!-- Actions -->
                    <div style="display: flex; gap: 8px; flex-direction: column;">
                        <form action="{{ route('admin.payments.reminder', $order) }}" method="POST" style="margin: 0;">
                            @csrf
                            <button type="submit" 
                                {{ $order->payment_reminder_sent_at ? 'disabled' : '' }}
                                style="width: 100%; padding: 8px 16px; background: {{ $order->payment_reminder_sent_at ? 'rgba(59, 130, 246, 0.3)' : '#3b82f6' }}; color: white; border: none; border-radius: 4px; font-size: 13px; font-weight: 600; cursor: {{ $order->payment_reminder_sent_at ? 'not-allowed' : 'pointer' }};">
                                <i class="bi bi-envelope"></i> 
                                @if($order->payment_reminder_sent_at)
                                    Enviado
                                @else
                                    Recordar
                                @endif
                            </button>
                        </form>
                        <a href="{{ route('admin.orders.show', $order) }}"
                            style="padding: 8px 16px; background: transparent; border: 1px solid var(--vitta-gold); color: var(--vitta-gold); text-decoration: none; border-radius: 4px; font-size: 13px; font-weight: 600; text-align: center;">
                            <i class="bi bi-eye"></i> Ver Pedido
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    @if($pendingPayments->count() === 0)
    <div style="background: var(--vitta-black-soft); border: 1px solid rgba(212, 175, 55, 0.2); border-radius: 8px; padding: 48px; text-align: center;">
        @if(request('search'))
            <i class="bi bi-search" style="font-size: 64px; color: rgba(212, 175, 55, 0.3); margin-bottom: 16px;"></i>
            <h3 style="color: var(--vitta-pearl); font-size: 20px; margin-bottom: 8px;">
                No se encontraron resultados
            </h3>
            <p style="color: var(--vitta-pearl); opacity: 0.6; margin-bottom: 24px;">
                No hay pedidos que coincidan con "{{ request('search') }}"
            </p>
            <a href="{{ route('admin.payments.pending') }}" 
                style="display: inline-flex; align-items: center; gap: 8px; padding: 12px 24px; background: var(--vitta-gold); color: #0A0A0A; text-decoration: none; border-radius: 6px; font-weight: 600;">
                <i class="bi bi-arrow-left"></i> Ver todos los pagos pendientes
            </a>
        @else
            <i class="bi bi-check-circle" style="font-size: 64px; color: rgba(212, 175, 55, 0.3); margin-bottom: 16px;"></i>
            <h3 style="color: var(--vitta-pearl); font-size: 20px; margin-bottom: 8px;">
                ¡Todo al día!
            </h3>
            <p style="color: var(--vitta-pearl); opacity: 0.6;">
                No hay pagos pendientes de confirmación en este momento.
            </p>
        @endif
    </div>
    @endif

</div>

<!-- Reject Modal -->
<div id="rejectModal" style="display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.8); z-index: 1000; align-items: center; justify-content: center;">
    <div style="background: var(--vitta-black-soft); border: 2px solid #ef4444; border-radius: 8px; padding: 32px; max-width: 500px; width: 90%;">
        <h3 style="color: #ef4444; font-size: 20px; margin-bottom: 16px;">
            <i class="bi bi-x-circle-fill"></i> Rechazar Pago
        </h3>
        <p style="color: var(--vitta-pearl); margin-bottom: 20px;">
            Pedido: <strong id="rejectOrderNumber"></strong>
        </p>
        <form id="rejectForm" method="POST">
            @csrf
            <div style="margin-bottom: 20px;">
                <label style="display: block; color: var(--vitta-pearl); font-weight: 600; margin-bottom: 8px;">
                    Motivo del rechazo:
                </label>
                <textarea name="reason" required
                    style="width: 100%; padding: 12px; background: var(--vitta-black); border: 1px solid #ef4444; border-radius: 6px; color: var(--vitta-pearl); font-size: 14px; min-height: 100px;"></textarea>
            </div>
            <div style="display: flex; gap: 12px; justify-content: flex-end;">
                <button type="button" onclick="closeRejectModal()"
                    style="padding: 10px 20px; background: transparent; border: 1px solid var(--vitta-pearl); color: var(--vitta-pearl); border-radius: 4px; cursor: pointer;">
                    Cancelar
                </button>
                <button type="submit"
                    style="padding: 10px 20px; background: #ef4444; color: white; border: none; border-radius: 4px; cursor: pointer; font-weight: 600;">
                    Rechazar Pago
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function showRejectModal(orderId, orderNumber) {
        document.getElementById('rejectModal').style.display = 'flex';
        document.getElementById('rejectOrderNumber').textContent = '#' + orderNumber;
        document.getElementById('rejectForm').action = '/admin/payments/' + orderId + '/reject';
    }

    function closeRejectModal() {
        document.getElementById('rejectModal').style.display = 'none';
    }

    // Close modal on outside click
    document.getElementById('rejectModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeRejectModal();
        }
    });
</script>
@endsection
