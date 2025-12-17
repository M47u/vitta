@extends('layouts.app')

@section('title', 'Catálogo de Productos - Vitta Perfumes')

@section('content')

    <!-- Header -->
    <section
        style="padding: 48px 0; background: var(--vitta-black-soft); border-bottom: 1px solid rgba(212, 175, 55, 0.2);">
        <div class="vitta-container">
            <h1 style="font-size: 48px; color: var(--vitta-gold); text-align: center; margin-bottom: 16px;">
                Nuestro Catálogo
            </h1>
            <p style="text-align: center; color: var(--vitta-pearl); opacity: 0.7;">
                Descubrí {{ $products->total() }} fragancias únicas
            </p>
        </div>
    </section>

    <!-- Main Content -->
    <section style="padding: 48px 0;">
        <div class="vitta-container">
            <div style="display: grid; grid-template-columns: 280px 1fr; gap: 32px;">

                <!-- Sidebar Filters -->
                <aside>
                    <form method="GET" action="{{ route('products.index') }}" id="filterForm">

                        <!-- Search -->
                        <div style="margin-bottom: 32px;">
                            <label
                                style="display: block; color: var(--vitta-gold); font-weight: 600; margin-bottom: 12px; font-size: 14px; letter-spacing: 0.5px;">
                                BUSCAR
                            </label>
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Nombre, SKU..."
                                style="width: 100%; padding: 12px 16px; background: var(--vitta-black-soft); border: 1px solid rgba(212, 175, 55, 0.3); color: var(--vitta-pearl); border-radius: 4px; font-size: 14px;">
                        </div>

                        <!-- Category -->
                        <div style="margin-bottom: 32px;">
                            <label
                                style="display: block; color: var(--vitta-gold); font-weight: 600; margin-bottom: 12px; font-size: 14px; letter-spacing: 0.5px;">
                                CATEGORÍA
                            </label>
                            <select name="category"
                                style="width: 100%; padding: 12px 16px; background: var(--vitta-black-soft); border: 1px solid rgba(212, 175, 55, 0.3); color: var(--vitta-pearl); border-radius: 4px; font-size: 14px;"
                                onchange="this.form.submit()">
                                <option value="">Todas</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Fragrance Family -->
                        <div style="margin-bottom: 32px;">
                            <label
                                style="display: block; color: var(--vitta-gold); font-weight: 600; margin-bottom: 12px; font-size: 14px; letter-spacing: 0.5px;">
                                FAMILIA AROMÁTICA
                            </label>
                            <select name="family"
                                style="width: 100%; padding: 12px 16px; background: var(--vitta-black-soft); border: 1px solid rgba(212, 175, 55, 0.3); color: var(--vitta-pearl); border-radius: 4px; font-size: 14px;"
                                onchange="this.form.submit()">
                                <option value="">Todas</option>
                                <option value="oriental" {{ request('family') == 'oriental' ? 'selected' : '' }}>Oriental
                                </option>
                                <option value="woody" {{ request('family') == 'woody' ? 'selected' : '' }}>Amaderado</option>
                                <option value="floral" {{ request('family') == 'floral' ? 'selected' : '' }}>Floral</option>
                                <option value="fresh" {{ request('family') == 'fresh' ? 'selected' : '' }}>Fresco</option>
                                <option value="spicy" {{ request('family') == 'spicy' ? 'selected' : '' }}>Especiado</option>
                                <option value="citrus" {{ request('family') == 'citrus' ? 'selected' : '' }}>Cítrico</option>
                            </select>
                        </div>

                        <!-- Price Range -->
                        <div style="margin-bottom: 32px;">
                            <label
                                style="display: block; color: var(--vitta-gold); font-weight: 600; margin-bottom: 12px; font-size: 14px; letter-spacing: 0.5px;">
                                RANGO DE PRECIO
                            </label>
                            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 8px;">
                                <input type="number" name="min_price" value="{{ request('min_price') }}" placeholder="Mín"
                                    style="padding: 12px; background: var(--vitta-black-soft); border: 1px solid rgba(212, 175, 55, 0.3); color: var(--vitta-pearl); border-radius: 4px; font-size: 14px;">
                                <input type="number" name="max_price" value="{{ request('max_price') }}" placeholder="Máx"
                                    style="padding: 12px; background: var(--vitta-black-soft); border: 1px solid rgba(212, 175, 55, 0.3); color: var(--vitta-pearl); border-radius: 4px; font-size: 14px;">
                            </div>
                        </div>

                        <!-- Gender -->
                        <div style="margin-bottom: 32px;">
                            <label
                                style="display: block; color: var(--vitta-gold); font-weight: 600; margin-bottom: 12px; font-size: 14px; letter-spacing: 0.5px;">
                                GÉNERO
                            </label>
                            <div style="display: flex; flex-direction: column; gap: 8px;">
                                <label
                                    style="display: flex; align-items: center; gap: 8px; color: var(--vitta-pearl); font-size: 14px; cursor: pointer;">
                                    <input type="radio" name="gender" value="" {{ !request('gender') ? 'checked' : '' }}
                                        onchange="this.form.submit()">
                                    Todos
                                </label>
                                <label
                                    style="display: flex; align-items: center; gap: 8px; color: var(--vitta-pearl); font-size: 14px; cursor: pointer;">
                                    <input type="radio" name="gender" value="unisex" {{ request('gender') == 'unisex' ? 'checked' : '' }} onchange="this.form.submit()">
                                    Unisex
                                </label>
                                <label
                                    style="display: flex; align-items: center; gap: 8px; color: var(--vitta-pearl); font-size: 14px; cursor: pointer;">
                                    <input type="radio" name="gender" value="masculine" {{ request('gender') == 'masculine' ? 'checked' : '' }} onchange="this.form.submit()">
                                    Masculino
                                </label>
                                <label
                                    style="display: flex; align-items: center; gap: 8px; color: var(--vitta-pearl); font-size: 14px; cursor: pointer;">
                                    <input type="radio" name="gender" value="feminine" {{ request('gender') == 'feminine' ? 'checked' : '' }} onchange="this.form.submit()">
                                    Femenino
                                </label>
                            </div>
                        </div>

                        <!-- Buttons -->
                        <button type="submit" class="btn-gold"
                            style="width: 100%; padding: 12px; font-size: 13px; margin-bottom: 12px;">
                            APLICAR FILTROS
                        </button>
                        <a href="{{ route('products.index') }}"
                            style="display: block; text-align: center; padding: 12px; border: 2px solid rgba(212, 175, 55, 0.3); color: var(--vitta-pearl); text-decoration: none; border-radius: 4px; font-size: 13px; font-weight: 600;">
                            LIMPIAR
                        </a>

                    </form>
                </aside>

                <!-- Products Grid -->
                <div>

                    <!-- Toolbar -->
                    <div
                        style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 32px; padding-bottom: 16px; border-bottom: 1px solid rgba(212, 175, 55, 0.2);">
                        <p style="color: var(--vitta-pearl); font-size: 14px;">
                            <strong>{{ $products->total() }}</strong> productos encontrados
                        </p>

                        <form method="GET" action="{{ route('products.index') }}"
                            style="display: flex; align-items: center; gap: 12px;">
                            @foreach(request()->except('sort') as $key => $value)
                                <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                            @endforeach

                            <label style="color: var(--vitta-pearl); font-size: 14px;">Ordenar:</label>
                            <select name="sort" onchange="this.form.submit()"
                                style="padding: 8px 16px; background: var(--vitta-black-soft); border: 1px solid rgba(212, 175, 55, 0.3); color: var(--vitta-pearl); border-radius: 4px; font-size: 14px;">
                                <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Más recientes
                                </option>
                                <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Precio: menor
                                    a mayor</option>
                                <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Precio:
                                    mayor a menor</option>
                                <option value="popular" {{ request('sort') == 'popular' ? 'selected' : '' }}>Más populares
                                </option>
                            </select>
                        </form>
                    </div>

                    <!-- Products -->
                    <div
                        style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 24px; margin-bottom: 48px;">

                        @forelse($products as $product)
                            <div class="product-card"
                                style="background: var(--vitta-black-soft); border: 1px solid rgba(212, 175, 55, 0.2); border-radius: 8px; overflow: hidden;">

                                <div style="position: relative; overflow: hidden; height: 350px;">
                                    <a href="{{ route('products.show', $product->slug) }}">
                                        <img src="{{ $product->main_image ?? 'https://via.placeholder.com/400x500/1A1A1A/D4AF37?text=Vitta' }}"
                                            alt="{{ $product->name }}"
                                            style="width: 100%; height: 100%; object-fit: cover; transition: transform 0.6s ease;">
                                    </a>

                                    @if($product->is_on_sale)
                                        <span class="badge-new"
                                            style="position: absolute; top: 12px; left: 12px; background: var(--vitta-gold); color: var(--vitta-black);">
                                            -{{ $product->discount_percentage }}%
                                        </span>
                                    @endif
                                </div>

                                <div style="padding: 20px;">
                                    <p
                                        style="font-size: 11px; color: var(--vitta-gold); margin-bottom: 6px; text-transform: uppercase; letter-spacing: 1px;">
                                        {{ $product->category->name }}
                                    </p>
                                    <h3 style="font-size: 18px; color: var(--vitta-pearl); margin-bottom: 8px;">
                                        <a href="{{ route('products.show', $product->slug) }}"
                                            style="text-decoration: none; color: inherit;">
                                            {{ $product->name }}
                                        </a>
                                    </h3>

                                    <div
                                        style="display: flex; justify-content: space-between; align-items: center; margin-top: 16px;">
                                        <div>
                                            <span style="font-size: 22px; font-weight: 700; color: var(--vitta-gold);">
                                                ${{ number_format($product->current_price, 0, ',', '.') }}
                                            </span>
                                            @if($product->is_on_sale)
                                                <span
                                                    style="font-size: 13px; color: var(--vitta-pearl); opacity: 0.4; text-decoration: line-through; margin-left: 6px;">
                                                    ${{ number_format($product->base_price, 0, ',', '.') }}
                                                </span>
                                            @endif
                                        </div>
                                        <a href="{{ route('products.show', $product->slug) }}"
                                            style="color: var(--vitta-gold); font-size: 20px;">
                                            <i class="bi bi-arrow-right-circle"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div
                                style="grid-column: 1 / -1; text-align: center; padding: 64px; color: var(--vitta-pearl); opacity: 0.5;">
                                <i class="bi bi-search" style="font-size: 64px; display: block; margin-bottom: 16px;"></i>
                                <p style="font-size: 18px; margin-bottom: 8px;">No se encontraron productos</p>
                                <p style="font-size: 14px;">Intentá con otros filtros</p>
                            </div>
                        @endforelse

                    </div>

                    <!-- Pagination -->
                    @if($products->hasPages())
                        <div style="display: flex; justify-content: center;">
                            {{ $products->links() }}
                        </div>
                    @endif

                </div>

            </div>
        </div>
    </section>

@endsection