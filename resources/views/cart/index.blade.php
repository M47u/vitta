@extends('layouts.app')

@section('title', 'Mi Carrito - Vitta Perfumes')

@php
    use App\Models\Setting;
    $freeShippingMinimum = Setting::get('free_shipping_minimum', 50000);
@endphp

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
                                <img src="{{ $item->product->main_image ? asset('storage/' . $item->product->main_image) : 'https://via.placeholder.com/120x150/1A1A1A/D4AF37' }}"
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
                                        <button onclick="changeQuantity({{ $item->id }}, -1)"
                                            style="width: 32px; height: 32px; background: var(--vitta-black); border: 1px solid rgba(212, 175, 55, 0.3); color: var(--vitta-gold); cursor: pointer; border-radius: 4px; font-size: 16px;">
                                            -
                                        </button>
                                        <span id="quantity-{{ $item->id }}"
                                            style="font-weight: 600; color: var(--vitta-pearl); min-width: 30px; text-align: center;">
                                            {{ $item->quantity }}
                                        </span>
                                        <button onclick="changeQuantity({{ $item->id }}, 1)"
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

                                <div id="shipping-message" 
                                    style="padding: 12px; border-radius: 4px; margin-top: 16px; transition: all 0.3s;
                                    @if($cart->subtotal >= $freeShippingMinimum)
                                        background: rgba(34, 197, 94, 0.1); border: 1px solid #22c55e;
                                    @else
                                        background: rgba(251, 191, 36, 0.1); border: 1px solid #fbbf24;
                                    @endif
                                    ">
                                    <p id="shipping-text" style="text-align: center; margin: 0;
                                        @if($cart->subtotal >= $freeShippingMinimum)
                                            color: #22c55e; font-size: 14px;
                                        @else
                                            color: #fbbf24; font-size: 13px;
                                        @endif
                                        ">
                                        @if($cart->subtotal >= $freeShippingMinimum)
                                            <i class="bi bi-truck" style="margin-right: 8px;"></i>
                                            ¡Envío gratis!
                                        @else
                                            Te faltan ${{ number_format($freeShippingMinimum - $cart->subtotal, 0, ',', '.') }} para envío gratis
                                        @endif
                                    </p>
                                </div>
                            </div>

                            <div class="golden-line" style="margin: 24px 0;"></div>

                            <div style="display: flex; justify-content: space-between; margin-bottom: 32px;">
                                <span style="font-size: 20px; color: var(--vitta-gold); font-weight: 600;">TOTAL:</span>
                                <span id="cart-total" style="font-size: 24px; color: var(--vitta-gold); font-weight: 700;">
                                    ${{ number_format($cart->total, 0, ',', '.') }}
                                </span>
                            </div>

                            <a href="{{ route("checkout.index") }}" class="btn-gold"
                                style="width: 100%; text-align: center; padding: 18px; text-decoration: none; display: block; margin-bottom: 12px;">
                                <i class="bi bi-lock" style="margin-right: 8px;"></i>
                                FINALIZAR COMPRA
                            </a>

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
        function changeQuantity(itemId, delta) {
            const quantityElement = document.getElementById(`quantity-${itemId}`);
            const currentQuantity = parseInt(quantityElement.textContent);
            const newQuantity = currentQuantity + delta;

            if (newQuantity < 1) {
                if (!confirm('¿Eliminar este producto del carrito?')) {
                    return;
                }
                updateQuantity(itemId, 0);
                return;
            }

            updateQuantity(itemId, newQuantity);
        }

        function updateQuantity(itemId, newQuantity) {

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
                            // Actualizar cantidad y subtotal del item
                            document.getElementById(`quantity-${itemId}`).textContent = newQuantity;
                            document.getElementById(`subtotal-${itemId}`).textContent = '$' + data.item_subtotal;
                            
                            // Actualizar resumen del pedido
                            document.getElementById('cart-subtotal').textContent = '$' + data.cart_subtotal;
                            document.getElementById('cart-tax').textContent = '$' + data.cart_tax;
                            document.getElementById('cart-total').textContent = '$' + data.cart_total;

                            // Actualizar mensaje de envío gratis
                            const shippingMessage = document.getElementById('shipping-message');
                            const shippingText = document.getElementById('shipping-text');
                            
                            if (shippingMessage && shippingText) {
                                if (data.has_free_shipping) {
                                    // Envío gratis activado
                                    shippingMessage.style.background = 'rgba(34, 197, 94, 0.1)';
                                    shippingMessage.style.borderColor = '#22c55e';
                                    shippingText.style.color = '#22c55e';
                                    shippingText.style.fontSize = '14px';
                                    shippingText.innerHTML = '<i class="bi bi-truck" style="margin-right: 8px;"></i>¡Envío gratis!';
                                } else {
                                    // Aún no califica para envío gratis
                                    shippingMessage.style.background = 'rgba(251, 191, 36, 0.1)';
                                    shippingMessage.style.borderColor = '#fbbf24';
                                    shippingText.style.color = '#fbbf24';
                                    shippingText.style.fontSize = '13px';
                                    shippingText.innerHTML = 'Te faltan $' + new Intl.NumberFormat('es-AR').format(data.free_shipping_remaining) + ' para envío gratis';
                                }
                            }

                            // Actualizar badge del navbar
                            const badge = document.querySelector('.cart-badge');
                            if (badge) {
                                badge.textContent = data.cart_count;
                                badge.style.display = data.cart_count > 0 ? 'flex' : 'none';
                            }

                            // Mostrar feedback visual breve
                            showUpdateFeedback();
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

        function showUpdateFeedback() {
            const totalElement = document.getElementById('cart-total');
            const originalColor = totalElement.style.color;
            
            totalElement.style.color = '#22c55e';
            totalElement.style.transition = 'color 0.3s';
            
            setTimeout(() => {
                totalElement.style.color = originalColor;
            }, 600);
        }
    </script>
@endpush