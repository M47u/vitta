@extends('layouts.admin')

@section('title', 'Configuración del Sistema')

@section('content')
<div style="padding: 32px;">
    
    <!-- Header -->
    <div style="margin-bottom: 32px;">
        <h1 style="font-size: 32px; color: var(--vitta-gold); margin-bottom: 8px;">
            <i class="bi bi-gear-fill" style="margin-right: 12px;"></i>
            Configuración del Sistema
        </h1>
        <p style="color: var(--vitta-pearl); opacity: 0.7;">
            Gestiona los parámetros generales de tu tienda
        </p>
    </div>

    @if(session('success'))
    <div style="background: rgba(34, 197, 94, 0.1); border: 1px solid #22c55e; border-radius: 6px; padding: 16px; margin-bottom: 24px; color: #22c55e;">
        <i class="bi bi-check-circle-fill" style="margin-right: 8px;"></i>
        {{ session('success') }}
    </div>
    @endif

    <form action="{{ route('admin.settings.update') }}" method="POST">
        @csrf
        @method('PUT')

        @foreach($settings as $group => $groupSettings)
        <div style="background: var(--vitta-black-soft); border: 1px solid rgba(212, 175, 55, 0.2); border-radius: 8px; padding: 32px; margin-bottom: 24px;">
            
            <!-- Group Title -->
            <h2 style="font-size: 20px; color: var(--vitta-gold); margin-bottom: 24px; text-transform: capitalize; display: flex; align-items: center; gap: 12px;">
                @if($group === 'general')
                    <i class="bi bi-info-circle"></i> General
                @elseif($group === 'shop')
                    <i class="bi bi-shop"></i> Tienda
                @elseif($group === 'shipping')
                    <i class="bi bi-truck"></i> Envíos
                @elseif($group === 'payment')
                    <i class="bi bi-credit-card"></i> Pagos
                @else
                    {{ ucfirst($group) }}
                @endif
            </h2>

            <div style="display: grid; gap: 24px;">
                @foreach($groupSettings as $setting)
                <div>
                    <label style="display: block; color: var(--vitta-pearl); font-weight: 600; margin-bottom: 8px;">
                        {{ $setting->label }}
                        @if($setting->description)
                        <span style="display: block; font-size: 13px; opacity: 0.6; font-weight: 400; margin-top: 4px;">
                            {{ $setting->description }}
                        </span>
                        @endif
                    </label>

                    @if($setting->type === 'boolean')
                        <div style="display: flex; align-items: center; gap: 12px;">
                            <input 
                                type="checkbox" 
                                id="{{ $setting->key }}"
                                name="settings[{{ $setting->key }}]"
                                value="true"
                                {{ $setting->value === 'true' || $setting->value === '1' ? 'checked' : '' }}
                                style="width: 20px; height: 20px; cursor: pointer;"
                            >
                            <label for="{{ $setting->key }}" style="cursor: pointer; color: var(--vitta-pearl); opacity: 0.8;">
                                Activado
                            </label>
                        </div>
                    @elseif($setting->type === 'number')
                        <input 
                            type="number" 
                            name="settings[{{ $setting->key }}]"
                            value="{{ $setting->value }}"
                            step="0.01"
                            style="width: 100%; max-width: 300px; padding: 12px 16px; background: var(--vitta-black); border: 1px solid rgba(212, 175, 55, 0.3); border-radius: 6px; color: var(--vitta-pearl); font-size: 15px;"
                        >
                    @else
                        <input 
                            type="text" 
                            name="settings[{{ $setting->key }}]"
                            value="{{ $setting->value }}"
                            style="width: 100%; padding: 12px 16px; background: var(--vitta-black); border: 1px solid rgba(212, 175, 55, 0.3); border-radius: 6px; color: var(--vitta-pearl); font-size: 15px;"
                        >
                    @endif
                </div>
                @endforeach
            </div>

        </div>
        @endforeach

        <!-- Save Button -->
        <div style="display: flex; justify-content: flex-end; gap: 12px;">
            <a href="{{ route('admin.products.index') }}" 
                style="padding: 14px 32px; background: transparent; border: 2px solid rgba(212, 175, 55, 0.3); color: var(--vitta-pearl); text-decoration: none; border-radius: 6px; font-weight: 600;">
                Cancelar
            </a>
            <button type="submit" class="btn-gold" style="padding: 14px 32px;">
                <i class="bi bi-save" style="margin-right: 8px;"></i>
                Guardar Configuración
            </button>
        </div>

    </form>

</div>
@endsection
