@extends('layouts.app')

@section('title', 'Pedido Confirmado')

@section('content')
    <div class="vitta-container" style="padding: 80px 24px;">
        <div style="max-width: 800px; margin: 0 auto; text-align: center;">

            <!-- Success/Error Messages -->
            @if(session('success'))
            <div style="background: rgba(34, 197, 94, 0.1); border: 1px solid #22c55e; border-radius: 6px; padding: 16px; margin-bottom: 24px; color: #22c55e; text-align: left;">
                <i class="bi bi-check-circle-fill" style="margin-right: 8px;"></i>
                {{ session('success') }}
            </div>
            @endif

            @if(session('error'))
            <div style="background: rgba(239, 68, 68, 0.1); border: 1px solid #ef4444; border-radius: 6px; padding: 16px; margin-bottom: 24px; color: #ef4444; text-align: left;">
                <i class="bi bi-exclamation-triangle-fill" style="margin-right: 8px;"></i>
                {{ session('error') }}
            </div>
            @endif

            @if(session('info'))
            <div style="background: rgba(59, 130, 246, 0.1); border: 1px solid #3b82f6; border-radius: 6px; padding: 16px; margin-bottom: 24px; color: #3b82f6; text-align: left;">
                <i class="bi bi-info-circle-fill" style="margin-right: 8px;"></i>
                {{ session('info') }}
            </div>
            @endif

            <!-- Success Icon -->
            <div
                style="width: 120px; height: 120px; border-radius: 50%; background: rgba(34, 197, 94, 0.1); border: 3px solid #22c55e; margin: 0 auto 32px; display: flex; align-items: center; justify-content: center;">
                <i class="bi bi-check-circle-fill" style="font-size: 64px; color: #22c55e;"></i>
            </div>

            <h1 style="font-size: 42px; color: var(--vitta-gold); margin-bottom: 16px;">
                ¡Pedido Confirmado!
            </h1>

            <p style="font-size: 18px; color: var(--vitta-pearl); opacity: 0.8; margin-bottom: 32px;">
                @if($order->payment_method === 'transfer')
                    Tu pedido fue registrado. Realizá la transferencia para confirmarlo.
                @else
                    Tu pago ha sido procesado exitosamente
                @endif
            </p>

            <!-- Order Number -->
            <div
                style="background: var(--vitta-black-soft); border: 2px solid var(--vitta-gold); border-radius: 8px; padding: 24px; margin-bottom: 32px;">
                <p style="color: var(--vitta-pearl); opacity: 0.7; font-size: 14px; margin-bottom: 8px;">
                    Número de Pedido
                </p>
                <p style="font-size: 28px; color: var(--vitta-gold); font-weight: 600; letter-spacing: 2px;">
                    {{ $order->order_number }}
                </p>
            </div>

            <!-- Order Details -->
            <div
                style="background: var(--vitta-black-soft); border: 1px solid var(--vitta-gray); border-radius: 8px; padding: 32px; text-align: left; margin-bottom: 32px;">
                <h3 style="font-size: 20px; color: var(--vitta-pearl); margin-bottom: 24px; text-align: center;">
                    Detalles del Pedido
                </h3>

                <div style="display: grid; gap: 16px; margin-bottom: 24px;">
                    @foreach($order->items as $item)
                        <div
                            style="display: flex; justify-content: space-between; align-items: center; padding-bottom: 16px; border-bottom: 1px solid var(--vitta-gray);">
                            <div>
                                <p style="color: var(--vitta-pearl); font-size: 15px; margin-bottom: 4px;">
                                    {{ $item->product_name }}
                                    @if($item->variant_name)
                                        <span style="opacity: 0.7; font-size: 13px;">- {{ $item->variant_name }}</span>
                                    @endif
                                </p>
                                <p style="color: var(--vitta-pearl); opacity: 0.6; font-size: 13px;">
                                    Cantidad: {{ $item->quantity }}
                                </p>
                            </div>
                            <p style="color: var(--vitta-gold); font-weight: 600;">
                                ${{ number_format($item->subtotal, 2) }}
                            </p>
                        </div>
                    @endforeach
                </div>

                <div style="display: flex; flex-direction: column; gap: 12px;">
                    <div style="display: flex; justify-content: space-between;">
                        <span style="color: var(--vitta-pearl); opacity: 0.7;">Subtotal:</span>
                        <span style="color: var(--vitta-pearl);">${{ number_format($order->subtotal, 0, ',', '.') }}</span>
                    </div>
                    <div style="display: flex; justify-content: space-between;">
                        <span style="color: var(--vitta-pearl); opacity: 0.7;">Envío:</span>
                        <span style="color: var(--vitta-pearl);">${{ number_format($order->shipping, 0, ',', '.') }}</span>
                    </div>
                    @if($order->discount > 0)
                        <div style="display: flex; justify-content: space-between;">
                            <span style="color: #10b981; font-weight: 600;">Descuento transferencia (5%):</span>
                            <span style="color: #10b981; font-weight: 600;">-${{ number_format($order->discount, 0, ',', '.') }}</span>
                        </div>
                    @endif
                    <div class="golden-line" style="margin: 8px 0;"></div>
                    <div style="display: flex; justify-content: space-between; font-size: 20px;">
                        <span style="color: var(--vitta-gold); font-weight: 600;">Total:</span>
                        <span style="color: var(--vitta-gold); font-weight: 600;">${{ number_format($order->total, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>

            <!-- Shipping Address -->
            <div
                style="background: var(--vitta-black-soft); border: 1px solid var(--vitta-gray); border-radius: 8px; padding: 24px; text-align: left; margin-bottom: 32px;">
                <h4 style="color: var(--vitta-gold); font-size: 16px; margin-bottom: 12px;">
                    <i class="bi bi-geo-alt-fill"></i> Dirección de Envío
                </h4>
                <p style="color: var(--vitta-pearl); margin-bottom: 8px;">
                    <strong>{{ $order->shipping_address['recipient_name'] ?? 'N/A' }}</strong>
                </p>
                <p style="color: var(--vitta-pearl); opacity: 0.8; font-size: 14px;">
                    {{ $order->shipping_address['full_address'] ?? 'N/A' }}
                </p>
            </div>

            @if($order->payment_method === 'transfer')
            <!-- Bank Transfer Data -->
            <div style="background: rgba(16, 185, 129, 0.05); border: 2px solid #10b981; border-radius: 8px; padding: 28px; margin-bottom: 32px; text-align: left;">
                <h4 style="color: #10b981; font-size: 18px; margin-bottom: 20px; display: flex; align-items: center; gap: 10px;">
                    <i class="bi bi-bank2"></i> Datos para la Transferencia
                </h4>
                <div style="display: flex; flex-direction: column; gap: 14px;">
                    @php
                        $bankName     = \App\Models\Setting::get('bank_name', '');
                        $bankHolder   = \App\Models\Setting::get('bank_holder', '');
                        $bankCbu      = \App\Models\Setting::get('bank_cbu', '');
                        $bankAlias    = \App\Models\Setting::get('bank_alias', '');
                        $bankCuit     = \App\Models\Setting::get('bank_cuit', '');
                        $bankAccType  = \App\Models\Setting::get('bank_account_type', '');
                    @endphp
                    <div style="display: flex; justify-content: space-between; border-bottom: 1px solid rgba(16,185,129,0.2); padding-bottom: 10px;">
                        <span style="color: var(--vitta-pearl); opacity: 0.7; font-size: 14px;">Banco:</span>
                        <span style="color: var(--vitta-pearl); font-weight: 600;">{{ $bankName }}</span>
                    </div>
                    <div style="display: flex; justify-content: space-between; border-bottom: 1px solid rgba(16,185,129,0.2); padding-bottom: 10px;">
                        <span style="color: var(--vitta-pearl); opacity: 0.7; font-size: 14px;">Tipo de cuenta:</span>
                        <span style="color: var(--vitta-pearl); font-weight: 600;">{{ $bankAccType }}</span>
                    </div>
                    <div style="display: flex; justify-content: space-between; border-bottom: 1px solid rgba(16,185,129,0.2); padding-bottom: 10px;">
                        <span style="color: var(--vitta-pearl); opacity: 0.7; font-size: 14px;">Titular:</span>
                        <span style="color: var(--vitta-pearl); font-weight: 600;">{{ $bankHolder }}</span>
                    </div>
                    <div style="display: flex; justify-content: space-between; border-bottom: 1px solid rgba(16,185,129,0.2); padding-bottom: 10px;">
                        <span style="color: var(--vitta-pearl); opacity: 0.7; font-size: 14px;">CUIT/CUIL:</span>
                        <span style="color: var(--vitta-pearl); font-weight: 600;">{{ $bankCuit }}</span>
                    </div>
                    <div style="display: flex; justify-content: space-between; border-bottom: 1px solid rgba(16,185,129,0.2); padding-bottom: 10px;">
                        <span style="color: var(--vitta-pearl); opacity: 0.7; font-size: 14px;">CBU:</span>
                        <span style="color: #10b981; font-weight: 700; font-family: monospace; letter-spacing: 1px;">{{ $bankCbu }}</span>
                    </div>
                    <div style="display: flex; justify-content: space-between;">
                        <span style="color: var(--vitta-pearl); opacity: 0.7; font-size: 14px;">Alias:</span>
                        <span style="color: #10b981; font-weight: 700; font-size: 16px;">{{ $bankAlias }}</span>
                    </div>
                </div>
                <div style="margin-top: 20px; padding: 12px; background: rgba(16,185,129,0.1); border-radius: 4px;">
                    <p style="color: var(--vitta-pearl); font-size: 13px; margin: 0;">
                        <i class="bi bi-info-circle" style="color: #10b981; margin-right: 6px;"></i>
                        Indicá el número de pedido <strong style="color: #10b981;">{{ $order->order_number }}</strong> en el concepto de la transferencia. Monto a transferir: <strong style="color: #10b981;">${{ number_format($order->total, 0, ',', '.') }}</strong>
                    </p>
                </div>
            </div>

            <!-- Upload Payment Proof -->
            @if(!$order->payment_confirmed_at)
            <div style="background: rgba(59, 130, 246, 0.05); border: 2px solid #3b82f6; border-radius: 8px; padding: 28px; margin-bottom: 32px; text-align: left;">
                <h4 style="color: #3b82f6; font-size: 18px; margin-bottom: 16px; display: flex; align-items: center; gap: 10px;">
                    <i class="bi bi-cloud-upload-fill"></i> 
                    @if($order->payment_proof)
                        ¡Comprobante Recibido!
                    @else
                        Subí tu Comprobante de Transferencia
                    @endif
                </h4>

                @if($order->payment_proof)
                    <!-- Proof already uploaded -->
                    <div style="background: rgba(34, 197, 94, 0.1); border: 1px solid #22c55e; border-radius: 6px; padding: 16px; margin-bottom: 16px;">
                        <div style="display: flex; align-items: center; gap: 12px;">
                            <i class="bi bi-check-circle-fill" style="color: #22c55e; font-size: 24px;"></i>
                            <div style="flex: 1;">
                                <p style="color: #22c55e; font-weight: 600; margin-bottom: 4px;">
                                    Comprobante recibido el {{ $order->payment_proof_uploaded_at->format('d/m/Y H:i') }}
                                </p>
                                <p style="color: var(--vitta-pearl); opacity: 0.8; font-size: 13px; margin: 0;">
                                    Estamos verificando tu pago. Te notificaremos cuando esté confirmado.
                                </p>
                            </div>
                        </div>
                    </div>

                    <div style="display: flex; gap: 12px; align-items: center;">
                        <a href="{{ route('payment.proof.view', $order) }}" target="_blank"
                            style="padding: 10px 20px; background: rgba(59, 130, 246, 0.1); border: 1px solid #3b82f6; color: #3b82f6; text-decoration: none; font-weight: 600; border-radius: 4px; display: inline-flex; align-items: center; gap: 8px; font-size: 14px;">
                            <i class="bi bi-eye-fill"></i> Ver Comprobante
                        </a>
                        <p style="color: var(--vitta-pearl); opacity: 0.6; font-size: 13px; margin: 0;">
                            ¿Subiste el archivo equivocado? Podés reemplazarlo:
                        </p>
                    </div>
                @else
                    <!-- No proof uploaded yet -->
                    <p style="color: var(--vitta-pearl); opacity: 0.9; font-size: 14px; margin-bottom: 20px;">
                        <strong>Acelera la confirmación de tu pedido</strong> subiendo el comprobante de tu transferencia. 
                        Aceptamos imágenes (JPG, PNG) o PDF de hasta 5MB.
                    </p>
                @endif

                <!-- Upload Form -->
                @if(!$order->payment_confirmed_at)
                <form action="{{ route('payment.proof.upload', $order) }}" method="POST" enctype="multipart/form-data" id="proofUploadForm">
                    @csrf
                    <div style="display: flex; flex-direction: column; gap: 16px;">
                        <div>
                            <input 
                                type="file" 
                                name="payment_proof" 
                                id="payment_proof"
                                accept="image/jpeg,image/png,application/pdf"
                                required
                                onchange="updateFileName()"
                                style="display: none;"
                            >
                            <label for="payment_proof" 
                                style="display: inline-flex; align-items: center; gap: 10px; padding: 14px 28px; background: var(--vitta-black-soft); border: 2px dashed #3b82f6; color: #3b82f6; border-radius: 6px; cursor: pointer; font-weight: 600; transition: all 0.3s;"
                                onmouseover="this.style.background='rgba(59, 130, 246, 0.1)'"
                                onmouseout="this.style.background='var(--vitta-black-soft)'">
                                <i class="bi bi-paperclip" style="font-size: 20px;"></i>
                                <span id="fileLabel">Seleccionar Archivo</span>
                            </label>
                            <p style="color: var(--vitta-pearl); opacity: 0.6; font-size: 12px; margin-top: 8px; margin-bottom: 0;">
                                Formatos: JPG, PNG, PDF • Tamaño máximo: 5MB
                            </p>
                            @error('payment_proof')
                            <p style="color: #ef4444; font-size: 13px; margin-top: 8px;">{{ $message }}</p>
                            @enderror
                        </div>

                        <button type="submit" 
                            style="padding: 14px 32px; background: linear-gradient(135deg, #3b82f6, #2563eb); color: white; border: none; border-radius: 6px; font-weight: 600; cursor: pointer; display: inline-flex; align-items: center; gap: 8px; justify-content: center; transition: all 0.3s;"
                            onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 6px 20px rgba(59, 130, 246, 0.4)'"
                            onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none'">
                            <i class="bi bi-upload"></i>
                            {{ $order->payment_proof ? 'Reemplazar Comprobante' : 'Subir Comprobante' }}
                        </button>
                    </div>
                </form>
                @endif
            </div>
            @else
            <!-- Payment Already Confirmed -->
            <div style="background: rgba(34, 197, 94, 0.1); border: 2px solid #22c55e; border-radius: 8px; padding: 24px; margin-bottom: 32px; text-align: left;">
                <div style="display: flex; align-items: center; gap: 16px;">
                    <i class="bi bi-check-circle-fill" style="color: #22c55e; font-size: 48px;"></i>
                    <div>
                        <h4 style="color: #22c55e; font-size: 18px; margin-bottom: 8px;">
                            ¡Pago Confirmado!
                        </h4>
                        <p style="color: var(--vitta-pearl); opacity: 0.8; font-size: 14px; margin: 0;">
                            Tu pago fue confirmado el {{ $order->payment_confirmed_at->format('d/m/Y H:i') }}. 
                            Estamos preparando tu pedido con el máximo cuidado.
                        </p>
                    </div>
                </div>
            </div>
            @endif
            @endif

            <!-- What's Next -->
            <div
                style="background: rgba(212, 175, 55, 0.1); border: 1px solid var(--vitta-gold); border-radius: 8px; padding: 24px; margin-bottom: 32px;">
                <h4 style="color: var(--vitta-gold); font-size: 18px; margin-bottom: 16px;">
                    ¿Qué sigue ahora?
                </h4>
                <div style="display: flex; flex-direction: column; gap: 12px; text-align: left;">
                    <div style="display: flex; align-items: start; gap: 12px;">
                        <i class="bi bi-1-circle-fill"
                            style="color: var(--vitta-gold); font-size: 24px; flex-shrink: 0;"></i>
                        <p style="color: var(--vitta-pearl); font-size: 14px;">
                            @if($order->payment_method === 'transfer')
                                Realizá la transferencia a los datos bancarios indicados arriba
                            @else
                                Recibirás un correo de confirmación con los detalles de tu pedido
                            @endif
                        </p>
                    </div>
                    <div style="display: flex; align-items: start; gap: 12px;">
                        <i class="bi bi-2-circle-fill"
                            style="color: var(--vitta-gold); font-size: 24px; flex-shrink: 0;"></i>
                        <p style="color: var(--vitta-pearl); font-size: 14px;">
                            @if($order->payment_method === 'transfer')
                                @if($order->payment_proof && !$order->payment_confirmed_at)
                                    Estamos verificando tu comprobante. Te notificaremos cuando esté confirmado
                                @elseif($order->payment_confirmed_at)
                                    Estamos preparando tu pedido con el máximo cuidado
                                @else
                                    Subí el comprobante de pago para acelerar la confirmación de tu pedido
                                @endif
                            @else
                                Prepararemos tu pedido con el máximo cuidado
                            @endif
                        </p>
                    </div>
                    <div style="display: flex; align-items: start; gap: 12px;">
                        <i class="bi bi-3-circle-fill"
                            style="color: var(--vitta-gold); font-size: 24px; flex-shrink: 0;"></i>
                        <p style="color: var(--vitta-pearl); font-size: 14px;">
                            Te notificaremos cuando tu pedido esté en camino
                        </p>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div style="display: flex; gap: 16px; justify-content: center; flex-wrap: wrap;">
                <a href="{{ route('home') }}" class="btn-gold" style="text-decoration: none;">
                    <i class="bi bi-house-fill"></i> VOLVER AL INICIO
                </a>
                <a href="{{ route('products.index') }}"
                    style="padding: 14px 32px; border: 2px solid var(--vitta-gold); color: var(--vitta-gold); text-decoration: none; font-weight: 600; letter-spacing: 0.5px; text-transform: uppercase; border-radius: 4px; display: inline-flex; align-items: center; gap: 8px;">
                    <i class="bi bi-bag-fill"></i> SEGUIR COMPRANDO
                </a>
            </div>

            <p style="color: var(--vitta-pearl); opacity: 0.6; font-size: 13px; margin-top: 32px;">
                Si tenés alguna pregunta sobre tu pedido, contactanos a
                <a href="mailto:contacto@vittaperfumes.com" style="color: var(--vitta-gold);">contacto@vittaperfumes.com</a>
            </p>
        </div>
    </div>

    <script>
        function updateFileName() {
            const input = document.getElementById('payment_proof');
            const label = document.getElementById('fileLabel');
            if (input.files.length > 0) {
                const fileName = input.files[0].name;
                const fileSize = (input.files[0].size / 1024 / 1024).toFixed(2);
                label.innerHTML = `<i class="bi bi-file-earmark-check"></i> ${fileName} (${fileSize} MB)`;
            } else {
                label.textContent = 'Seleccionar Archivo';
            }
        }
    </script>
@endsection