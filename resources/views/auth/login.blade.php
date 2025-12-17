<x-guest-layout>

    <h2 style="text-align: center; color: #F8F5F0; font-size: 24px; margin-bottom: 8px;">Iniciar Sesión</h2>
    <p style="text-align: center; color: rgba(248, 245, 240, 0.6); font-size: 14px; margin-bottom: 32px;">Accede a tu
        cuenta de Vitta Perfumes</p>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        <div class="form-group">
            <label for="email" class="form-label">Email</label>
            <input id="email" class="form-control" type="email" name="email" :value="old('email')" required autofocus
                autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="text-error" />
        </div>

        <!-- Password -->
        <div class="form-group">
            <label for="password" class="form-label">Contraseña</label>
            <input id="password" class="form-control" type="password" name="password" required
                autocomplete="current-password" />
            <x-input-error :messages="$errors->get('password')" class="text-error" />
        </div>

        <!-- Remember Me -->
        <div style="display: flex; align-items: center; margin-bottom: 24px;">
            <input id="remember_me" type="checkbox" name="remember"
                style="width: 16px; height: 16px; margin-right: 8px;">
            <label for="remember_me" style="color: rgba(248, 245, 240, 0.8); font-size: 14px; cursor: pointer;">
                Recordarme
            </label>
        </div>

        <button type="submit" class="btn-primary">
            Ingresar
        </button>

        <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 24px;">
            @if (Route::has('password.request'))
                <a class="link-gold" href="{{ route('password.request') }}">
                    ¿Olvidaste tu contraseña?
                </a>
            @endif

            <a class="link-gold" href="{{ route('register') }}">
                Crear cuenta
            </a>
        </div>

        <div class="divider">
            <span>O continuar como</span>
        </div>

        <a href="{{ route('home') }}"
            style="display: block; text-align: center; padding: 12px; border: 2px solid rgba(212, 175, 55, 0.3); color: var(--vitta-pearl); text-decoration: none; border-radius: 6px; font-weight: 600; transition: all 0.3s;">
            Invitado
        </a>
    </form>
</x-guest-layout>