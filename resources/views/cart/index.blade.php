@extends('layouts.app')

@section('title', 'Mi Carrito - Vitta Perfumes')

@section('content')

    <!-- Header -->
    <section
        style="padding: 48px 0; background: var(--vitta-black-soft); border-bottom: 1px solid rgba(212, 175, 55, 0.2);">
        <div class="vitta-container">
            <h1 style="font-size: 48px; color: var(--vitta-gold); text-align: center;">
                Mi Carrito
            </h1>
        </div>
    </section>

    <!-- Cart Content -->
    <section style="padding: 64px 0; min-height: 60vh;">
        <div class="vitta-container">

            @if(session('success'))
                <div
                    style="background: rgba(34, 197, 94, 0.1); border: 1px solid #22c55e; border-radius: 6px; padding: 16px; margin-bottom: 24px; color: #22c55e;">
                    {{ session('success') }}
                </div>
            @endif

            @if($cart->isEmpty())

                <!-- Empty Cart -->
                <div style="text-align: center; padding: 80px 0;">
                    <i class="bi bi-bag-x"
                        style="font-size: 80px; color: rgba(212, 175, 55, 0.3); display: block; margin-bottom: 24px;"></i>
                    <h2 style="font-size: 28px; color: var(--vitta-pearl); margin-bottom: 16px;">Tu carrito está vacío</h2>
                    <p style="color: var(--vitta-pearl); opacity: 0.7; margin-bottom: 32px;">Descubrí nuestras fragancias únicas
                    </p>
                    <a href="{{ route('products.index') }}" class="btn-gold">
                        EXPLORAR PRODUCTOS
                    </a>
                </div>

            @else

                <!-- Cart with items -->
                <div style="display: grid; grid-template-columns: 1fr 400px; gap: 48px;">

                    <!-- Cart Items -->
                    <div>
                        @foreach($cart->items as $item)
                            <div
                                style="display: grid; grid-template-columns: 120px 1fr auto; gap: 24px; padding: 24px; background: var(--vitta-black-soft); border: 1px solid rgba(212, 175, 55, 0.2); border-radius: 8px; margin-bottom: 16px;">

                                <!-- Image -->
                                <img src="{{ $item->product->main_image ?? 'https://via.placeholder.com/120x150/1A1A1A/D4AF37' }}"
                                    alt="{{ $item->product->name }}"
                                    style="width: 100%; height: 150px; object-fit: cover; border-radius: 4px;">

                                <!-- Info -->
                                <div style="display: flex; flex-direction: column; justify-content: space-between;">
                                    <div>
                                        <h3 style="font-size: 20px; color: var(--vitta-pearl); margin-bottom: 8px;">
                                            <a href="{{ route('products.show', $item->product->slug) }}"
                                                style="text-decoration: none; color: inherit;">
                                                {{ $item->product->name }}
                                            </a>
                                        </h3>
                                        @if($item->productVariant)
                                            <p style="font-size: 14px; color: var(--vitta-gold); margin-bottom: 8px;">
                                                {{ $item->productVariant->name }}
                                            </p>
                                        @endif
                                        <p style="font-size: 14px; color: var(--vitta-pearl); opacity: 0.7;">
                                            Precio unitario: ${{ number_format($item->price, 0, ',', '.') }}
                                        </p>
                                    </div>

                                    <!-- Quantity Controls -->
                                    <div style="display: flex; align-items: center; gap: 12px; margin-top: 16px;">
                                        <button onclick="updateQuantity({{ $item->id }}, {{ $item->quantity - 1 }})"
                                            style="width: 32px; height: 32px; background: var(--vitta-black); border: 1px solid rgba(212, 175, 55, 0.3); color: var(--vitta-gold); cursor: pointer; border-radius: 4px; font-size: 16px;">
                                            -
                                        </button>
                                        <span id="quantity-{{ $item->id }}"
                                            style="font-weight: 600; color: var(--vitta-pearl); min-width: 30px; text-align: center;">
                                            {{ $item->quantity }}
                                        </span>
                                        <button onclick="updateQuantity({{ $item->id }}, {{ $item->quantity + 1 }})"
                                            style="width: 32px; height: 32px; background: var(--vitta-black); border: 1px solid rgba(212, 175, 55, 0.3); color: var(--vitta-gold); cursor: pointer; border-radius: 4px; font-size: 16px;">
                                            +
                                        </button>
                                        <form action="{{ route('cart.destroy', $item->id) }}" method="POST"
                                            style="margin-left: 16px;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" onclick="return confirm('¿Eliminar este producto?')"
                                                style="background: none; border: none; color: #ef4444; cursor: pointer; font-size: 18px;">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>

                                <!-- Price -->
                                <div style="text-align: right;">
                                    <p id="subtotal-{{ $item->id }}"
                                        style="font-size: 24px; font-weight: 700; color: var(--vitta-gold);">
                                        ${{ number_format($item->subtotal, 0, ',', '.') }}
                                    </p>
                                </div>

                            </div>
                        @endforeach
                    </div>

                    <!-- Summary -->
                    <div style="position: sticky; top: 100px; height: fit-content;">
                        <div
                            style="background: var(--vitta-black-soft); border: 1px solid rgba(212, 175, 55, 0.2); border-radius: 8px; padding: 32px;">
                            <h2 style="font-size: 24px; color: var(--vitta-gold); margin-bottom: 24px; text-align: center;">
                                Resumen del Pedido
                            </h2>

                            <div style="margin-bottom: 24px;">
                                <div style="display: flex; justify-content: space-between; margin-bottom: 12px;">
                                    <span style="color: var(--vitta-pearl); opacity: 0.8;">Subtotal:</span>
                                    <span id="cart-subtotal" style="color: var(--vitta-pearl); font-weight: 600;">
                                        ${{ number_format($cart->subtotal, 0, ',', '.') }}
                                    </span>
                                </div>
                                <div style="display: flex; justify-content: space-between; margin-bottom: 12px;">
                                    <span style="color: var(--vitta-pearl); opacity: 0.8;">IVA (21%):</span>
                                    <span id="cart-tax" style="color: var(--vitta-pearl); font-weight: 600;">
                                        ${{ number_format($cart->tax, 0, ',', '.') }}
                                    </span>
                                </div>
                                @if($cart->discount > 0)
                                    <div style="display: flex; justify-content: space-between; margin-bottom: 12px;">
                                        <span style="color: #22c55e;">Descuento:</span>
                                        <span style="color: #22c55e; font-weight: 600;">
                                            -${{ number_format($cart->discount, 0, ',', '.') }}
                                        </span>
                                    </div>
                                @endif

                                @if($cart->subtotal >= 50000)
                                    <div
                                        style="padding: 12px; background: rgba(34, 197, 94, 0.1); border: 1px solid #22c55e; border-radius: 4px; margin-top: 16px;">
                                        <p style="color: #22c55e; font-size: 14px; text-align: center;">
                                            <i class="bi bi-truck" style="margin-right: 8px;"></i>
                                            ¡Envío gratis!
                                        </p>
                                    </div>
                                @else
                                    <div
                                        style="padding: 12px; background: rgba(251, 191, 36, 0.1); border: 1px solid #fbbf24; border-radius: 4px; margin-top: 16px;">
                                        <p style="color: #fbbf24; font-size: 13px; text-align: center;">
                                            Te faltan ${{ number_format(50000 - $cart->subtotal, 0, ',', '.') }} para envío gratis
                                        </p>
                                    </div>
                                @endif
                            </div>

                            <div class="golden-line" style="margin: 24px 0;"></div>

                            <div style="display: flex; justify-content: space-between; margin-bottom: 32px;">
                                <span style="font-size: 20px; color: var(--vitta-gold); font-weight: 600;">TOTAL:</span>
                                <span id="cart-total" style="font-size: 24px; color: var(--vitta-gold); font-weight: 700;">
                                    ${{ number_format($cart->total, 0, ',', '.') }}
                                </span>
                            </div>

                            @auth
                                <a href="{{ route("checkout.index") }}" class="btn-gold"
                                    style="width: 100%; text-align: center; padding: 18px; text-decoration: none; display: block; margin-bottom: 12px;">
                                    <i class="bi bi-lock" style="margin-right: 8px;"></i>
                                    FINALIZAR COMPRA
                                </a>
                            @else
                                <a href="{{ route("login") }}" class="btn-gold"
                                    style="width: 100%; text-align: center; padding: 18px; text-decoration: none; display: block; margin-bottom: 12px;">
                                    <i class="bi bi-box-arrow-in-right" style="margin-right: 8px;"></i>
                                    INICIAR SESIÓN PARA COMPRAR
                                </a>
                            @endauth

                            <a href="{{ route('products.index') }}"
                                style="display: block; text-align: center; padding: 14px; border: 2px solid rgba(212, 175, 55, 0.3); color: var(--vitta-pearl); text-decoration: none; border-radius: 4px; font-weight: 600;">
                                SEGUIR COMPRANDO
                            </a>

                            <div style="margin-top: 24px; text-align: center;">
                                <img src="https://http2.mlstatic.com/frontend-assets/mercadopago-web/v1.0.0/mercadopago-logo.svg"
                                    alt="MercadoPago" style="height: 24px; opacity: 0.7;">
                                <p style="font-size: 12px; color: var(--vitta-pearl); opacity: 0.6; margin-top: 8px;">
                                    Compra 100% segura
                                </p>
                            </div>
                        </div>
                    </div>

                </div>

            @endif

        </div>
    </section>

@endsection

@push('scripts')
    <script>
        function updateQuantity(itemId, newQuantity) {
            if (newQuantity < 0) return;

            if (newQuantity === 0) {
                if (!confirm('¿Eliminar este producto del carrito?')) {
                    return;
                }
            }

            fetch(`/carrito/${itemId}`, {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ quantity: newQuantity })
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        if (newQuantity === 0) {
                            location.reload();
                        } else {
                            document.getElementById(`quantity-${itemId}`).textContent = newQuantity;
                            document.getElementById(`subtotal-${itemId}`).textContent = '$' + data.item_subtotal;
                            document.getElementById('cart-total').textContent = '$' + data.cart_total;

                            // Actualizar badge del navbar
                            const badge = document.querySelector('.cart-badge');
                            if (badge) {
                                badge.textContent = data.cart_count;
                            }
                        }
                    } else {
                        alert(data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Ocurrió un error al actualizar el carrito');
                });
        }
    </script>
@endpush