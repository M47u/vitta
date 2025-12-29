@extends('layouts.app')

@section('title', 'Vitta Perfumes - Lujo Árabe en Cada Gota')

@section('content')

    <!-- Hero Section -->
    <section
        style="height: 85vh; display: flex; align-items: center; justify-content: center; position: relative; overflow: hidden;">

        <!-- Background particles -->
        <div class="golden-particles" style="position: absolute; inset: 0; z-index: 0;"></div>

        <!-- Gradient overlay -->
        <div
            style="position: absolute; inset: 0; background: radial-gradient(circle at center, rgba(212, 175, 55, 0.08) 0%, transparent 70%); z-index: 1;">
        </div>

        <!-- Content -->
        <div class="vitta-container" style="position: relative; z-index: 2; text-align: center;">
            <h1 class="animate-fade-in-up"
                style="font-size: 72px; color: var(--vitta-gold); margin-bottom: 24px; letter-spacing: 2px; text-shadow: 0 0 40px rgba(212, 175, 55, 0.3);">
                El Lujo de Oriente
            </h1>
            <p class="animate-fade-in-up delay-200"
                style="font-size: 28px; color: var(--vitta-pearl); margin-bottom: 16px; font-weight: 300;">
                en Cada Gota
            </p>
            <p class="animate-fade-in-up delay-300"
                style="font-size: 16px; color: var(--vitta-pearl); opacity: 0.7; max-width: 600px; margin: 0 auto 48px; line-height: 1.8;">
                Descubrí la elegancia de los perfumes árabes más exclusivos. Oud auténtico, almizcles orientales y
                fragancias que cautivan los sentidos.
            </p>

            <div class="animate-fade-in-up delay-400" style="display: flex; gap: 16px; justify-content: center;">
                <a href="{{ route('products.index') }}" class="btn-gold">
                    EXPLORAR CATÁLOGO
                </a>
                <a href="#featured"
                    style="padding: 14px 32px; border: 2px solid var(--vitta-gold); color: var(--vitta-gold); text-decoration: none; font-weight: 600; letter-spacing: 0.5px; text-transform: uppercase; transition: all 0.3s;">
                    VER DESTACADOS
                </a>
            </div>
        </div>

        <!-- Scroll indicator -->
        <div
            style="position: absolute; bottom: 40px; left: 50%; transform: translateX(-50%); display: flex; flex-direction: column; align-items: center; gap: 12px; animation: scroll 2s ease-in-out infinite;">
            <span style="color: var(--vitta-gold); font-size: 12px; letter-spacing: 2px;">SCROLL</span>
            <i class="bi bi-chevron-down" style="color: var(--vitta-gold); font-size: 20px;"></i>
        </div>
    </section>

    <div class="golden-line"></div>

    <!-- Featured Products -->
    <section id="featured" style="padding: 80px 0;">
        <div class="vitta-container">

            <div style="text-align: center; margin-bottom: 64px;">
                <h2 style="font-size: 48px; color: var(--vitta-gold); margin-bottom: 16px;">
                    Productos Destacados
                </h2>
                <p style="font-size: 16px; color: var(--vitta-pearl); opacity: 0.7;">
                    <a href="{{ route('products.index') }}"
                        style="color: var(--vitta-gold); text-decoration: none; font-weight: 600; letter-spacing: 1px; font-size: 14px;">
                        VER TODOS LOS PRODUCTOS <i class="bi bi-arrow-right" style="margin-left: 8px;"></i>
                    </a>
                </p>
            </div>

            <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 24px;">

                @forelse($featured as $product)
                    <a href="{{ route('products.show', $product->slug) }}" class="product-card"
                        style="background: linear-gradient(135deg, rgba(26, 26, 26, 0.9), rgba(42, 42, 42, 0.9)); border: 1px solid rgba(212, 175, 55, 0.2); border-radius: 8px; overflow: hidden; text-decoration: none; display: flex; flex-direction: column; transition: all 0.3s;">

                        <div style="position: relative; width: 100%; padding-top: 100%; overflow: hidden;">
                            @if($product->main_image)
                                <img src="{{ asset('storage/' . $product->main_image) }}" 
                                     alt="{{ $product->name }}"
                                     style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; object-fit: cover;">
                            @else
                                <div style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: linear-gradient(135deg, #1a1a1a, #2a2a2a); display: flex; align-items: center; justify-content: center;">
                                    <i class="bi bi-image" style="font-size: 48px; color: var(--vitta-gold); opacity: 0.3;"></i>
                                </div>
                            @endif

                            @if($product->is_on_sale)
                                <div style="position: absolute; top: 16px; right: 16px; background: var(--vitta-gold); color: var(--vitta-charcoal); padding: 6px 12px; border-radius: 4px; font-size: 12px; font-weight: 700; letter-spacing: 0.5px;">
                                    -{{ $product->discount_percentage }}%
                                </div>
                            @endif
                        </div>

                        <div style="padding: 24px; flex-grow: 1; display: flex; flex-direction: column;">
                            <span style="font-size: 12px; color: var(--vitta-gold); text-transform: uppercase; letter-spacing: 1px; margin-bottom: 8px;">
                                {{ $product->category->name ?? 'Sin categoría' }}
                            </span>

                            <h3 style="font-size: 18px; color: var(--vitta-pearl); margin-bottom: 8px; font-weight: 600;">
                                {{ $product->name }}
                            </h3>

                            <p style="font-size: 14px; color: var(--vitta-pearl); opacity: 0.7; margin-bottom: 16px; line-height: 1.6; flex-grow: 1;">
                                {{ Str::limit($product->description, 80) }}
                            </p>

                            <div style="display: flex; align-items: center; justify-content: space-between; margin-top: auto;">
                                <div>
                                    @if($product->is_on_sale)
                                        <span style="font-size: 14px; color: var(--vitta-pearl); opacity: 0.5; text-decoration: line-through; margin-right: 8px;">
                                            ${{ number_format($product->base_price, 0, ',', '.') }}
                                        </span>
                                    @endif
                                    <span style="font-size: 24px; color: var(--vitta-gold); font-weight: 700;">
                                        ${{ number_format($product->current_price, 0, ',', '.') }}
                                    </span>
                                </div>

                                <i class="bi bi-arrow-right" style="color: var(--vitta-gold); font-size: 20px;"></i>
                            </div>
                        </div>
                    </a>
                @empty
                    <div style="grid-column: 1 / -1; text-align: center; padding: 48px; color: var(--vitta-pearl); opacity: 0.5;">
                        <i class="bi bi-inbox" style="font-size: 48px; margin-bottom: 16px; display: block;"></i>
                        <p>No hay productos destacados disponibles</p>
                    </div>
                @endforelse

            </div>

        </div>
    </section>

    <div class="golden-line"></div>

    <!-- Categories -->
    <section style="padding: 80px 0;">
        <div class="vitta-container">

            <div style="text-align: center; margin-bottom: 64px;">
                <h2 style="font-size: 48px; color: var(--vitta-gold); margin-bottom: 16px;">
                    Nuestras Colecciones
                </h2>
                <p style="font-size: 16px; color: var(--vitta-pearl); opacity: 0.7;">
                    Explorá cada familia aromática
                </p>
            </div>

            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 24px;">

                @foreach($categories as $category)
                    <a href="#" class="category-card"
                        style="background: linear-gradient(135deg, rgba(26, 26, 26, 0.9), rgba(42, 42, 42, 0.9)); border: 1px solid rgba(212, 175, 55, 0.2); border-radius: 8px; padding: 32px; text-decoration: none; display: flex; flex-direction: column; align-items: center; text-align: center; height: 320px; justify-content: center; position: relative; overflow: hidden;">

                        <div
                            style="position: absolute; inset: 0; background-image: url('{{ $category->image ? asset('storage/' . $category->image) : 'https://via.placeholder.com/400x400/2A2A2A/D4AF37?text=' . urlencode($category->name) }}'); background-size: cover; background-position: center; opacity: 0.3; filter: grayscale(50%);">
                        </div>

                        <div style="position: relative; z-index: 2;">
                            <i class="bi bi-stars"
                                style="font-size: 48px; color: var(--vitta-gold); margin-bottom: 16px; display: block;"></i>
                            <h3 style="font-size: 24px; color: var(--vitta-pearl); margin-bottom: 12px;">
                                {{ $category->name }}
                            </h3>
                            <p style="font-size: 14px; color: var(--vitta-pearl); opacity: 0.7; margin-bottom: 16px;">
                                {{ $category->description }}
                            </p>
                            <span style="color: var(--vitta-gold); font-size: 12px; letter-spacing: 1px;">
                                {{ $category->products_count }} productos
                            </span>
                        </div>
                    </a>
                @endforeach

            </div>

        </div>
    </section>

    <div class="golden-line"></div>

    <!-- Why Choose Us -->
    <section style="padding: 80px 0;">
        <div class="vitta-container">

            <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 48px;">

                <div style="text-align: center;">
                    <div
                        style="width: 80px; height: 80px; border-radius: 50%; border: 2px solid var(--vitta-gold); display: flex; align-items: center; justify-content: center; margin: 0 auto 24px;">
                        <i class="bi bi-gem" style="font-size: 32px; color: var(--vitta-gold);"></i>
                    </div>
                    <h4 style="font-size: 20px; color: var(--vitta-pearl); margin-bottom: 12px;">Calidad Premium</h4>
                    <p style="font-size: 14px; color: var(--vitta-pearl); opacity: 0.7; line-height: 1.6;">
                        Solo ingredientes de la más alta calidad y concentración
                    </p>
                </div>

                <div style="text-align: center;">
                    <div
                        style="width: 80px; height: 80px; border-radius: 50%; border: 2px solid var(--vitta-gold); display: flex; align-items: center; justify-content: center; margin: 0 auto 24px;">
                        <i class="bi bi-truck" style="font-size: 32px; color: var(--vitta-gold);"></i>
                    </div>
                    <h4 style="font-size: 20px; color: var(--vitta-pearl); margin-bottom: 12px;">Envío Gratis</h4>
                    <p style="font-size: 14px; color: var(--vitta-pearl); opacity: 0.7; line-height: 1.6;">
                        En compras mayores a $50.000 a todo el país
                    </p>
                </div>

                <div style="text-align: center;">
                    <div
                        style="width: 80px; height: 80px; border-radius: 50%; border: 2px solid var(--vitta-gold); display: flex; align-items: center; justify-content: center; margin: 0 auto 24px;">
                        <i class="bi bi-shield-check" style="font-size: 32px; color: var(--vitta-gold);"></i>
                    </div>
                    <h4 style="font-size: 20px; color: var(--vitta-pearl); margin-bottom: 12px;">Compra Segura</h4>
                    <p style="font-size: 14px; color: var(--vitta-pearl); opacity: 0.7; line-height: 1.6;">
                        Pagos 100% protegidos con MercadoPago
                    </p>
                </div>

            </div>

        </div>
    </section>

@endsection