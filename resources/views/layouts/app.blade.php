<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Vitta Perfumes - Lujo Árabe')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link
        href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;500;600;700&family=Montserrat:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">

    <!-- Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        :root {
            --vitta-gold: #D4AF37;
            --vitta-gold-dark: #B8941F;
            --vitta-gold-light: #F4E4B8;
            --vitta-black: #0A0A0A;
            --vitta-black-soft: #1A1A1A;
            --vitta-gray: #2A2A2A;
            --vitta-pearl: #F8F5F0;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Montserrat', sans-serif;
            background: var(--vitta-black);
            color: var(--vitta-pearl);
            overflow-x: hidden;
        }

        h1,
        h2,
        h3,
        h4,
        h5,
        h6 {
            font-family: 'Cinzel', serif;
            font-weight: 600;
        }

        .vitta-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 24px;
        }

        .golden-line {
            height: 1px;
            background: linear-gradient(90deg, transparent, var(--vitta-gold), transparent);
        }

        .arabic-pattern {
            background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M30 0l30 30-30 30L0 30z' fill='%23D4AF37' fill-opacity='0.03'/%3E%3C/svg%3E");
        }

        .btn-gold {
            background: linear-gradient(135deg, var(--vitta-gold), var(--vitta-gold-dark));
            color: var(--vitta-black);
            border: none;
            padding: 14px 32px;
            font-weight: 600;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .btn-gold::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
            transition: left 0.5s;
        }

        .btn-gold:hover::before {
            left: 100%;
        }

        .btn-gold:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(212, 175, 55, 0.4);
        }

        .navbar-vitta {
            background: rgba(10, 10, 10, 0.95);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(212, 175, 55, 0.2);
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .cart-badge {
            background: var(--vitta-gold);
            color: var(--vitta-black);
            border-radius: 50%;
            padding: 2px 6px;
            font-size: 11px;
            font-weight: 700;
            position: absolute;
            top: -5px;
            right: -8px;
        }
    </style>

    @stack('styles')
</head>

<body class="arabic-pattern">

    <!-- Navbar -->
    <nav class="navbar-vitta">
        <div class="vitta-container">
            <div style="display: flex; align-items: center; justify-content: space-between; padding: 20px 0;">

                <!-- Logo -->
                <a href="{{ route('home') }}" style="text-decoration: none;">
                    <h1 style="color: var(--vitta-gold); font-size: 28px; margin: 0; letter-spacing: 2px;">
                        VITTA
                    </h1>
                    <p
                        style="color: var(--vitta-gold-light); font-size: 10px; margin: 0; letter-spacing: 3px; text-align: center;">
                        PERFUMES
                    </p>
                </a>

                <!-- Menu -->
                <div style="display: flex; gap: 32px; align-items: center;">
                    <a href="{{ route('home') }}"
                        style="color: var(--vitta-pearl); text-decoration: none; font-weight: 500; font-size: 14px; letter-spacing: 0.5px;">INICIO</a>
                    <a href="{{ route('products.index') }}"
                        style="color: var(--vitta-pearl); text-decoration: none; font-weight: 500; font-size: 14px; letter-spacing: 0.5px;">CATÁLOGO</a>
                    <a href="#"
                        style="color: var(--vitta-pearl); text-decoration: none; font-weight: 500; font-size: 14px; letter-spacing: 0.5px;">CATEGORÍAS</a>
                    <a href="#"
                        style="color: var(--vitta-pearl); text-decoration: none; font-weight: 500; font-size: 14px; letter-spacing: 0.5px;">NOSOTROS</a>
                </div>

                <!-- Actions -->
                <div style="display: flex; gap: 24px; align-items: center;">

                    <!-- Search -->
                    <button
                        style="background: none; border: none; color: var(--vitta-gold); cursor: pointer; font-size: 20px;">
                        <i class="bi bi-search"></i>
                    </button>

                    <!-- Cart -->
                    <a href="{{ route('cart.index') }}"
                        style="position: relative; color: var(--vitta-gold); font-size: 22px; text-decoration: none;">
                        <i class="bi bi-bag"></i>
                        @if(isset($cartCount) && $cartCount > 0)
                            <span class="cart-badge">{{ $cartCount }}</span>
                        @endif
                    </a>

                    <!-- User -->
                    @auth
                        <div style="position: relative;">
                            <button onclick="toggleUserMenu()"
                                style="background: none; border: none; color: var(--vitta-pearl); cursor: pointer; font-size: 20px;">
                                <i class="bi bi-person"></i>
                            </button>
                            <div id="userMenu"
                                style="display: none; position: absolute; right: 0; top: 100%; margin-top: 12px; background: #1A1A1A; border: 1px solid rgba(212, 175, 55, 0.2); border-radius: 8px; min-width: 200px; box-shadow: 0 8px 24px rgba(0,0,0,0.5); z-index: 1000;">
                                <div style="padding: 16px; border-bottom: 1px solid rgba(212, 175, 55, 0.2);">
                                    <p style="font-weight: 600; margin-bottom: 4px;">{{ auth()->user()->name }}</p>
                                    <p style="font-size: 12px; color: rgba(248, 245, 240, 0.6);">{{ auth()->user()->email }}
                                    </p>
                                </div>
                                <a href="{{ route('customer.dashboard') }}"
                                    style="display: block; padding: 12px 16px; color: var(--vitta-pearl); text-decoration: none; font-size: 14px; transition: background 0.3s;"
                                    onmouseover="this.style.background='rgba(212, 175, 55, 0.1)'"
                                    onmouseout="this.style.background='transparent'">
                                    <i class="bi bi-house-door" style="margin-right: 8px;"></i> Mi Cuenta
                                </a>
                                <a href="{{ route('customer.orders') }}"
                                    style="display: block; padding: 12px 16px; color: var(--vitta-pearl); text-decoration: none; font-size: 14px; transition: background 0.3s;"
                                    onmouseover="this.style.background='rgba(212, 175, 55, 0.1)'"
                                    onmouseout="this.style.background='transparent'">
                                    <i class="bi bi-receipt" style="margin-right: 8px;"></i> Mis Pedidos
                                </a>
                                <a href="{{ route('customer.wishlist') }}"
                                    style="display: block; padding: 12px 16px; color: var(--vitta-pearl); text-decoration: none; font-size: 14px; transition: background 0.3s;"
                                    onmouseover="this.style.background='rgba(212, 175, 55, 0.1)'"
                                    onmouseout="this.style.background='transparent'">
                                    <i class="bi bi-heart" style="margin-right: 8px;"></i> Mis Favoritos
                                </a>
                                <a href="{{ route('profile.edit') }}"
                                    style="display: block; padding: 12px 16px; color: var(--vitta-pearl); text-decoration: none; font-size: 14px; transition: background 0.3s;"
                                    onmouseover="this.style.background='rgba(212, 175, 55, 0.1)'"
                                    onmouseout="this.style.background='transparent'">
                                    <i class="bi bi-person-circle" style="margin-right: 8px;"></i> Editar Perfil
                                </a>
                                @if(auth()->user()->isAdmin())
                                    <div style="border-top: 1px solid rgba(212, 175, 55, 0.2); margin: 8px 0;"></div>
                                    <a href="{{ route('admin.dashboard') }}"
                                        style="display: block; padding: 12px 16px; color: var(--vitta-gold); text-decoration: none; font-size: 14px; transition: background 0.3s;"
                                        onmouseover="this.style.background='rgba(212, 175, 55, 0.1)'"
                                        onmouseout="this.style.background='transparent'">
                                        <i class="bi bi-speedometer2" style="margin-right: 8px;"></i> Panel Admin
                                    </a>
                                @endif
                                <div style="border-top: 1px solid rgba(212, 175, 55, 0.2); margin: 8px 0;"></div>
                                <form method="POST" action="{{ route('logout') }}" style="margin: 0;">
                                    @csrf
                                    <button type="submit"
                                        style="width: 100%; text-align: left; background: none; border: none; padding: 12px 16px; color: #ef4444; cursor: pointer; font-size: 14px; transition: background 0.3s;"
                                        onmouseover="this.style.background='rgba(239, 68, 68, 0.1)'"
                                        onmouseout="this.style.background='transparent'">
                                        <i class="bi bi-box-arrow-left" style="margin-right: 8px;"></i> Cerrar Sesión
                                    </button>
                                </form>
                            </div>
                        </div>
                    @else
                        <a href="{{ route('login') }}"
                            style="color: var(--vitta-pearl); text-decoration: none; font-size: 14px; font-weight: 500;">INGRESAR</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    <footer
        style="background: var(--vitta-black-soft); border-top: 1px solid rgba(212, 175, 55, 0.2); margin-top: 80px; padding: 60px 0 30px;">
        <div class="vitta-container">
            <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 48px; margin-bottom: 48px;">

                <!-- Brand -->
                <div>
                    <h3 style="color: var(--vitta-gold); margin-bottom: 16px;">VITTA</h3>
                    <p style="color: var(--vitta-pearl); opacity: 0.7; line-height: 1.8; font-size: 14px;">
                        Fragancias árabes de lujo que cautivan tus sentidos con la elegancia de Oriente.
                    </p>
                    <div style="display: flex; gap: 16px; margin-top: 24px;">
                        <a href="#" style="color: var(--vitta-gold); font-size: 20px;"><i
                                class="bi bi-instagram"></i></a>
                        <a href="#" style="color: var(--vitta-gold); font-size: 20px;"><i
                                class="bi bi-facebook"></i></a>
                        <a href="#" style="color: var(--vitta-gold); font-size: 20px;"><i class="bi bi-tiktok"></i></a>
                        <a href="#" style="color: var(--vitta-gold); font-size: 20px;"><i
                                class="bi bi-whatsapp"></i></a>
                    </div>
                </div>

                <!-- Links -->
                <div>
                    <h4 style="color: var(--vitta-gold); font-size: 14px; margin-bottom: 16px; letter-spacing: 1px;">
                        COMPRAR</h4>
                    <ul style="list-style: none; padding: 0;">
                        <li style="margin-bottom: 12px;"><a href="#"
                                style="color: var(--vitta-pearl); opacity: 0.7; text-decoration: none; font-size: 14px;">Oud
                                Premium</a></li>
                        <li style="margin-bottom: 12px;"><a href="#"
                                style="color: var(--vitta-pearl); opacity: 0.7; text-decoration: none; font-size: 14px;">Musk
                                Oriental</a></li>
                        <li style="margin-bottom: 12px;"><a href="#"
                                style="color: var(--vitta-pearl); opacity: 0.7; text-decoration: none; font-size: 14px;">Bakhoor</a>
                        </li>
                        <li style="margin-bottom: 12px;"><a href="#"
                                style="color: var(--vitta-pearl); opacity: 0.7; text-decoration: none; font-size: 14px;">Aceites</a>
                        </li>
                    </ul>
                </div>

                <!-- Info -->
                <div>
                    <h4 style="color: var(--vitta-gold); font-size: 14px; margin-bottom: 16px; letter-spacing: 1px;">
                        INFORMACIÓN</h4>
                    <ul style="list-style: none; padding: 0;">
                        <li style="margin-bottom: 12px;"><a href="#"
                                style="color: var(--vitta-pearl); opacity: 0.7; text-decoration: none; font-size: 14px;">Sobre
                                Nosotros</a></li>
                        <li style="margin-bottom: 12px;"><a href="#"
                                style="color: var(--vitta-pearl); opacity: 0.7; text-decoration: none; font-size: 14px;">Envíos</a>
                        </li>
                        <li style="margin-bottom: 12px;"><a href="#"
                                style="color: var(--vitta-pearl); opacity: 0.7; text-decoration: none; font-size: 14px;">Devoluciones</a>
                        </li>
                        <li style="margin-bottom: 12px;"><a href="#"
                                style="color: var(--vitta-pearl); opacity: 0.7; text-decoration: none; font-size: 14px;">Términos</a>
                        </li>
                    </ul>
                </div>

                <!-- Contact -->
                <div>
                    <h4 style="color: var(--vitta-gold); font-size: 14px; margin-bottom: 16px; letter-spacing: 1px;">
                        CONTACTO</h4>
                    <p style="color: var(--vitta-pearl); opacity: 0.7; font-size: 14px; margin-bottom: 12px;">
                        <i class="bi bi-envelope" style="margin-right: 8px;"></i>
                        contacto@vittaperfumes.com
                    </p>
                    <p style="color: var(--vitta-pearl); opacity: 0.7; font-size: 14px; margin-bottom: 12px;">
                        <i class="bi bi-whatsapp" style="margin-right: 8px;"></i>
                        +54 9 351 123 4567
                    </p>
                    <p style="color: var(--vitta-pearl); opacity: 0.7; font-size: 14px;">
                        <i class="bi bi-geo-alt" style="margin-right: 8px;"></i>
                        Córdoba, Argentina
                    </p>
                </div>
            </div>

            <div class="golden-line" style="margin: 32px 0;"></div>

            <div style="text-align: center; color: var(--vitta-pearl); opacity: 0.5; font-size: 13px;">
                © 2024 Vitta Perfumes. Todos los derechos reservados.
            </div>
        </div>
    </footer>

    @stack('scripts')

    <script>
        function toggleUserMenu() {
            const menu = document.getElementById('userMenu');
            menu.style.display = menu.style.display === 'none' ? 'block' : 'none';
        }

        // Cerrar menú al hacer clic fuera
        document.addEventListener('click', function (event) {
            const menu = document.getElementById('userMenu');
            const button = event.target.closest('button');
            if (menu && !menu.contains(event.target) && !button) {
                menu.style.display = 'none';
            }
        });
    </script>

</body>

</html>