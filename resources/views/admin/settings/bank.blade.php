@extends('layouts.admin')

@section('title', 'Datos Bancarios')

@section('content')
<div style="padding: 32px;">
    
    <!-- Header -->
    <div style="margin-bottom: 32px;">
        <div style="display: flex; align-items: center; gap: 16px; margin-bottom: 8px;">
            <a href="{{ route('admin.settings.index') }}" 
                style="color: var(--vitta-pearl); opacity: 0.6; text-decoration: none; display: flex; align-items: center; gap: 8px; font-size: 14px;">
                <i class="bi bi-arrow-left"></i> Volver a Configuración
            </a>
        </div>
        <h1 style="font-size: 32px; color: var(--vitta-gold); margin-bottom: 8px;">
            <i class="bi bi-bank2" style="margin-right: 12px;"></i>
            Datos Bancarios
        </h1>
        <p style="color: var(--vitta-pearl); opacity: 0.7;">
            Configura los datos bancarios que se mostrarán a los clientes para transferencias
        </p>
    </div>

    @if(session('success'))
    <div style="background: rgba(34, 197, 94, 0.1); border: 1px solid #22c55e; border-radius: 6px; padding: 16px; margin-bottom: 24px; color: #22c55e;">
        <i class="bi bi-check-circle-fill" style="margin-right: 8px;"></i>
        {{ session('success') }}
    </div>
    @endif

    @if($errors->any())
    <div style="background: rgba(239, 68, 68, 0.1); border: 1px solid #ef4444; border-radius: 6px; padding: 16px; margin-bottom: 24px; color: #ef4444;">
        <i class="bi bi-exclamation-triangle-fill" style="margin-right: 8px;"></i>
        <strong>Por favor corrige los siguientes errores:</strong>
        <ul style="margin: 8px 0 0 24px;">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form action="{{ route('admin.settings.bank.update') }}" method="POST">
        @csrf
        @method('PUT')

        <!-- Preview Card -->
        <div style="background: rgba(16, 185, 129, 0.05); border: 2px solid #10b981; border-radius: 8px; padding: 24px; margin-bottom: 32px;">
            <h3 style="color: #10b981; font-size: 18px; margin-bottom: 16px; display: flex; align-items: center; gap: 10px;">
                <i class="bi bi-eye-fill"></i> Vista Previa
            </h3>
            <p style="color: var(--vitta-pearl); opacity: 0.7; font-size: 13px; margin-bottom: 20px;">
                Así es como los clientes verán tus datos bancarios en la página de confirmación:
            </p>
            
            <div style="display: flex; flex-direction: column; gap: 14px;">
                <div style="display: flex; justify-content: space-between; border-bottom: 1px solid rgba(16,185,129,0.2); padding-bottom: 10px;">
                    <span style="color: var(--vitta-pearl); opacity: 0.7; font-size: 14px;">Banco:</span>
                    <span id="preview-bank-name" style="color: var(--vitta-pearl); font-weight: 600;">{{ old('bank_name', $bankSettings['bank_name']) ?: 'Nombre del Banco' }}</span>
                </div>
                <div style="display: flex; justify-content: space-between; border-bottom: 1px solid rgba(16,185,129,0.2); padding-bottom: 10px;">
                    <span style="color: var(--vitta-pearl); opacity: 0.7; font-size: 14px;">Tipo de cuenta:</span>
                    <span id="preview-account-type" style="color: var(--vitta-pearl); font-weight: 600;">{{ old('bank_account_type', $bankSettings['bank_account_type']) ?: 'Tipo de Cuenta' }}</span>
                </div>
                <div style="display: flex; justify-content: space-between; border-bottom: 1px solid rgba(16,185,129,0.2); padding-bottom: 10px;">
                    <span style="color: var(--vitta-pearl); opacity: 0.7; font-size: 14px;">Titular:</span>
                    <span id="preview-holder" style="color: var(--vitta-pearl); font-weight: 600;">{{ old('bank_holder', $bankSettings['bank_holder']) ?: 'Titular de la Cuenta' }}</span>
                </div>
                <div style="display: flex; justify-content: space-between; border-bottom: 1px solid rgba(16,185,129,0.2); padding-bottom: 10px;">
                    <span style="color: var(--vitta-pearl); opacity: 0.7; font-size: 14px;">CUIT/CUIL:</span>
                    <span id="preview-cuit" style="color: var(--vitta-pearl); font-weight: 600;">{{ old('bank_cuit', $bankSettings['bank_cuit']) ?: '30-12345678-9' }}</span>
                </div>
                <div style="display: flex; justify-content: space-between; border-bottom: 1px solid rgba(16,185,129,0.2); padding-bottom: 10px;">
                    <span style="color: var(--vitta-pearl); opacity: 0.7; font-size: 14px;">CBU:</span>
                    <span id="preview-cbu" style="color: #10b981; font-weight: 700; font-family: monospace; letter-spacing: 1px;">{{ old('bank_cbu', $bankSettings['bank_cbu']) ?: '0000000000000000000000' }}</span>
                </div>
                <div style="display: flex; justify-content: space-between;">
                    <span style="color: var(--vitta-pearl); opacity: 0.7; font-size: 14px;">Alias:</span>
                    <span id="preview-alias" style="color: #10b981; font-weight: 700; font-size: 16px;">{{ old('bank_alias', $bankSettings['bank_alias']) ?: 'TU.ALIAS' }}</span>
                </div>
            </div>
        </div>

        <!-- Form Fields -->
        <div style="background: var(--vitta-black-soft); border: 1px solid rgba(212, 175, 55, 0.2); border-radius: 8px; padding: 32px; margin-bottom: 24px;">
            
            <h2 style="font-size: 20px; color: var(--vitta-gold); margin-bottom: 24px; display: flex; align-items: center; gap: 12px;">
                <i class="bi bi-pencil-fill"></i> Editar Información
            </h2>

            <div style="display: grid; gap: 24px;">
                
                <!-- Bank Name -->
                <div>
                    <label for="bank_name" style="display: block; color: var(--vitta-pearl); font-weight: 600; margin-bottom: 8px;">
                        Nombre del Banco *
                        <span style="display: block; font-size: 13px; opacity: 0.6; font-weight: 400; margin-top: 4px;">
                            Ejemplo: Banco Galicia, Banco Nación, BBVA, etc.
                        </span>
                    </label>
                    <input 
                        type="text" 
                        id="bank_name"
                        name="bank_name"
                        value="{{ old('bank_name', $bankSettings['bank_name']) }}"
                        required
                        oninput="document.getElementById('preview-bank-name').textContent = this.value || 'Nombre del Banco'"
                        style="width: 100%; padding: 12px 16px; background: var(--vitta-black); border: 1px solid {{ $errors->has('bank_name') ? '#ef4444' : 'rgba(212, 175, 55, 0.3)' }}; border-radius: 6px; color: var(--vitta-pearl); font-size: 15px;"
                    >
                    @error('bank_name')
                    <span style="color: #ef4444; font-size: 13px; margin-top: 4px; display: block;">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Account Type -->
                <div>
                    <label for="bank_account_type" style="display: block; color: var(--vitta-pearl); font-weight: 600; margin-bottom: 8px;">
                        Tipo de Cuenta *
                        <span style="display: block; font-size: 13px; opacity: 0.6; font-weight: 400; margin-top: 4px;">
                            Ejemplo: Cuenta Corriente, Caja de Ahorro
                        </span>
                    </label>
                    <input 
                        type="text" 
                        id="bank_account_type"
                        name="bank_account_type"
                        value="{{ old('bank_account_type', $bankSettings['bank_account_type']) }}"
                        required
                        oninput="document.getElementById('preview-account-type').textContent = this.value || 'Tipo de Cuenta'"
                        style="width: 100%; padding: 12px 16px; background: var(--vitta-black); border: 1px solid {{ $errors->has('bank_account_type') ? '#ef4444' : 'rgba(212, 175, 55, 0.3)' }}; border-radius: 6px; color: var(--vitta-pearl); font-size: 15px;"
                    >
                    @error('bank_account_type')
                    <span style="color: #ef4444; font-size: 13px; margin-top: 4px; display: block;">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Holder -->
                <div>
                    <label for="bank_holder" style="display: block; color: var(--vitta-pearl); font-weight: 600; margin-bottom: 8px;">
                        Titular de la Cuenta *
                        <span style="display: block; font-size: 13px; opacity: 0.6; font-weight: 400; margin-top: 4px;">
                            Nombre completo del titular o razón social
                        </span>
                    </label>
                    <input 
                        type="text" 
                        id="bank_holder"
                        name="bank_holder"
                        value="{{ old('bank_holder', $bankSettings['bank_holder']) }}"
                        required
                        oninput="document.getElementById('preview-holder').textContent = this.value || 'Titular de la Cuenta'"
                        style="width: 100%; padding: 12px 16px; background: var(--vitta-black); border: 1px solid {{ $errors->has('bank_holder') ? '#ef4444' : 'rgba(212, 175, 55, 0.3)' }}; border-radius: 6px; color: var(--vitta-pearl); font-size: 15px;"
                    >
                    @error('bank_holder')
                    <span style="color: #ef4444; font-size: 13px; margin-top: 4px; display: block;">{{ $message }}</span>
                    @enderror
                </div>

                <!-- CUIT -->
                <div>
                    <label for="bank_cuit" style="display: block; color: var(--vitta-pearl); font-weight: 600; margin-bottom: 8px;">
                        CUIT/CUIL *
                        <span style="display: block; font-size: 13px; opacity: 0.6; font-weight: 400; margin-top: 4px;">
                            Formato: 30-12345678-9 (con o sin guiones)
                        </span>
                    </label>
                    <input 
                        type="text" 
                        id="bank_cuit"
                        name="bank_cuit"
                        value="{{ old('bank_cuit', $bankSettings['bank_cuit']) }}"
                        required
                        maxlength="20"
                        oninput="document.getElementById('preview-cuit').textContent = this.value || '30-12345678-9'"
                        style="width: 100%; max-width: 400px; padding: 12px 16px; background: var(--vitta-black); border: 1px solid {{ $errors->has('bank_cuit') ? '#ef4444' : 'rgba(212, 175, 55, 0.3)' }}; border-radius: 6px; color: var(--vitta-pearl); font-size: 15px;"
                    >
                    @error('bank_cuit')
                    <span style="color: #ef4444; font-size: 13px; margin-top: 4px; display: block;">{{ $message }}</span>
                    @enderror
                </div>

                <!-- CBU -->
                <div>
                    <label for="bank_cbu" style="display: block; color: var(--vitta-pearl); font-weight: 600; margin-bottom: 8px;">
                        CBU *
                        <span style="display: block; font-size: 13px; opacity: 0.6; font-weight: 400; margin-top: 4px;">
                            22 dígitos numéricos (sin espacios ni guiones)
                        </span>
                    </label>
                    <input 
                        type="text" 
                        id="bank_cbu"
                        name="bank_cbu"
                        value="{{ old('bank_cbu', $bankSettings['bank_cbu']) }}"
                        required
                        maxlength="22"
                        pattern="[0-9]{22}"
                        oninput="this.value = this.value.replace(/[^0-9]/g, ''); document.getElementById('preview-cbu').textContent = this.value || '0000000000000000000000'"
                        style="width: 100%; max-width: 600px; padding: 12px 16px; background: var(--vitta-black); border: 1px solid {{ $errors->has('bank_cbu') ? '#ef4444' : 'rgba(212, 175, 55, 0.3)' }}; border-radius: 6px; color: var(--vitta-pearl); font-size: 15px; font-family: monospace; letter-spacing: 2px;"
                    >
                    <span style="color: var(--vitta-pearl); opacity: 0.6; font-size: 12px; margin-top: 4px; display: block;">
                        Longitud actual: <span id="cbu-length">{{ strlen(old('bank_cbu', $bankSettings['bank_cbu'])) }}</span>/22
                    </span>
                    @error('bank_cbu')
                    <span style="color: #ef4444; font-size: 13px; margin-top: 4px; display: block;">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Alias -->
                <div>
                    <label for="bank_alias" style="display: block; color: var(--vitta-pearl); font-weight: 600; margin-bottom: 8px;">
                        Alias *
                        <span style="display: block; font-size: 13px; opacity: 0.6; font-weight: 400; margin-top: 4px;">
                            CVU o alias de tu cuenta (ejemplo: VITTA.PERFUMES)
                        </span>
                    </label>
                    <input 
                        type="text" 
                        id="bank_alias"
                        name="bank_alias"
                        value="{{ old('bank_alias', $bankSettings['bank_alias']) }}"
                        required
                        maxlength="50"
                        oninput="document.getElementById('preview-alias').textContent = this.value || 'TU.ALIAS'"
                        style="width: 100%; max-width: 500px; padding: 12px 16px; background: var(--vitta-black); border: 1px solid {{ $errors->has('bank_alias') ? '#ef4444' : 'rgba(212, 175, 55, 0.3)' }}; border-radius: 6px; color: var(--vitta-pearl); font-size: 15px;"
                    >
                    @error('bank_alias')
                    <span style="color: #ef4444; font-size: 13px; margin-top: 4px; display: block;">{{ $message }}</span>
                    @enderror
                </div>

            </div>

        </div>

        <!-- Save Button -->
        <div style="display: flex; justify-content: flex-end; gap: 12px;">
            <a href="{{ route('admin.settings.index') }}" 
                style="padding: 14px 32px; background: transparent; border: 2px solid rgba(212, 175, 55, 0.3); color: var(--vitta-pearl); text-decoration: none; border-radius: 6px; font-weight: 600; display: inline-flex; align-items: center; gap: 8px;">
                <i class="bi bi-x-circle"></i> Cancelar
            </a>
            <button type="submit" class="btn-gold" style="padding: 14px 32px;">
                <i class="bi bi-save" style="margin-right: 8px;"></i>
                Guardar Datos Bancarios
            </button>
        </div>

    </form>

</div>

<script>
    // Update CBU length counter
    document.getElementById('bank_cbu').addEventListener('input', function(e) {
        document.getElementById('cbu-length').textContent = e.target.value.length;
    });
</script>
@endsection
