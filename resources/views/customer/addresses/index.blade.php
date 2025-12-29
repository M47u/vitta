@extends('layouts.app')

@section('title', 'Mis Direcciones - Vitta Perfumes')

@section('content')
<div style="min-height: 100vh; padding: 40px 0; background: var(--vitta-black);">
    <div class="container">
        <div style="display: grid; grid-template-columns: 280px 1fr; gap: 32px;">
            
            <!-- Sidebar -->
            @include('customer.partials.sidebar')

            <!-- Main Content -->
            <div>
                
                <!-- Header -->
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 32px;">
                    <div>
                        <h1 style="font-size: 28px; font-weight: 700; color: var(--vitta-gold); margin-bottom: 8px;">
                            Mis Direcciones
                        </h1>
                        <p style="color: var(--vitta-pearl); opacity: 0.7;">
                            Gestiona tus direcciones de envío
                        </p>
                    </div>
                    <button onclick="document.getElementById('add-address-form').style.display='block'; window.scrollTo({top: 0, behavior: 'smooth'});"
                            style="padding: 12px 24px; background: var(--vitta-gold); color: var(--vitta-black); border: none; border-radius: 6px; font-weight: 600; cursor: pointer; transition: all 0.3s;"
                            onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 4px 12px rgba(212, 175, 55, 0.3)';"
                            onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none';">
                        <i class="bi bi-plus-lg"></i> Nueva Dirección
                    </button>
                </div>

                <!-- Add Address Form (Hidden by default) -->
                <div id="add-address-form" style="display: none; background: var(--vitta-black-soft); border: 1px solid rgba(212, 175, 55, 0.2); border-radius: 8px; padding: 24px; margin-bottom: 24px;">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                        <h2 style="font-size: 20px; font-weight: 600; color: var(--vitta-gold);">
                            Agregar Nueva Dirección
                        </h2>
                        <button onclick="this.closest('#add-address-form').style.display='none'"
                                style="background: none; border: none; color: var(--vitta-pearl); opacity: 0.6; cursor: pointer; font-size: 20px;"
                                onmouseover="this.style.opacity='1'"
                                onmouseout="this.style.opacity='0.6'">
                            <i class="bi bi-x-lg"></i>
                        </button>
                    </div>
                    
                    <form method="POST" action="{{ route('customer.addresses.store') }}">
                        @csrf
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 16px;">
                            
                            <div>
                                <label style="display: block; color: var(--vitta-pearl); font-size: 14px; margin-bottom: 6px;">
                                    Nombre del Destinatario *
                                </label>
                                <input type="text" name="recipient_name" required
                                       style="width: 100%; padding: 10px 14px; background: var(--vitta-black); border: 1px solid rgba(212, 175, 55, 0.3); border-radius: 6px; color: var(--vitta-pearl);">
                            </div>

                            <div>
                                <label style="display: block; color: var(--vitta-pearl); font-size: 14px; margin-bottom: 6px;">
                                    Teléfono *
                                </label>
                                <input type="tel" name="recipient_phone" required
                                       style="width: 100%; padding: 10px 14px; background: var(--vitta-black); border: 1px solid rgba(212, 175, 55, 0.3); border-radius: 6px; color: var(--vitta-pearl);">
                            </div>

                            <div>
                                <label style="display: block; color: var(--vitta-pearl); font-size: 14px; margin-bottom: 6px;">
                                    Calle *
                                </label>
                                <input type="text" name="street_address" required
                                       style="width: 100%; padding: 10px 14px; background: var(--vitta-black); border: 1px solid rgba(212, 175, 55, 0.3); border-radius: 6px; color: var(--vitta-pearl);">
                            </div>

                            <div>
                                <label style="display: block; color: var(--vitta-pearl); font-size: 14px; margin-bottom: 6px;">
                                    Número *
                                </label>
                                <input type="text" name="street_number" required
                                       style="width: 100%; padding: 10px 14px; background: var(--vitta-black); border: 1px solid rgba(212, 175, 55, 0.3); border-radius: 6px; color: var(--vitta-pearl);">
                            </div>

                            <div style="grid-column: 1 / -1;">
                                <label style="display: block; color: var(--vitta-pearl); font-size: 14px; margin-bottom: 6px;">
                                    Departamento/Piso (Opcional)
                                </label>
                                <input type="text" name="apartment"
                                       style="width: 100%; padding: 10px 14px; background: var(--vitta-black); border: 1px solid rgba(212, 175, 55, 0.3); border-radius: 6px; color: var(--vitta-pearl);">
                            </div>

                            <div>
                                <label style="display: block; color: var(--vitta-pearl); font-size: 14px; margin-bottom: 6px;">
                                    Barrio (Opcional)
                                </label>
                                <input type="text" name="neighborhood"
                                       style="width: 100%; padding: 10px 14px; background: var(--vitta-black); border: 1px solid rgba(212, 175, 55, 0.3); border-radius: 6px; color: var(--vitta-pearl);">
                            </div>

                            <div>
                                <label style="display: block; color: var(--vitta-pearl); font-size: 14px; margin-bottom: 6px;">
                                    Ciudad *
                                </label>
                                <input type="text" name="city" required
                                       style="width: 100%; padding: 10px 14px; background: var(--vitta-black); border: 1px solid rgba(212, 175, 55, 0.3); border-radius: 6px; color: var(--vitta-pearl);">
                            </div>

                            <div>
                                <label style="display: block; color: var(--vitta-pearl); font-size: 14px; margin-bottom: 6px;">
                                    Provincia *
                                </label>
                                <input type="text" name="state" required
                                       style="width: 100%; padding: 10px 14px; background: var(--vitta-black); border: 1px solid rgba(212, 175, 55, 0.3); border-radius: 6px; color: var(--vitta-pearl);">
                            </div>

                            <div>
                                <label style="display: block; color: var(--vitta-pearl); font-size: 14px; margin-bottom: 6px;">
                                    Código Postal *
                                </label>
                                <input type="text" name="postal_code" required
                                       style="width: 100%; padding: 10px 14px; background: var(--vitta-black); border: 1px solid rgba(212, 175, 55, 0.3); border-radius: 6px; color: var(--vitta-pearl);">
                            </div>

                        </div>

                        <div style="margin-bottom: 20px;">
                            <label style="display: flex; align-items: center; gap: 8px; color: var(--vitta-pearl); cursor: pointer;">
                                <input type="checkbox" name="is_default" value="1"
                                       style="width: 18px; height: 18px; cursor: pointer;">
                                <span>Establecer como dirección predeterminada</span>
                            </label>
                        </div>

                        <div style="display: flex; gap: 12px;">
                            <button type="submit"
                                    style="flex: 1; padding: 12px; background: var(--vitta-gold); color: var(--vitta-black); border: none; border-radius: 6px; font-weight: 600; cursor: pointer; transition: all 0.3s;"
                                    onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 4px 12px rgba(212, 175, 55, 0.3)';"
                                    onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none';">
                                <i class="bi bi-check-lg"></i> Guardar Dirección
                            </button>
                            <button type="button" onclick="this.closest('#add-address-form').style.display='none'"
                                    style="padding: 12px 24px; background: var(--vitta-black); color: var(--vitta-pearl); border: 1px solid rgba(212, 175, 55, 0.3); border-radius: 6px; cursor: pointer; transition: all 0.3s;"
                                    onmouseover="this.style.borderColor='var(--vitta-gold)'"
                                    onmouseout="this.style.borderColor='rgba(212, 175, 55, 0.3)'">
                                Cancelar
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Addresses List -->
                @if($addresses->count() > 0)
                    <div style="display: grid; gap: 16px;">
                        @foreach($addresses as $address)
                            <div style="background: var(--vitta-black-soft); border: 1px solid {{ $address->is_default ? 'var(--vitta-gold)' : 'rgba(212, 175, 55, 0.2)' }}; border-radius: 8px; padding: 24px; position: relative; transition: all 0.3s;"
                                 onmouseover="if(!this.querySelector('.default-badge')) this.style.borderColor='var(--vitta-gold)'; this.style.transform='translateY(-2px)'; this.style.boxShadow='0 8px 24px rgba(0,0,0,0.3)';"
                                 onmouseout="if(!this.querySelector('.default-badge')) this.style.borderColor='rgba(212, 175, 55, 0.2)'; this.style.transform='translateY(0)'; this.style.boxShadow='none';">
                                
                                @if($address->is_default)
                                    <span class="default-badge" style="position: absolute; top: 16px; right: 16px; padding: 6px 12px; background: var(--vitta-gold); color: var(--vitta-black); border-radius: 12px; font-size: 12px; font-weight: 600;">
                                        <i class="bi bi-star-fill"></i> Predeterminada
                                    </span>
                                @endif

                                <div style="display: grid; grid-template-columns: 1fr auto; gap: 20px;">
                                    
                                    <!-- Address Info -->
                                    <div>
                                        <h3 style="font-size: 18px; font-weight: 600; color: var(--vitta-gold); margin-bottom: 12px;">
                                            {{ $address->recipient_name }}
                                        </h3>
                                        <div style="color: var(--vitta-pearl); line-height: 1.8;">
                                            <p>{{ $address->street_address }}, {{ $address->street_number }}</p>
                                            @if($address->apartment)
                                                <p>{{ $address->apartment }}</p>
                                            @endif
                                            @if($address->neighborhood)
                                                <p>{{ $address->neighborhood }}</p>
                                            @endif
                                            <p>{{ $address->city }}, {{ $address->state }}</p>
                                            <p>CP: {{ $address->postal_code }}</p>
                                            <p style="margin-top: 8px; opacity: 0.7;">
                                                <i class="bi bi-telephone"></i> {{ $address->recipient_phone }}
                                            </p>
                                        </div>
                                    </div>

                                    <!-- Actions -->
                                    <div style="display: flex; flex-direction: column; gap: 8px; align-items: flex-end;">
                                        
                                        @if(!$address->is_default)
                                            <form method="POST" action="{{ route('customer.addresses.update', $address->id) }}">
                                                @csrf
                                                @method('PUT')
                                                <input type="hidden" name="is_default" value="1">
                                                <button type="submit"
                                                        style="padding: 8px 16px; background: rgba(212, 175, 55, 0.1); color: var(--vitta-gold); border: 1px solid var(--vitta-gold); border-radius: 6px; cursor: pointer; font-size: 13px; transition: all 0.3s;"
                                                        onmouseover="this.style.background='var(--vitta-gold)'; this.style.color='var(--vitta-black)'"
                                                        onmouseout="this.style.background='rgba(212, 175, 55, 0.1)'; this.style.color='var(--vitta-gold)'">
                                                    <i class="bi bi-star"></i> Hacer Predeterminada
                                                </button>
                                            </form>
                                        @endif

                                        <form method="POST" action="{{ route('customer.addresses.destroy', $address->id) }}" 
                                              onsubmit="return confirm('¿Estás seguro de eliminar esta dirección?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    style="padding: 8px 16px; background: rgba(220, 53, 69, 0.1); color: #dc3545; border: 1px solid #dc3545; border-radius: 6px; cursor: pointer; font-size: 13px; transition: all 0.3s;"
                                                    onmouseover="this.style.background='#dc3545'; this.style.color='white'"
                                                    onmouseout="this.style.background='rgba(220, 53, 69, 0.1)'; this.style.color='#dc3545'">
                                                <i class="bi bi-trash"></i> Eliminar
                                            </button>
                                        </form>

                                    </div>

                                </div>

                            </div>
                        @endforeach
                    </div>
                @else
                    <!-- Empty State -->
                    <div style="background: var(--vitta-black-soft); border: 1px solid rgba(212, 175, 55, 0.2); border-radius: 8px; padding: 60px 40px; text-align: center;">
                        <div style="width: 80px; height: 80px; margin: 0 auto 24px; background: rgba(212, 175, 55, 0.1); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                            <i class="bi bi-geo-alt" style="font-size: 36px; color: var(--vitta-gold);"></i>
                        </div>
                        <h3 style="font-size: 20px; font-weight: 600; color: var(--vitta-pearl); margin-bottom: 12px;">
                            No tienes direcciones guardadas
                        </h3>
                        <p style="color: var(--vitta-pearl); opacity: 0.7; margin-bottom: 24px;">
                            Agrega una dirección de envío para facilitar tus compras
                        </p>
                        <button onclick="document.getElementById('add-address-form').style.display='block'; window.scrollTo({top: 0, behavior: 'smooth'});"
                                style="padding: 12px 28px; background: var(--vitta-gold); color: var(--vitta-black); border: none; border-radius: 6px; font-weight: 600; cursor: pointer; transition: all 0.3s;"
                                onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 4px 12px rgba(212, 175, 55, 0.3)';"
                                onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none';">
                            <i class="bi bi-plus-lg"></i> Agregar Primera Dirección
                        </button>
                    </div>
                @endif

            </div>

        </div>
    </div>
</div>
@endsection
