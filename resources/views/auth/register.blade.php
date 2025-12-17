<x-guest-layout>

    <h2 style="text-align: center; color: #F8F5F0; font-size: 24px; margin-bottom: 8px;">Crear Cuenta</h2>
    <p style="text-align: center; color: rgba(248, 245, 240, 0.6); font-size: 14px; margin-bottom: 32px;">Únete a Vitta
        Perfumes</p>

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Name -->
        <div class="form-group">
            <label for="name" class="form-label">Nombre Completo</label>
            <input id="name" class="form-control" type="text" name="name" :value="old('name')" required autofocus
                autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="text-error" />
        </div>

        <!-- Email Address -->
        <div class="form-group">
            <label for="email" class="form-label">Email</label>
            <input id="email" class="form-control" type="email" name="email" :value="old('email')" required
                autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="text-error" />
        </div>

        <!-- Password -->
        <div class="form-group">
            <label for="password" class="form-label">Contraseña</label>
            <input id="password" class="form-control" type="password" name="password" required
                autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password')" class="text-error" />
        </div>

        <!-- Confirm Password -->
        <div class="form-group">
            <label for="password_confirmation" class="form-label">Confirmar Contraseña</label>
            <input id="password_confirmation" class="form-control" type="password" name="password_confirmation" required
                autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="text-error" />
        </div>

        <button type="submit" class="btn-primary" style="margin-top: 24px;">
            Crear Cuenta
        </button>

        <div style="text-align: center; margin-top: 24px;">
            <span style="color: rgba(248, 245, 240, 0.6); font-size: 14px;">¿Ya tienes cuenta?</span>
            <a class="link-gold" href="{{ route('login') }}" style="margin-left: 8px;">
                Iniciar sesión
            </a>
        </div>
    </form>
</x-guest-layout>