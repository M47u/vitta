@extends('layouts.app')

@section('title', 'Mi Cuenta - Vitta Perfumes')

@section('content')

<!-- Header -->
<section style="padding: 48px 0; background: var(--vitta-black-soft); border-bottom: 1px solid rgba(212, 175, 55, 0.2);">
    <div class="vitta-container">
        <h1 style="font-size: 48px; color: var(--vitta-gold); text-align: center;">
            Mi Cuenta
        </h1>
    </div>
</section>

<!-- Dashboard Content -->
<section style="padding: 64px 0;">
    <div class="vitta-container">
        
        <div style="display: grid; grid-template-columns: 280px 1fr; gap: 32px;">
            
            <!-- Sidebar Menu -->
            @include('customer.partials.sidebar')

            <!-- Main Content -->
            <div>
                
                <!-- Welcome Message -->
                <div style="background: linear-gradient(135deg, rgba(212, 175, 55, 0.1) 0%, rgba(26, 26, 26, 0.8) 100%); border: 1px solid rgba(212, 175, 55, 0.3); border-radius: 12px; padding: 32px; margin-bottom: 32px;">
                    <h2 style="font-size: 28px; color: var(--vitta-gold); margin-bottom: 12px;">
                        ¡Hola, {{ $user->name }}!
                    </h2>
                    <p style="color: var(--vitta-pearl); opacity: 0.8;">
                        Bienvenido a tu panel de control. Aquí puedes gestionar tus pedidos, direcciones y más.
                    </p>
                </div>

                <!-- Stats Cards -->
                <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px; margin-bottom: 40px;">
                    
                    <div style="background: var(--vitta-black-soft); border: 1px solid rgba(212, 175, 55, 0.2); border-radius: 8px; padding: 24px; text-align: center;">
                        <i class="bi bi-receipt" style="font-size: 32px; color: var(--vitta-gold); display: block; margin-bottom: 12px;"></i>
                        <p style="font-size: 28px; font-weight: 700; color: var(--vitta-pearl); margin-bottom: 4px;">
                            {{ $stats['total_orders'] }}
                        </p>
                        <p style="font-size: 13px; color: var(--vitta-pearl); opacity: 0.7;">
                            Pedidos Totales
                        </p>
                    </div>

                    <div style="background: var(--vitta-black-soft); border: 1px solid rgba(212, 175, 55, 0.2); border-radius: 8px; padding: 24px; text-align: center;">
                        <i class="bi bi-hourglass-split" style="font-size: 32px; color: #fbbf24; display: block; margin-bottom: 12px;"></i>
                        <p style="font-size: 28px; font-weight: 700; color: var(--vitta-pearl); margin-bottom: 4px;">
                            {{ $stats['pending_orders'] }}
                        </p>
                        <p style="font-size: 13px; color: var(--vitta-pearl); opacity: 0.7;">
                            En Proceso
                        </p>
                    </div>

                    <div style="background: var(--vitta-black-soft); border: 1px solid rgba(212, 175, 55, 0.2); border-radius: 8px; padding: 24px; text-align: center;">
                        <i class="bi bi-cash-coin" style="font-size: 32px; color: #22c55e; display: block; margin-bottom: 12px;"></i>
                        <p style="font-size: 28px; font-weight: 700; color: var(--vitta-pearl); margin-bottom: 4px;">
                            ${{ number_format($stats['total_spent'], 0, ',', '.') }}
                        </p>
                        <p style="font-size: 13px; color: var(--vitta-pearl); opacity: 0.7;">
                            Total Gastado
                        </p>
                    </div>

                    <div style="background: var(--vitta-black-soft); border: 1px solid rgba(212, 175, 55, 0.2); border-radius: 8px; padding: 24px; text-align: center;">
                        <i class="bi bi-heart-fill" style="font-size: 32px; color: #ef4444; display: block; margin-bottom: 12px;"></i>
                        <p style="font-size: 28px; font-weight: 700; color: var(--vitta-pearl); margin-bottom: 4px;">
                            {{ $stats['wishlist_count'] }}
                        </p>
                        <p style="font-size: 13px; color: var(--vitta-pearl); opacity: 0.7;">
                            Favoritos
                        </p>
                    </div>

                </div>

                <!-- Recent Orders -->
                <div style="background: var(--vitta-black-soft); border: 1px solid rgba(212, 175, 55, 0.2); border-radius: 8px; padding: 32px;">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
                        <h3 style="font-size: 20px; color: var(--vitta-gold);">
                            <i class="bi bi-clock-history" style="margin-right: 8px;"></i>
                            Pedidos Recientes
                        </h3>
                        <a href="{{ route('customer.orders') }}" style="color: var(--vitta-gold); text-decoration: none; font-size: 14px;">
                            Ver todos <i class="bi bi-arrow-right" style="margin-left: 4px;"></i>
                        </a>
                    </div>

                    @if($recentOrders->count() > 0)
                        <div style="display: grid; gap: 16px;">
                            @foreach($recentOrders as $order)
                            <a href="{{ route('customer.orders.show', $order->id) }}" 
                               style="display: flex; justify-content: space-between; align-items: center; padding: 20px; background: rgba(212, 175, 55, 0.05); border: 1px solid rgba(212, 175, 55, 0.2); border-radius: 6px; text-decoration: none; transition: all 0.3s;"
                               onmouseover="this.style.background='rgba(212, 175, 55, 0.1)'; this.style.borderColor='var(--vitta-gold)'"
                               onmouseout="this.style.background='rgba(212, 175, 55, 0.05)'; this.style.borderColor='rgba(212, 175, 55, 0.2)'">
                                
                                <div style="flex: 1;">
                                    <p style="font-weight: 600; color: var(--vitta-pearl); margin-bottom: 6px;">
                                        {{ $order->order_number }}
                                    </p>
                                    <p style="font-size: 13px; color: var(--vitta-pearl); opacity: 0.7;">
                                        {{ $order->items->count() }} producto(s) • {{ $order->created_at->format('d/m/Y') }}
                                    </p>
                                </div>

                                <div style="text-align: right; margin: 0 24px;">
                                    <span style="display: inline-block; padding: 6px 12px; border-radius: 20px; font-size: 12px; font-weight: 600;
                                        @if($order->status === 'delivered') background: rgba(34, 197, 94, 0.1); color: #22c55e; border: 1px solid #22c55e;
                                        @elseif($order->status === 'shipped') background: rgba(59, 130, 246, 0.1); color: #3b82f6; border: 1px solid #3b82f6;
                                        @elseif($order->status === 'processing' || $order->status === 'paid') background: rgba(251, 191, 36, 0.1); color: #fbbf24; border: 1px solid #fbbf24;
                                        @else background: rgba(248, 245, 240, 0.1); color: var(--vitta-pearl); border: 1px solid rgba(248, 245, 240, 0.3);
                                        @endif">
                                        {{ $order->status_label }}
                                    </span>
                                </div>

                                <div style="text-align: right;">
                                    <p style="font-size: 20px; font-weight: 700; color: var(--vitta-gold);">
                                        ${{ number_format($order->total, 0, ',', '.') }}
                                    </p>
                                </div>

                            </a>
                            @endforeach
                        </div>
                    @else
                        <div style="text-align: center; padding: 40px 0;">
                            <i class="bi bi-bag-x" style="font-size: 48px; color: rgba(212, 175, 55, 0.3); display: block; margin-bottom: 16px;"></i>
                            <p style="color: var(--vitta-pearl); opacity: 0.7; margin-bottom: 20px;">
                                Aún no tienes pedidos
                            </p>
                            <a href="{{ route('products.index') }}" class="btn-gold">
                                Explorar Productos
                            </a>
                        </div>
                    @endif

                </div>

            </div>

        </div>

    </div>
</section>

@endsection
