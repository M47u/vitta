<aside style="background: var(--vitta-black-soft); border: 1px solid rgba(212, 175, 55, 0.2); border-radius: 8px; padding: 24px; height: fit-content; position: sticky; top: 100px;">
    
    <!-- User Info -->
    <div style="text-align: center; padding-bottom: 24px; border-bottom: 1px solid rgba(212, 175, 55, 0.2); margin-bottom: 20px;">
        <div style="width: 80px; height: 80px; margin: 0 auto 16px; background: linear-gradient(135deg, var(--vitta-gold) 0%, #8B7029 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
            <span style="font-size: 32px; font-weight: 700; color: var(--vitta-black);">
                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
            </span>
        </div>
        <h3 style="font-size: 16px; font-weight: 600; color: var(--vitta-pearl); margin-bottom: 4px;">
            {{ Auth::user()->name }}
        </h3>
        <p style="font-size: 13px; color: var(--vitta-pearl); opacity: 0.6;">
            {{ Auth::user()->email }}
        </p>
    </div>

    <!-- Menu Items -->
    <nav>
        <a href="{{ route('customer.dashboard') }}" 
           style="display: flex; align-items: center; padding: 14px 16px; color: var(--vitta-pearl); text-decoration: none; border-radius: 6px; margin-bottom: 6px; transition: all 0.3s;
           {{ request()->routeIs('customer.dashboard') ? 'background: rgba(212, 175, 55, 0.1); border-left: 3px solid var(--vitta-gold);' : '' }}"
           onmouseover="if(!this.classList.contains('active')) { this.style.background='rgba(212, 175, 55, 0.05)'; }"
           onmouseout="if(!this.classList.contains('active')) { this.style.background='transparent'; }"
           class="{{ request()->routeIs('customer.dashboard') ? 'active' : '' }}">
            <i class="bi bi-house-door" style="font-size: 18px; margin-right: 12px; {{ request()->routeIs('customer.dashboard') ? 'color: var(--vitta-gold);' : '' }}"></i>
            <span style="{{ request()->routeIs('customer.dashboard') ? 'color: var(--vitta-gold); font-weight: 600;' : '' }}">Panel Principal</span>
        </a>

        <a href="{{ route('customer.orders') }}" 
           style="display: flex; align-items: center; padding: 14px 16px; color: var(--vitta-pearl); text-decoration: none; border-radius: 6px; margin-bottom: 6px; transition: all 0.3s;
           {{ request()->routeIs('customer.orders*') ? 'background: rgba(212, 175, 55, 0.1); border-left: 3px solid var(--vitta-gold);' : '' }}"
           onmouseover="if(!this.classList.contains('active')) { this.style.background='rgba(212, 175, 55, 0.05)'; }"
           onmouseout="if(!this.classList.contains('active')) { this.style.background='transparent'; }"
           class="{{ request()->routeIs('customer.orders*') ? 'active' : '' }}">
            <i class="bi bi-receipt" style="font-size: 18px; margin-right: 12px; {{ request()->routeIs('customer.orders*') ? 'color: var(--vitta-gold);' : '' }}"></i>
            <span style="{{ request()->routeIs('customer.orders*') ? 'color: var(--vitta-gold); font-weight: 600;' : '' }}">Mis Pedidos</span>
        </a>

        <a href="{{ route('customer.wishlist') }}" 
           style="display: flex; align-items: center; padding: 14px 16px; color: var(--vitta-pearl); text-decoration: none; border-radius: 6px; margin-bottom: 6px; transition: all 0.3s;
           {{ request()->routeIs('customer.wishlist') ? 'background: rgba(212, 175, 55, 0.1); border-left: 3px solid var(--vitta-gold);' : '' }}"
           onmouseover="if(!this.classList.contains('active')) { this.style.background='rgba(212, 175, 55, 0.05)'; }"
           onmouseout="if(!this.classList.contains('active')) { this.style.background='transparent'; }"
           class="{{ request()->routeIs('customer.wishlist') ? 'active' : '' }}">
            <i class="bi bi-heart" style="font-size: 18px; margin-right: 12px; {{ request()->routeIs('customer.wishlist') ? 'color: var(--vitta-gold);' : '' }}"></i>
            <span style="{{ request()->routeIs('customer.wishlist') ? 'color: var(--vitta-gold); font-weight: 600;' : '' }}">Mis Favoritos</span>
        </a>

        <a href="{{ route('customer.addresses') }}" 
           style="display: flex; align-items: center; padding: 14px 16px; color: var(--vitta-pearl); text-decoration: none; border-radius: 6px; margin-bottom: 6px; transition: all 0.3s;
           {{ request()->routeIs('customer.addresses*') ? 'background: rgba(212, 175, 55, 0.1); border-left: 3px solid var(--vitta-gold);' : '' }}"
           onmouseover="if(!this.classList.contains('active')) { this.style.background='rgba(212, 175, 55, 0.05)'; }"
           onmouseout="if(!this.classList.contains('active')) { this.style.background='transparent'; }"
           class="{{ request()->routeIs('customer.addresses*') ? 'active' : '' }}">
            <i class="bi bi-geo-alt" style="font-size: 18px; margin-right: 12px; {{ request()->routeIs('customer.addresses*') ? 'color: var(--vitta-gold);' : '' }}"></i>
            <span style="{{ request()->routeIs('customer.addresses*') ? 'color: var(--vitta-gold); font-weight: 600;' : '' }}">Mis Direcciones</span>
        </a>

        <a href="{{ route('customer.account') }}" 
           style="display: flex; align-items: center; padding: 14px 16px; color: var(--vitta-pearl); text-decoration: none; border-radius: 6px; margin-bottom: 6px; transition: all 0.3s;
           {{ request()->routeIs('customer.account') ? 'background: rgba(212, 175, 55, 0.1); border-left: 3px solid var(--vitta-gold);' : '' }}"
           onmouseover="if(!this.classList.contains('active')) { this.style.background='rgba(212, 175, 55, 0.05)'; }"
           onmouseout="if(!this.classList.contains('active')) { this.style.background='transparent'; }"
           class="{{ request()->routeIs('customer.account') ? 'active' : '' }}">
            <i class="bi bi-person" style="font-size: 18px; margin-right: 12px; {{ request()->routeIs('customer.account') ? 'color: var(--vitta-gold);' : '' }}"></i>
            <span style="{{ request()->routeIs('customer.account') ? 'color: var(--vitta-gold); font-weight: 600;' : '' }}">Datos Personales</span>
        </a>

        <div style="border-top: 1px solid rgba(212, 175, 55, 0.2); margin: 16px 0;"></div>

        <a href="{{ route('home') }}" 
           style="display: flex; align-items: center; padding: 14px 16px; color: var(--vitta-pearl); text-decoration: none; border-radius: 6px; margin-bottom: 6px; transition: all 0.3s;"
           onmouseover="this.style.background='rgba(212, 175, 55, 0.05)';"
           onmouseout="this.style.background='transparent';">
            <i class="bi bi-shop" style="font-size: 18px; margin-right: 12px;"></i>
            <span>Ir a la Tienda</span>
        </a>

        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" 
                    style="width: 100%; display: flex; align-items: center; padding: 14px 16px; color: #ef4444; background: none; border: none; text-decoration: none; border-radius: 6px; cursor: pointer; transition: all 0.3s;"
                    onmouseover="this.style.background='rgba(239, 68, 68, 0.1)';"
                    onmouseout="this.style.background='transparent';">
                <i class="bi bi-box-arrow-left" style="font-size: 18px; margin-right: 12px;"></i>
                <span>Cerrar Sesión</span>
            </button>
        </form>
    </nav>

</aside>
