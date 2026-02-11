@extends('layouts.app')

@section('title', 'Checkout - Información de Envío')

@section('content')
<div class="vitta-container" style="padding: 80px 24px;">
    
    <!-- Progress Steps -->
    <div style="max-width: 800px; margin: 0 auto 64px;">
        <div style="display: flex; justify-content: space-between; position: relative;">
            <!-- Line -->
            <div style="position: absolute; top: 16px; left: 0; right: 0; height: 2px; background: var(--vitta-gray); z-index: 0;"></div>
            
            <!-- Step 1 - Active -->
            <div style="position: relative; z-index: 1; text-align: center; flex: 1;">
                <div style="width: 32px; height: 32px; border-radius: 50%; background: var(--vitta-gold); margin: 0 auto 12px; display: flex; align-items: center; justify-content: center;">
                    <i class="bi bi-check2" style="color: var(--vitta-black); font-weight: bold;"></i>
                </div>
                <span style="font-size: 12px; color: var(--vitta-gold); font-weight: 600;">ENVÍO</span>
            </div>
            
            <!-- Step 2 -->
            <div style="position: relative; z-index: 1; text-align: center; flex: 1;">
                <div style="width: 32px; height: 32px; border-radius: 50%; background: var(--vitta-gray); margin: 0 auto 12px; display: flex; align-items: center; justify-content: center; color: var(--vitta-pearl);">
                    2
                </div>
                <span style="font-size: 12px; color: var(--vitta-pearl); opacity: 0.5;">PAGO</span>
            </div>
            
            <!-- Step 3 -->
            <div style="position: relative; z-index: 1; text-align: center; flex: 1;">
                <div style="width: 32px; height: 32px; border-radius: 50%; background: var(--vitta-gray); margin: 0 auto 12px; display: flex; align-items: center; justify-content: center; color: var(--vitta-pearl);">
                    3
                </div>
                <span style="font-size: 12px; color: var(--vitta-pearl); opacity: 0.5;">CONFIRMACIÓN</span>
            </div>
        </div>
    </div>

    <div style="max-width: 1200px; margin: 0 auto; display: grid; grid-template-columns: 1fr 400px; gap: 48px;">
        
        <!-- Left Column - Address Form -->
        <div>
            <h2 style="font-size: 32px; color: var(--vitta-gold); margin-bottom: 32px;">
                Información de Envío
            </h2>

            @if($addresses->isNotEmpty())
                <div style="margin-bottom: 32px;">
                    <h3 style="font-size: 18px; color: var(--vitta-pearl); margin-bottom: 16px;">
                        Direcciones Guardadas
                    </h3>
                    
                    @foreach($addresses as $address)
                        <div style="background: var(--vitta-black-soft); border: 2px solid {{ $address->is_default ? 'var(--vitta-gold)' : 'var(--vitta-gray)' }}; border-radius: 8px; padding: 20px; margin-bottom: 16px;">
                            <div style="display: flex; justify-content: space-between; align-items: start;">
                                <div style="flex: 1;">
                                    <div style="display: flex; gap: 12px; align-items: center; margin-bottom: 12px;">
                                        <h4 style="font-size: 16px; color: var(--vitta-pearl);">{{ $address->label }}</h4>
                                        @if($address->is_default)
                                            <span style="background: var(--vitta-gold); color: var(--vitta-black); padding: 2px 8px; border-radius: 4px; font-size: 11px; font-weight: 600;">PREDETERMINADA</span>
                                        @endif
                                    </div>
                                    <p style="color: var(--vitta-pearl); opacity: 0.8; font-size: 14px; margin-bottom: 8px;">
                                        <strong>{{ $address->recipient_name }}</strong> - {{ $address->recipient_phone }}
                                    </p>
                                    <p style="color: var(--vitta-pearl); opacity: 0.7; font-size: 14px;">
                                        {{ $address->full_address }}
                                    </p>
                                </div>
                                <a href="{{ route('checkout.payment', $address->id) }}" class="btn-gold" style="text-decoration: none; padding: 10px 20px; font-size: 14px; white-space: nowrap;">
                                    USAR ESTA DIRECCIÓN
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="golden-line" style="margin: 32px 0;"></div>

                <h3 style="font-size: 18px; color: var(--vitta-pearl); margin-bottom: 24px;">
                    O Agregar Nueva Dirección
                </h3>
            @endif

            <form method="POST" action="{{ route('checkout.address.store') }}" style="display: flex; flex-direction: column; gap: 20px;">
                @csrf

                @guest
                    <div>
                        <label style="display: block; color: var(--vitta-pearl); font-size: 14px; margin-bottom: 8px;">
                            Email * <span style="opacity: 0.7; font-size: 12px;">(para enviar la confirmación de tu pedido)</span>
                        </label>
                        <input type="email" name="guest_email" required
                            style="width: 100%; padding: 12px; background: var(--vitta-black-soft); border: 1px solid var(--vitta-gray); border-radius: 4px; color: var(--vitta-pearl); font-size: 14px;"
                            value="{{ old('guest_email', session('guest_email')) }}">
                        @error('guest_email')
                            <span style="color: #ef4444; font-size: 12px;">{{ $message }}</span>
                        @enderror
                    </div>
                @endguest

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                    <div>
                        <label style="display: block; color: var(--vitta-pearl); font-size: 14px; margin-bottom: 8px;">
                            Nombre Completo *
                        </label>
                        <input type="text" name="recipient_name" required
                            style="width: 100%; padding: 12px; background: var(--vitta-black-soft); border: 1px solid var(--vitta-gray); border-radius: 4px; color: var(--vitta-pearl); font-size: 14px;"
                            value="{{ old('recipient_name') }}">
                        @error('recipient_name')
                            <span style="color: #ef4444; font-size: 12px;">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label style="display: block; color: var(--vitta-pearl); font-size: 14px; margin-bottom: 8px;">
                            Teléfono *
                        </label>
                        <input type="tel" name="recipient_phone" required
                            style="width: 100%; padding: 12px; background: var(--vitta-black-soft); border: 1px solid var(--vitta-gray); border-radius: 4px; color: var(--vitta-pearl); font-size: 14px;"
                            value="{{ old('recipient_phone') }}">
                        @error('recipient_phone')
                            <span style="color: #ef4444; font-size: 12px;">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: 3fr 1fr; gap: 20px;">
                    <div>
                        <label style="display: block; color: var(--vitta-pearl); font-size: 14px; margin-bottom: 8px;">
                            Calle *
                        </label>
                        <input type="text" name="street_address" required
                            style="width: 100%; padding: 12px; background: var(--vitta-black-soft); border: 1px solid var(--vitta-gray); border-radius: 4px; color: var(--vitta-pearl); font-size: 14px;"
                            value="{{ old('street_address') }}">
                        @error('street_address')
                            <span style="color: #ef4444; font-size: 12px;">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label style="display: block; color: var(--vitta-pearl); font-size: 14px; margin-bottom: 8px;">
                            Número *
                        </label>
                        <input type="text" name="street_number" required
                            style="width: 100%; padding: 12px; background: var(--vitta-black-soft); border: 1px solid var(--vitta-gray); border-radius: 4px; color: var(--vitta-pearl); font-size: 14px;"
                            value="{{ old('street_number') }}">
                        @error('street_number')
                            <span style="color: #ef4444; font-size: 12px;">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                    <div>
                        <label style="display: block; color: var(--vitta-pearl); font-size: 14px; margin-bottom: 8px;">
                            Piso/Depto
                        </label>
                        <input type="text" name="apartment"
                            style="width: 100%; padding: 12px; background: var(--vitta-black-soft); border: 1px solid var(--vitta-gray); border-radius: 4px; color: var(--vitta-pearl); font-size: 14px;"
                            value="{{ old('apartment') }}">
                    </div>

                    <div>
                        <label style="display: block; color: var(--vitta-pearl); font-size: 14px; margin-bottom: 8px;">
                            Barrio
                        </label>
                        <input type="text" name="neighborhood"
                            style="width: 100%; padding: 12px; background: var(--vitta-black-soft); border: 1px solid var(--vitta-gray); border-radius: 4px; color: var(--vitta-pearl); font-size: 14px;"
                            value="{{ old('neighborhood') }}">
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 20px;">
                    <div>
                        <label style="display: block; color: var(--vitta-pearl); font-size: 14px; margin-bottom: 8px;">
                            Ciudad *
                        </label>
                        <input type="text" name="city" required
                            style="width: 100%; padding: 12px; background: var(--vitta-black-soft); border: 1px solid var(--vitta-gray); border-radius: 4px; color: var(--vitta-pearl); font-size: 14px;"
                            value="{{ old('city') }}">
                        @error('city')
                            <span style="color: #ef4444; font-size: 12px;">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label style="display: block; color: var(--vitta-pearl); font-size: 14px; margin-bottom: 8px;">
                            Provincia *
                        </label>
                        <input type="text" name="state" required
                            style="width: 100%; padding: 12px; background: var(--vitta-black-soft); border: 1px solid var(--vitta-gray); border-radius: 4px; color: var(--vitta-pearl); font-size: 14px;"
                            value="{{ old('state') }}">
                        @error('state')
                            <span style="color: #ef4444; font-size: 12px;">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label style="display: block; color: var(--vitta-pearl); font-size: 14px; margin-bottom: 8px;">
                            Código Postal *
                        </label>
                        <input type="text" name="postal_code" required
                            style="width: 100%; padding: 12px; background: var(--vitta-black-soft); border: 1px solid var(--vitta-gray); border-radius: 4px; color: var(--vitta-pearl); font-size: 14px;"
                            value="{{ old('postal_code') }}">
                        @error('postal_code')
                            <span style="color: #ef4444; font-size: 12px;">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div>
                    <label style="display: block; color: var(--vitta-pearl); font-size: 14px; margin-bottom: 8px;">
                        País *
                    </label>
                    <input type="text" name="country" required value="Argentina"
                        style="width: 100%; padding: 12px; background: var(--vitta-black-soft); border: 1px solid var(--vitta-gray); border-radius: 4px; color: var(--vitta-pearl); font-size: 14px;">
                    @error('country')
                        <span style="color: #ef4444; font-size: 12px;">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <label style="display: block; color: var(--vitta-pearl); font-size: 14px; margin-bottom: 8px;">
                        Información Adicional
                    </label>
                    <textarea name="additional_info" rows="3"
                        style="width: 100%; padding: 12px; background: var(--vitta-black-soft); border: 1px solid var(--vitta-gray); border-radius: 4px; color: var(--vitta-pearl); font-size: 14px; resize: vertical;"
                        placeholder="Referencias del domicilio, puntos de referencia, etc.">{{ old('additional_info') }}</textarea>
                </div>

                @auth
                    <div style="display: flex; align-items: center; gap: 12px;">
                        <input type="checkbox" name="is_default" id="is_default" value="1" {{ old('is_default') ? 'checked' : '' }}
                            style="width: 20px; height: 20px;">
                        <label for="is_default" style="color: var(--vitta-pearl); font-size: 14px;">
                            Guardar como dirección predeterminada
                        </label>
                    </div>
                @endauth

                <button type="submit" class="btn-gold" style="margin-top: 16px;">
                    CONTINUAR AL PAGO
                </button>
            </form>
        </div>

        <!-- Right Column - Order Summary -->
        <div>
            <div style="background: var(--vitta-black-soft); border: 1px solid var(--vitta-gray); border-radius: 8px; padding: 32px; position: sticky; top: 24px;">
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

                @php
                    $subtotal = $cart->subtotal;
                    $tax = $cart->tax;
                    $shipping = 2500;
                    $total = $cart->total + $shipping;
                @endphp

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

                <p style="color: var(--vitta-pearl); opacity: 0.6; font-size: 12px; margin-top: 24px; text-align: center;">
                    <i class="bi bi-shield-check"></i> Compra segura con encriptación SSL
                </p>
            </div>
        </div>
    </div>
</div>
@endsection