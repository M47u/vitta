@extends('layouts.app')

@section('title', 'Mis Favoritos - Vitta Perfumes')

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
                        Mis Favoritos
                    </h1>
                    <p style="color: var(--vitta-pearl); opacity: 0.7;">
                        Productos que te encantan
                    </p>
                </div>

                <!-- Wishlist Products -->
                @if($products->count() > 0)
                    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 24px;">
                        @foreach($products as $product)
                            <div style="background: var(--vitta-black-soft); border: 1px solid rgba(212, 175, 55, 0.2); border-radius: 8px; overflow: hidden; transition: all 0.3s; position: relative;"
                                 onmouseover="this.style.borderColor='var(--vitta-gold)'; this.style.transform='translateY(-4px)'; this.style.boxShadow='0 12px 32px rgba(0,0,0,0.3)';"
                                 onmouseout="this.style.borderColor='rgba(212, 175, 55, 0.2)'; this.style.transform='translateY(0)'; this.style.boxShadow='none';">
                                
                                <!-- Wishlist Remove Button -->
                                <button onclick="removeFromWishlist({{ $product->id }})"
                                        style="position: absolute; top: 12px; right: 12px; width: 36px; height: 36px; background: rgba(0,0,0,0.8); border: none; border-radius: 50%; display: flex; align-items: center; justify-content: center; cursor: pointer; z-index: 10; transition: all 0.3s;"
                                        onmouseover="this.style.background='#dc3545'; this.style.transform='scale(1.1)';"
                                        onmouseout="this.style.background='rgba(0,0,0,0.8)'; this.style.transform='scale(1)';">
                                    <i class="bi bi-heart-fill" style="color: #dc3545; font-size: 18px;"></i>
                                </button>

                                <!-- Product Image -->
                                <a href="{{ route('products.show', $product->id) }}" style="display: block; overflow: hidden; aspect-ratio: 1; background: var(--vitta-black);">
                                    @if($product->main_image)
                                        <img src="{{ Storage::url($product->main_image) }}" 
                                             alt="{{ $product->name }}"
                                             style="width: 100%; height: 100%; object-fit: cover; transition: transform 0.5s;"
                                             onmouseover="this.style.transform='scale(1.1)'"
                                             onmouseout="this.style.transform='scale(1)'">
                                    @else
                                        <div style="width: 100%; height: 100%; display: flex; align-items: center; justify-content: center;">
                                            <i class="bi bi-image" style="font-size: 48px; color: var(--vitta-gold); opacity: 0.2;"></i>
                                        </div>
                                    @endif
                                </a>

                                <!-- Product Info -->
                                <div style="padding: 20px;">
                                    
                                    <!-- Category Badge -->
                                    <span style="display: inline-block; padding: 4px 10px; background: rgba(212, 175, 55, 0.1); color: var(--vitta-gold); border-radius: 12px; font-size: 11px; font-weight: 600; text-transform: uppercase; margin-bottom: 10px;">
                                        {{ $product->category->name ?? 'Sin categoría' }}
                                    </span>

                                    <!-- Product Name -->
                                    <h3 style="font-size: 17px; font-weight: 600; color: var(--vitta-pearl); margin-bottom: 12px; min-height: 50px; line-height: 1.4;">
                                        <a href="{{ route('products.show', $product->id) }}" 
                                           style="color: inherit; text-decoration: none; transition: color 0.3s;"
                                           onmouseover="this.style.color='var(--vitta-gold)'"
                                           onmouseout="this.style.color='var(--vitta-pearl)'">
                                            {{ $product->name }}
                                        </a>
                                    </h3>

                                    <!-- Price -->
                                    @php
                                        $defaultVariant = $product->variants()
                                            ->where('is_active', true)
                                            ->orderBy('ml_size')
                                            ->first();
                                    @endphp
                                    
                                    @if($defaultVariant)
                                        <div style="margin-bottom: 16px;">
                                            <p style="color: var(--vitta-gold); font-size: 24px; font-weight: 700;">
                                                ${{ number_format($defaultVariant->price, 0, ',', '.') }}
                                            </p>
                                            <p style="color: var(--vitta-pearl); opacity: 0.6; font-size: 13px;">
                                                Presentación: {{ $defaultVariant->ml_size }}ml
                                            </p>
                                        </div>

                                        <!-- Stock Badge -->
                                        @if($defaultVariant->stock > 0)
                                            <span style="display: inline-block; padding: 4px 10px; background: rgba(25, 135, 84, 0.1); color: #198754; border-radius: 12px; font-size: 12px; font-weight: 600; margin-bottom: 16px;">
                                                <i class="bi bi-check-circle-fill"></i> En Stock
                                            </span>
                                        @else
                                            <span style="display: inline-block; padding: 4px 10px; background: rgba(220, 53, 69, 0.1); color: #dc3545; border-radius: 12px; font-size: 12px; font-weight: 600; margin-bottom: 16px;">
                                                <i class="bi bi-x-circle-fill"></i> Sin Stock
                                            </span>
                                        @endif
                                    @endif

                                    <!-- View Button -->
                                    <a href="{{ route('products.show', $product->id) }}"
                                       style="display: block; width: 100%; padding: 10px; text-align: center; background: var(--vitta-gold); color: var(--vitta-black); text-decoration: none; border-radius: 6px; font-weight: 600; transition: all 0.3s;"
                                       onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 4px 12px rgba(212, 175, 55, 0.3)';"
                                       onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none';">
                                        Ver Producto
                                    </a>

                                </div>

                            </div>
                        @endforeach
                    </div>
                @else
                    <!-- Empty State -->
                    <div style="background: var(--vitta-black-soft); border: 1px solid rgba(212, 175, 55, 0.2); border-radius: 8px; padding: 60px 40px; text-align: center;">
                        <div style="width: 80px; height: 80px; margin: 0 auto 24px; background: rgba(212, 175, 55, 0.1); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                            <i class="bi bi-heart" style="font-size: 36px; color: var(--vitta-gold);"></i>
                        </div>
                        <h3 style="font-size: 20px; font-weight: 600; color: var(--vitta-pearl); margin-bottom: 12px;">
                            No tienes favoritos aún
                        </h3>
                        <p style="color: var(--vitta-pearl); opacity: 0.7; margin-bottom: 24px;">
                            Explora nuestro catálogo y guarda tus fragancias preferidas
                        </p>
                        <a href="{{ route('home') }}" 
                           style="display: inline-flex; align-items: center; gap: 8px; padding: 12px 28px; background: var(--vitta-gold); color: var(--vitta-black); text-decoration: none; border-radius: 6px; font-weight: 600; transition: all 0.3s;"
                           onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 4px 12px rgba(212, 175, 55, 0.3)';"
                           onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none';">
                            <i class="bi bi-shop"></i>
                            Explorar Productos
                        </a>
                    </div>
                @endif

            </div>

        </div>
    </div>
</div>

<script>
function removeFromWishlist(productId) {
    if (!confirm('¿Deseas eliminar este producto de tus favoritos?')) {
        return;
    }

    fetch(`/customer/wishlist/${productId}/toggle`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Reload page to update wishlist
            window.location.reload();
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error al actualizar favoritos');
    });
}
</script>
@endsection
