@extends('layouts.app')

@section('title', 'Checkout - Método de Pago')

@section('content')
    <div class="vitta-container" style="padding: 80px 24px;">

        <!-- Progress Steps -->
        <div style="max-width: 800px; margin: 0 auto 64px;">
            <div style="display: flex; justify-content: space-between; position: relative;">
                <div
                    style="position: absolute; top: 16px; left: 0; right: 0; height: 2px; background: var(--vitta-gray); z-index: 0;">
                </div>

                <!-- Step 1 - Complete -->
                <div style="position: relative; z-index: 1; text-align: center; flex: 1;">
                    <div
                        style="width: 32px; height: 32px; border-radius: 50%; background: var(--vitta-gold); margin: 0 auto 12px; display: flex; align-items: center; justify-content: center;">
                        <i class="bi bi-check2" style="color: var(--vitta-black); font-weight: bold;"></i>
                    </div>
                    <span style="font-size: 12px; color: var(--vitta-gold); font-weight: 600;">ENVÍO</span>
                </div>

                <!-- Step 2 - Active -->
                <div style="position: relative; z-index: 1; text-align: center; flex: 1;">
                    <div
                        style="width: 32px; height: 32px; border-radius: 50%; background: var(--vitta-gold); margin: 0 auto 12px; display: flex; align-items: center; justify-content: center; color: var(--vitta-black); font-weight: bold;">
                        2
                    </div>
                    <span style="font-size: 12px; color: var(--vitta-gold); font-weight: 600;">PAGO</span>
                </div>

                <!-- Step 3 -->
                <div style="position: relative; z-index: 1; text-align: center; flex: 1;">
                    <div
                        style="width: 32px; height: 32px; border-radius: 50%; background: var(--vitta-gray); margin: 0 auto 12px; display: flex; align-items: center; justify-content: center; color: var(--vitta-pearl);">
                        3
                    </div>
                    <span style="font-size: 12px; color: var(--vitta-pearl); opacity: 0.5;">CONFIRMACIÓN</span>
                </div>
            </div>
        </div>

        <div style="max-width: 1200px; margin: 0 auto; display: grid; grid-template-columns: 1fr 400px; gap: 48px;">

            <!-- Left Column - Payment Method -->
            <div>
                <h2 style="font-size: 32px; color: var(--vitta-gold); margin-bottom: 24px;">
                    Método de Pago
                </h2>

                <!-- Shipping Address Summary -->
                <div
                    style="background: var(--vitta-black-soft); border: 1px solid var(--vitta-gray); border-radius: 8px; padding: 24px; margin-bottom: 32px;">
                    <div style="display: flex; justify-content: between; align-items: start; margin-bottom: 16px;">
                        <h3 style="font-size: 18px; color: var(--vitta-pearl); flex: 1;">
                            <i class="bi bi-geo-alt-fill" style="color: var(--vitta-gold); margin-right: 8px;"></i>
                            Dirección de Envío
                        </h3>
                        <a href="{{ route('checkout.index') }}"
                            style="color: var(--vitta-gold); font-size: 14px; text-decoration: none;">
                            Cambiar
                        </a>
                    </div>
                    <p style="color: var(--vitta-pearl); margin-bottom: 8px;">
                        <strong>{{ $address->recipient_name }}</strong> - {{ $address->recipient_phone }}
                    </p>
                    <p style="color: var(--vitta-pearl); opacity: 0.7; font-size: 14px;">
                        {{ $address->full_address }}
                    </p>

                    @if(!empty($shippingOptions) && count($shippingOptions) > 0)
                        <div class="golden-line" style="margin: 20px 0;"></div>
                        <h4 style="font-size: 16px; color: var(--vitta-gold); margin-bottom: 16px;">
                            <i class="bi bi-truck" style="margin-right: 8px;"></i>
                            Opciones de Envío
                        </h4>
                        <div style="display: flex; flex-direction: column; gap: 12px;">
                            @foreach($shippingOptions as $index => $option)
                                <div style="padding: 12px; background: var(--vitta-black); border: 1px solid {{ $index === 0 ? 'var(--vitta-gold)' : 'var(--vitta-gray)' }}; border-radius: 4px;">
                                    <div style="display: flex; justify-content: space-between; align-items: center;">
                                        <div>
                                            <p style="color: var(--vitta-pearl); font-size: 14px; font-weight: 600; margin-bottom: 4px;">
                                                {{ ucfirst($option['name'] ?? 'Envío') }}
                                            </p>
                                            @if(isset($option['estimated_delivery_time']['shipping']))
                                                <p style="color: var(--vitta-pearl); opacity: 0.6; font-size: 12px;">
                                                    Llega en {{ $option['estimated_delivery_time']['shipping'] }} días hábiles
                                                </p>
                                            @endif
                                        </div>
                                        <p style="color: {{ $index === 0 ? 'var(--vitta-gold)' : 'var(--vitta-pearl)' }}; font-weight: 600;">
                                            ${{ number_format($option['cost'], 0, ',', '.') }}
                                        </p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <p style="color: var(--vitta-pearl); opacity: 0.6; font-size: 12px; margin-top: 12px;">
                            <i class="bi bi-info-circle" style="margin-right: 4px;"></i>
                            Cotización en tiempo real con MercadoEnvíos
                        </p>
                    @endif
                </div>

                <!-- Payment Options -->
                <div
                    style="background: var(--vitta-black-soft); border: 1px solid var(--vitta-gray); border-radius: 8px; padding: 32px;">
                    <h3 style="font-size: 18px; color: var(--vitta-pearl); margin-bottom: 24px;">
                        Seleccionar Método de Pago
                    </h3>

                    <!-- MercadoPago Option -->
                    <div style="margin-bottom: 16px;">
                        <label
                            id="mercadopago-label"
                            style="display: flex; align-items: center; padding: 20px; border: 2px solid var(--vitta-gold); border-radius: 8px; cursor: pointer; background: rgba(212, 175, 55, 0.05); transition: all 0.3s;">
                            <input type="radio" name="payment_method" value="mercadopago" checked
                                style="margin-right: 16px; width: 20px; height: 20px;">
                            <div style="flex: 1;">
                                <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 8px;">
                                    <i class="bi bi-credit-card-2-front" style="font-size: 24px; color: #009ee3;"></i>
                                    <span style="font-size: 18px; font-weight: 600; color: var(--vitta-pearl);">MercadoPago</span>
                                    <span style="background: #fbbf24; color: var(--vitta-black); padding: 2px 8px; border-radius: 4px; font-size: 11px; font-weight: 600;">-7.5% COM.</span>
                                </div>
                                <p style="color: var(--vitta-pearl); opacity: 0.7; font-size: 13px;">
                                    Tarjetas de crédito, débito y otros medios de pago
                                </p>
                            </div>
                            <div style="display: flex; gap: 8px; flex-wrap: wrap;">
                                <img src="https://imgmp.mlstatic.com/org-img/banners/ar/medios/online/575X40.jpg"
                                    alt="Medios de pago" style="height: 30px; border-radius: 4px;">
                            </div>
                        </label>
                    </div>

                    <!-- Bank Transfer Option -->
                    <div style="margin-bottom: 24px;">
                        <label
                            id="transfer-label"
                            style="display: flex; align-items: center; padding: 20px; border: 2px solid var(--vitta-gray); border-radius: 8px; cursor: pointer; background: transparent; transition: all 0.3s;">
                            <input type="radio" name="payment_method" value="transfer"
                                style="margin-right: 16px; width: 20px; height: 20px;">
                            <div style="flex: 1;">
                                <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 8px;">
                                    <i class="bi bi-bank2" style="font-size: 24px; color: var(--vitta-gold);"></i>
                                    <span style="font-size: 18px; font-weight: 600; color: var(--vitta-pearl);">Transferencia Bancaria</span>
                                    <span style="background: #10b981; color: white; padding: 2px 8px; border-radius: 4px; font-size: 11px; font-weight: 600;">SIN COMISIÓN</span>
                                </div>
                                <p style="color: var(--vitta-pearl); opacity: 0.7; font-size: 13px;">
                                    Te enviaremos los datos bancarios para que realices la transferencia
                                </p>
                            </div>
                        </label>
                    </div>

                    <!-- Notes -->
                    <div style="margin-bottom: 24px;">
                        <label style="display: block; color: var(--vitta-pearl); font-size: 14px; margin-bottom: 8px;">
                            Notas del Pedido (Opcional)
                        </label>
                        <textarea id="order-notes" rows="4"
                            style="width: 100%; padding: 12px; background: var(--vitta-black); border: 1px solid var(--vitta-gray); border-radius: 4px; color: var(--vitta-pearl); font-size: 14px; resize: vertical;"
                            placeholder="¿Alguna instrucción especial para tu pedido?"></textarea>
                    </div>

                    <!-- Security Notice -->
                    <div
                        style="background: rgba(212, 175, 55, 0.1); border: 1px solid var(--vitta-gold); border-radius: 4px; padding: 16px; margin-bottom: 24px;">
                        <div style="display: flex; align-items: start; gap: 12px;">
                            <i class="bi bi-shield-check" style="color: var(--vitta-gold); font-size: 24px;"></i>
                            <div>
                                <h4
                                    style="color: var(--vitta-gold); font-size: 14px; margin-bottom: 8px; font-weight: 600;">
                                    Pago 100% Seguro
                                </h4>
                                <p style="color: var(--vitta-pearl); font-size: 13px; opacity: 0.8;">
                                    Tu información está protegida con encriptación SSL. MercadoPago procesará tu pago de
                                    forma segura.
                                </p>
                            </div>
                        </div>
                    </div>

                    <button id="checkout-btn" class="btn-gold"
                        style="width: 100%; justify-content: center; display: flex; align-items: center; gap: 12px;">
                        <i class="bi bi-lock-fill"></i>
                        PROCEDER AL PAGO
                    </button>

                    <p
                        style="color: var(--vitta-pearl); opacity: 0.5; font-size: 12px; text-align: center; margin-top: 16px;">
                        Al continuar, aceptás nuestros <a href="#" style="color: var(--vitta-gold);">términos y
                            condiciones</a>
                    </p>
                </div>
            </div>

            <!-- Right Column - Order Summary -->
            <div>
                <div
                    style="background: var(--vitta-black-soft); border: 1px solid var(--vitta-gray); border-radius: 8px; padding: 32px; position: sticky; top: 24px;">
                    <h3 style="font-size: 20px; color: var(--vitta-gold); margin-bottom: 24px;">
                        Resumen del Pedido
                    </h3>

                    <div style="display: flex; flex-direction: column; gap: 16px; margin-bottom: 24px;">
                        @foreach($cart->items as $item)
                            <div style="display: flex; gap: 16px;">
                                <img src="{{ $item->product->main_image ? asset('storage/' . $item->product->main_image) : 'https://via.placeholder.com/80' }}"
                                    alt="{{ $item->product->name }}"
                                    style="width: 60px; height: 60px; object-fit: cover; border-radius: 4px;">
                                <div style="flex: 1;">
                                    <p style="color: var(--vitta-pearl); font-size: 14px; margin-bottom: 4px;">
                                        {{ $item->product->name }}
                                    </p>
                                    @if($item->variant)
                                        <p style="color: var(--vitta-pearl); opacity: 0.6; font-size: 12px;">
                                            {{ $item->variant->name }}
                                        </p>
                                    @endif
                                    <p style="color: var(--vitta-pearl); opacity: 0.6; font-size: 12px;">
                                        Cantidad: {{ $item->quantity }}
                                    </p>
                                </div>
                                <p style="color: var(--vitta-gold); font-weight: 600;">
                                    ${{ number_format(($item->product->discount_price ?? $item->product->base_price) * $item->quantity, 2) }}
                                </p>
                            </div>
                        @endforeach
                    </div>

                    <div class="golden-line" style="margin: 24px 0;"></div>

                    <div style="display: flex; flex-direction: column; gap: 12px; margin-bottom: 24px;">
                        <div style="display: flex; justify-content: space-between;">
                            <span style="color: var(--vitta-pearl); opacity: 0.7;">Subtotal:</span>
                            <span style="color: var(--vitta-pearl);">${{ number_format($subtotal, 0, ',', '.') }}</span>
                        </div>
                        <div style="display: flex; justify-content: space-between;">
                            <span style="color: var(--vitta-pearl); opacity: 0.7;">IVA (21%):</span>
                            <span style="color: var(--vitta-pearl);">${{ number_format($tax, 0, ',', '.') }}</span>
                        </div>
                        <div style="display: flex; justify-content: space-between;">
                            <span style="color: var(--vitta-pearl); opacity: 0.7;">Envío:</span>
                            <span style="color: var(--vitta-pearl);">${{ number_format($shipping, 0, ',', '.') }}</span>
                        </div>
                    </div>

                    <div class="golden-line" style="margin: 24px 0;"></div>

                    <div style="display: flex; justify-content: space-between; font-size: 20px;">
                        <span style="color: var(--vitta-gold); font-weight: 600;">Total:</span>
                        <span style="color: var(--vitta-gold); font-weight: 600;">${{ number_format($total, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- MercadoPago SDK -->
    <script src="https://sdk.mercadopago.com/js/v2"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const checkoutBtn = document.getElementById('checkout-btn');
            const paymentMethodRadios = document.querySelectorAll('input[name="payment_method"]');
            const mercadopagoLabel = document.getElementById('mercadopago-label');
            const transferLabel = document.getElementById('transfer-label');

            // Handle payment method selection styling
            paymentMethodRadios.forEach(radio => {
                radio.addEventListener('change', function() {
                    if (this.value === 'mercadopago') {
                        mercadopagoLabel.style.border = '2px solid var(--vitta-gold)';
                        mercadopagoLabel.style.background = 'rgba(212, 175, 55, 0.05)';
                        transferLabel.style.border = '2px solid var(--vitta-gray)';
                        transferLabel.style.background = 'transparent';
                        checkoutBtn.innerHTML = '<i class="bi bi-lock-fill"></i> PROCEDER AL PAGO';
                    } else if (this.value === 'transfer') {
                        transferLabel.style.border = '2px solid var(--vitta-gold)';
                        transferLabel.style.background = 'rgba(212, 175, 55, 0.05)';
                        mercadopagoLabel.style.border = '2px solid var(--vitta-gray)';
                        mercadopagoLabel.style.background = 'transparent';
                        checkoutBtn.innerHTML = '<i class="bi bi-bank2"></i> CONFIRMAR Y VER DATOS BANCARIOS';
                    }
                });
            });

            checkoutBtn.addEventListener('click', async function () {
                const paymentMethod = document.querySelector('input[name="payment_method"]:checked').value;
                const notes = document.getElementById('order-notes').value;

                // Disable button
                checkoutBtn.disabled = true;
                checkoutBtn.innerHTML = '<i class="bi bi-hourglass-split"></i> Procesando...';

                try {
                    // Make request to process order
                    const response = await fetch('{{ route("checkout.process", $addressId) }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            payment_method: paymentMethod,
                            notes: notes
                        })
                    });

                    const data = await response.json();

                    if (data.success) {
                        if (paymentMethod === 'mercadopago' && data.init_point) {
                            // Abrir MercadoPago en nueva pestaña
                            window.open(data.init_point, '_blank');
                            checkoutBtn.innerHTML = '<i class="bi bi-check-circle"></i> Pedido creado - Completá el pago en la nueva pestaña';
                            checkoutBtn.style.background = '#10b981';
                        } else if (paymentMethod === 'transfer' && data.redirect_url) {
                            // Redirigir a página con datos bancarios
                            window.location.href = data.redirect_url;
                        } else {
                            throw new Error(data.message || 'Error al procesar el pago');
                        }
                    } else {
                        throw new Error(data.message || 'Error al procesar el pago');
                    }

                } catch (error) {
                    console.error('Error:', error);
                    alert('Hubo un error al procesar tu pedido. Por favor, inténtalo de nuevo.');

                    // Re-enable button
                    checkoutBtn.disabled = false;
                    const currentMethod = document.querySelector('input[name="payment_method"]:checked').value;
                    if (currentMethod === 'transfer') {
                        checkoutBtn.innerHTML = '<i class="bi bi-bank2"></i> CONFIRMAR Y VER DATOS BANCARIOS';
                    } else {
                        checkoutBtn.innerHTML = '<i class="bi bi-lock-fill"></i> PROCEDER AL PAGO';
                    }
                }
            });
        });
    </script>
@endsection