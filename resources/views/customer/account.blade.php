@extends('layouts.app')

@section('title', 'Mis Datos - Vitta Perfumes')

@section('content')
<div style="min-height: 100vh; padding: 40px 0; background: var(--vitta-black);">
    <div class="container">
        <div style="display: grid; grid-template-columns: 280px 1fr; gap: 32px;">
            
            <!-- Sidebar -->
            @include('customer.partials.sidebar')

            <!-- Main Content -->
            <div>
                
                <!-- Header -->
                <div style="margin-bottom: 32px;">
                    <h1 style="font-size: 28px; font-weight: 700; color: var(--vitta-gold); margin-bottom: 8px;">
                        Datos Personales
                    </h1>
                    <p style="color: var(--vitta-pearl); opacity: 0.7;">
                        Actualiza tu información personal
                    </p>
                </div>

                <!-- Success Message -->
                @if(session('success'))
                    <div style="background: rgba(25, 135, 84, 0.1); border: 1px solid #198754; border-radius: 8px; padding: 16px; margin-bottom: 24px; display: flex; align-items: center; gap: 12px;">
                        <i class="bi bi-check-circle-fill" style="color: #198754; font-size: 20px;"></i>
                        <span style="color: #198754; font-weight: 500;">{{ session('success') }}</span>
                    </div>
                @endif

                <div style="display: grid; gap: 24px;">

                    <!-- Personal Information -->
                    <div style="background: var(--vitta-black-soft); border: 1px solid rgba(212, 175, 55, 0.2); border-radius: 8px; padding: 24px;">
                        <h2 style="font-size: 18px; font-weight: 600; color: var(--vitta-gold); margin-bottom: 20px;">
                            Información Personal
                        </h2>
                        
                        <form method="POST" action="{{ route('customer.account.update') }}">
                            @csrf
                            @method('PUT')
                            
                            <div style="display: grid; gap: 20px;">
                                
                                <!-- Name -->
                                <div>
                                    <label style="display: block; color: var(--vitta-pearl); font-size: 14px; margin-bottom: 8px;">
                                        Nombre Completo *
                                    </label>
                                    <input type="text" 
                                           name="name" 
                                           value="{{ old('name', Auth::user()->name) }}" 
                                           required
                                           style="width: 100%; padding: 12px 16px; background: var(--vitta-black); border: 1px solid rgba(212, 175, 55, 0.3); border-radius: 6px; color: var(--vitta-pearl); font-size: 15px;">
                                    @error('name')
                                        <span style="color: #dc3545; font-size: 13px; margin-top: 4px; display: block;">{{ $message }}</span>
                                    @enderror
                                </div>

                                <!-- Email -->
                                <div>
                                    <label style="display: block; color: var(--vitta-pearl); font-size: 14px; margin-bottom: 8px;">
                                        Email *
                                    </label>
                                    <input type="email" 
                                           name="email" 
                                           value="{{ old('email', Auth::user()->email) }}" 
                                           required
                                           style="width: 100%; padding: 12px 16px; background: var(--vitta-black); border: 1px solid rgba(212, 175, 55, 0.3); border-radius: 6px; color: var(--vitta-pearl); font-size: 15px;">
                                    @error('email')
                                        <span style="color: #dc3545; font-size: 13px; margin-top: 4px; display: block;">{{ $message }}</span>
                                    @enderror
                                </div>

                                <!-- Phone -->
                                <div>
                                    <label style="display: block; color: var(--vitta-pearl); font-size: 14px; margin-bottom: 8px;">
                                        Teléfono
                                    </label>
                                    <input type="tel" 
                                           name="phone" 
                                           value="{{ old('phone', Auth::user()->phone) }}"
                                           style="width: 100%; padding: 12px 16px; background: var(--vitta-black); border: 1px solid rgba(212, 175, 55, 0.3); border-radius: 6px; color: var(--vitta-pearl); font-size: 15px;">
                                    @error('phone')
                                        <span style="color: #dc3545; font-size: 13px; margin-top: 4px; display: block;">{{ $message }}</span>
                                    @enderror
                                </div>

                                <!-- Submit Button -->
                                <div style="padding-top: 8px;">
                                    <button type="submit"
                                            style="padding: 12px 32px; background: var(--vitta-gold); color: var(--vitta-black); border: none; border-radius: 6px; font-weight: 600; font-size: 15px; cursor: pointer; transition: all 0.3s;"
                                            onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 4px 12px rgba(212, 175, 55, 0.3)';"
                                            onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none';">
                                        <i class="bi bi-check-lg"></i> Guardar Cambios
                                    </button>
                                </div>

                            </div>

                        </form>
                    </div>

                    <!-- Change Password -->
                    <div style="background: var(--vitta-black-soft); border: 1px solid rgba(212, 175, 55, 0.2); border-radius: 8px; padding: 24px;">
                        <h2 style="font-size: 18px; font-weight: 600; color: var(--vitta-gold); margin-bottom: 20px;">
                            Cambiar Contraseña
                        </h2>
                        
                        <form method="POST" action="{{ route('customer.account.password') }}">
                            @csrf
                            @method('PUT')
                            
                            <div style="display: grid; gap: 20px;">
                                
                                <!-- Current Password -->
                                <div>
                                    <label style="display: block; color: var(--vitta-pearl); font-size: 14px; margin-bottom: 8px;">
                                        Contraseña Actual *
                                    </label>
                                    <input type="password" 
                                           name="current_password" 
                                           required
                                           style="width: 100%; padding: 12px 16px; background: var(--vitta-black); border: 1px solid rgba(212, 175, 55, 0.3); border-radius: 6px; color: var(--vitta-pearl); font-size: 15px;">
                                    @error('current_password')
                                        <span style="color: #dc3545; font-size: 13px; margin-top: 4px; display: block;">{{ $message }}</span>
                                    @enderror
                                </div>

                                <!-- New Password -->
                                <div>
                                    <label style="display: block; color: var(--vitta-pearl); font-size: 14px; margin-bottom: 8px;">
                                        Nueva Contraseña *
                                    </label>
                                    <input type="password" 
                                           name="password" 
                                           required
                                           style="width: 100%; padding: 12px 16px; background: var(--vitta-black); border: 1px solid rgba(212, 175, 55, 0.3); border-radius: 6px; color: var(--vitta-pearl); font-size: 15px;">
                                    @error('password')
                                        <span style="color: #dc3545; font-size: 13px; margin-top: 4px; display: block;">{{ $message }}</span>
                                    @enderror
                                </div>

                                <!-- Confirm Password -->
                                <div>
                                    <label style="display: block; color: var(--vitta-pearl); font-size: 14px; margin-bottom: 8px;">
                                        Confirmar Nueva Contraseña *
                                    </label>
                                    <input type="password" 
                                           name="password_confirmation" 
                                           required
                                           style="width: 100%; padding: 12px 16px; background: var(--vitta-black); border: 1px solid rgba(212, 175, 55, 0.3); border-radius: 6px; color: var(--vitta-pearl); font-size: 15px;">
                                </div>

                                <!-- Submit Button -->
                                <div style="padding-top: 8px;">
                                    <button type="submit"
                                            style="padding: 12px 32px; background: var(--vitta-gold); color: var(--vitta-black); border: none; border-radius: 6px; font-weight: 600; font-size: 15px; cursor: pointer; transition: all 0.3s;"
                                            onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 4px 12px rgba(212, 175, 55, 0.3)';"
                                            onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none';">
                                        <i class="bi bi-shield-lock"></i> Actualizar Contraseña
                                    </button>
                                </div>

                            </div>

                        </form>
                    </div>

                    <!-- Account Statistics -->
                    <div style="background: var(--vitta-black-soft); border: 1px solid rgba(212, 175, 55, 0.2); border-radius: 8px; padding: 24px;">
                        <h2 style="font-size: 18px; font-weight: 600; color: var(--vitta-gold); margin-bottom: 20px;">
                            Información de la Cuenta
                        </h2>
                        <div style="display: grid; gap: 16px;">
                            
                            <div style="display: flex; justify-content: space-between; padding: 12px 0; border-bottom: 1px solid rgba(212, 175, 55, 0.1);">
                                <span style="color: var(--vitta-pearl); opacity: 0.7;">Fecha de Registro:</span>
                                <span style="color: var(--vitta-pearl); font-weight: 600;">
                                    {{ Auth::user()->created_at->format('d/m/Y') }}
                                </span>
                            </div>

                            <div style="display: flex; justify-content: space-between; padding: 12px 0; border-bottom: 1px solid rgba(212, 175, 55, 0.1);">
                                <span style="color: var(--vitta-pearl); opacity: 0.7;">Email Verificado:</span>
                                <span style="color: {{ Auth::user()->email_verified_at ? '#198754' : '#dc3545' }}; font-weight: 600;">
                                    @if(Auth::user()->email_verified_at)
                                        <i class="bi bi-check-circle-fill"></i> Sí
                                    @else
                                        <i class="bi bi-x-circle-fill"></i> No
                                    @endif
                                </span>
                            </div>

                            <div style="display: flex; justify-content: space-between; padding: 12px 0;">
                                <span style="color: var(--vitta-pearl); opacity: 0.7;">Última Actualización:</span>
                                <span style="color: var(--vitta-pearl); font-weight: 600;">
                                    {{ Auth::user()->updated_at->format('d/m/Y H:i') }}
                                </span>
                            </div>

                        </div>
                    </div>

                </div>

            </div>

        </div>
    </div>
</div>
@endsection
