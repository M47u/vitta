@extends('layouts.app')

@section('title', $product->name . ' - Vitta Perfumes')

@section('content')

<!-- Breadcrumb -->
<section style="padding: 24px 0; background: var(--vitta-black-soft); border-bottom: 1px solid rgba(212, 175, 55, 0.2);">
    <div class="vitta-container">
        <div style="display: flex; align-items: center; gap: 12px; color: var(--vitta-pearl); font-size: 14px;">
            <a href="{{ route('home') }}" style="color: var(--vitta-pearl); opacity: 0.7; text-decoration: none;">Inicio</a>
            <i class="bi bi-chevron-right" style="font-size: 12px; opacity: 0.5;"></i>
            <a href="{{ route('products.index') }}" style="color: var(--vitta-pearl); opacity: 0.7; text-decoration: none;">Productos</a>
            <i class="bi bi-chevron-right" style="font-size: 12px; opacity: 0.5;"></i>
            <span style="color: var(--vitta-gold);">{{ $product->name }}</span>
        </div>
    </div>
</section>

<!-- Product Detail -->
<section style="padding: 64px 0;">
    <div class="vitta-container">
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 64px; margin-bottom: 80px;">
            
            <!-- Gallery -->
            <div>
                <!-- Main Image -->
                <div style="background: var(--vitta-black-soft); border: 1px solid rgba(212, 175, 55, 0.2); border-radius: 8px; overflow: hidden; margin-bottom: 16px; height: 600px; display: flex; align-items: center; justify-content: center;">
                    <img 
                        id="mainImage"
                        src="{{ $product->main_image ? asset('storage/' . $product->main_image) : 'https://via.placeholder.com/600x750/1A1A1A/D4AF37?text=Vitta+Perfumes' }}" 
                        alt="{{ $product->name }}"
                        style="width: 100%; height: 100%; object-fit: cover;"
                    >
                </div>

                <!-- Thumbnails -->
                @if($product->images && count($product->images) > 1)
                <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 12px;">
                    @foreach($product->images as $image)
                    <img 
                        src="{{ asset('storage/' . $image) }}" 
                        alt="{{ $product->name }}"
                        onclick="changeImage('{{ asset('storage/' . $image) }}')"
                        style="width: 100%; height: 80px; object-fit: cover; border: 2px solid rgba(212, 175, 55, 0.3); border-radius: 4px; cursor: pointer; transition: all 0.3s;"
                        onmouseover="this.style.borderColor='var(--vitta-gold)'"
                        onmouseout="this.style.borderColor='rgba(212, 175, 55, 0.3)'"
                    >
                    @endforeach
                </div>
                @endif
            </div>

            <!-- Product Info -->
            <div>
                
                <!-- Category Badge -->
                <span style="display: inline-block; padding: 6px 16px; background: rgba(212, 175, 55, 0.1); border: 1px solid rgba(212, 175, 55, 0.3); border-radius: 20px; color: var(--vitta-gold); font-size: 12px; font-weight: 600; letter-spacing: 1px; margin-bottom: 16px;">
                    {{ $product->category->name }}
                </span>

                <!-- Title -->
                <h1 style="font-size: 42px; color: var(--vitta-pearl); margin-bottom: 16px; line-height: 1.2;">
                    {{ $product->name }}
                </h1>

                <!-- Price -->
                <div style="margin-bottom: 24px;">
                    <span id="currentPrice" style="font-size: 36px; font-weight: 700; color: var(--vitta-gold);">
                        @if($defaultVariant)
                            ${{ number_format($defaultVariant->price, 0, ',', '.') }}
                        @else
                            ${{ number_format($product->current_price, 0, ',', '.') }}
                        @endif
                    </span>
                    @if($product->is_on_sale && !$defaultVariant)
                    <span style="font-size: 20px; color: var(--vitta-pearl); opacity: 0.4; text-decoration: line-through; margin-left: 12px;">
                        ${{ number_format($product->base_price, 0, ',', '.') }}
                    </span>
                    <span style="display: inline-block; margin-left: 12px; padding: 4px 12px; background: var(--vitta-gold); color: var(--vitta-black); border-radius: 4px; font-size: 13px; font-weight: 700;">
                        -{{ $product->discount_percentage }}% OFF
                    </span>
                    @endif
                </div>

                <div class="golden-line" style="margin: 24px 0;"></div>

                <!-- Description -->
                <p style="font-size: 16px; color: var(--vitta-pearl); line-height: 1.8; margin-bottom: 24px;">
                    {{ $product->description }}
                </p>

                @if($product->long_description)
                <p style="font-size: 14px; color: var(--vitta-pearl); opacity: 0.8; line-height: 1.8; margin-bottom: 32px;">
                    {{ $product->long_description }}
                </p>
                @endif

                <!-- Variants Dropdown -->
                @if($product->variants->count() > 0)
                <div style="margin-bottom: 32px;">
                    <label style="display: block; color: var(--vitta-gold); font-weight: 600; margin-bottom: 12px; font-size: 14px; letter-spacing: 0.5px;">
                        SELECCIONAR PRESENTACIÓN
                    </label>
                    <div style="position: relative;">
                        <select 
                            id="variantSelector"
                            onchange="updateVariant()"
                            style="width: 100%; padding: 18px 20px; background: var(--vitta-black-soft); border: 2px solid rgba(212, 175, 55, 0.3); border-radius: 6px; color: var(--vitta-pearl); font-size: 15px; font-weight: 500; cursor: pointer; appearance: none; transition: all 0.3s;"
                            onfocus="this.style.borderColor='var(--vitta-gold)'"
                            onblur="this.style.borderColor='rgba(212, 175, 55, 0.3)'"
                        >
                            @foreach($product->variants as $variant)
                            <option 
                                value="{{ $variant->id }}"
                                data-price="{{ $variant->price }}"
                                data-stock="{{ $variant->stock }}"
                                data-sku="{{ $variant->sku }}"
                                data-name="{{ $variant->name }}"
                                {{ $loop->first ? 'selected' : '' }}
                                {{ $variant->stock == 0 ? 'disabled' : '' }}
                            >
                                {{ $variant->name }} - ${{ number_format($variant->price, 0, ',', '.') }}
                                @if($variant->stock > 0)
                                    ({{ $variant->stock }} disponibles)
                                @else
                                    (Agotado)
                                @endif
                            </option>
                            @endforeach
                        </select>
                        <i class="bi bi-chevron-down" style="position: absolute; right: 20px; top: 50%; transform: translateY(-50%); color: var(--vitta-gold); pointer-events: none; font-size: 14px;"></i>
                    </div>
                    
                    <!-- Stock Info -->
                    <div id="stockInfo" style="margin-top: 12px; padding: 12px 16px; background: rgba(212, 175, 55, 0.05); border: 1px solid rgba(212, 175, 55, 0.2); border-radius: 6px; display: flex; align-items: center; gap: 10px;">
                        <i class="bi bi-box-seam" style="color: var(--vitta-gold); font-size: 16px;"></i>
                        <div style="flex: 1;">
                            <p style="font-size: 13px; color: var(--vitta-pearl); margin: 0;">
                                <strong>Stock disponible:</strong> 
                                <span id="stockCount" style="color: var(--vitta-gold);">{{ $defaultVariant ? $defaultVariant->stock : 0 }}</span> unidades
                            </p>
                            <p style="font-size: 12px; color: var(--vitta-pearl); opacity: 0.7; margin: 4px 0 0 0;">
                                SKU: <span id="currentSku">{{ $defaultVariant ? $defaultVariant->sku : $product->sku }}</span>
                            </p>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Quantity -->
                <div style="margin-bottom: 32px;">
                    <label style="display: block; color: var(--vitta-gold); font-weight: 600; margin-bottom: 12px; font-size: 14px; letter-spacing: 0.5px;">
                        CANTIDAD
                    </label>
                    <div style="display: flex; align-items: center; gap: 12px;">
                        <button 
                            onclick="changeQuantity(-1)"
                            style="width: 40px; height: 40px; background: var(--vitta-black-soft); border: 1px solid rgba(212, 175, 55, 0.3); color: var(--vitta-gold); font-size: 18px; cursor: pointer; border-radius: 4px;">
                            -
                        </button>
                        <input 
                            type="number" 
                            id="quantity"
                            value="1" 
                            min="1"
                            style="width: 80px; height: 40px; text-align: center; background: var(--vitta-black-soft); border: 1px solid rgba(212, 175, 55, 0.3); color: var(--vitta-pearl); font-size: 16px; font-weight: 600; border-radius: 4px;"
                        >
                        <button 
                            onclick="changeQuantity(1)"
                            style="width: 40px; height: 40px; background: var(--vitta-black-soft); border: 1px solid rgba(212, 175, 55, 0.3); color: var(--vitta-gold); font-size: 18px; cursor: pointer; border-radius: 4px;">
                            +
                        </button>
                    </div>
                </div>

                <!-- Actions -->
                <div style="display: grid; grid-template-columns: 1fr auto; gap: 12px; margin-bottom: 32px;">
                    <button 
                        onclick="addToCart()"
                        class="btn-gold" 
                        style="width: 100%; padding: 18px; font-size: 15px;">
                        <i class="bi bi-bag-plus" style="margin-right: 8px;"></i>
                        AGREGAR AL CARRITO
                    </button>
                    <button 
                        style="width: 56px; height: 56px; background: transparent; border: 2px solid var(--vitta-gold); color: var(--vitta-gold); font-size: 20px; cursor: pointer; border-radius: 4px; transition: all 0.3s;">
                        <i class="bi bi-heart"></i>
                    </button>
                </div>

                <!-- Details -->
                <div style="padding: 24px; background: rgba(212, 175, 55, 0.05); border: 1px solid rgba(212, 175, 55, 0.2); border-radius: 8px;">
                    <div style="display: grid; gap: 16px;">
                        <div style="display: flex; align-items: center; gap: 12px;">
                            <i class="bi bi-box-seam" style="font-size: 20px; color: var(--vitta-gold);"></i>
                            <div>
                                <p style="font-size: 13px; color: var(--vitta-pearl); font-weight: 600;">SKU</p>
                                <p style="font-size: 13px; color: var(--vitta-pearl); opacity: 0.7;">{{ $product->sku }}</p>
                            </div>
                        </div>
                        <div style="display: flex; align-items: center; gap: 12px;">
                            <i class="bi bi-flower1" style="font-size: 20px; color: var(--vitta-gold);"></i>
                            <div>
                                <p style="font-size: 13px; color: var(--vitta-pearl); font-weight: 600;">Familia Aromática</p>
                                <p style="font-size: 13px; color: var(--vitta-pearl); opacity: 0.7; text-transform: capitalize;">{{ $product->fragrance_family }}</p>
                            </div>
                        </div>
                        <div style="display: flex; align-items: center; gap: 12px;">
                            <i class="bi bi-gender-ambiguous" style="font-size: 20px; color: var(--vitta-gold);"></i>
                            <div>
                                <p style="font-size: 13px; color: var(--vitta-pearl); font-weight: 600;">Género</p>
                                <p style="font-size: 13px; color: var(--vitta-pearl); opacity: 0.7; text-transform: capitalize;">{{ $product->gender }}</p>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            
        </div>

        <!-- Fragrance Notes -->
        @if($product->fragranceNotes->count() > 0)
        <div style="margin-bottom: 80px;">
            <h2 style="font-size: 32px; color: var(--vitta-gold); text-align: center; margin-bottom: 48px;">
                Notas Aromáticas
            </h2>
            
            <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 32px;">
                
                <!-- Top Notes -->
                <div style="text-align: center;">
                    <div style="width: 80px; height: 80px; margin: 0 auto 16px; background: rgba(212, 175, 55, 0.1); border: 2px solid rgba(212, 175, 55, 0.3); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                        <i class="bi bi-circle-fill" style="font-size: 12px; color: var(--vitta-gold);"></i>
                    </div>
                    <h4 style="color: var(--vitta-gold); margin-bottom: 16px; font-size: 16px; letter-spacing: 1px;">NOTAS DE SALIDA</h4>
                    @foreach($product->fragranceNotes->where('type', 'top') as $note)
                    <p style="color: var(--vitta-pearl); font-size: 14px; margin-bottom: 8px;">
                        {{ $note->icon }} {{ $note->name }}
                    </p>
                    @endforeach
                </div>

                <!-- Heart Notes -->
                <div style="text-align: center;">
                    <div style="width: 80px; height: 80px; margin: 0 auto 16px; background: rgba(212, 175, 55, 0.1); border: 2px solid rgba(212, 175, 55, 0.3); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                        <i class="bi bi-heart-fill" style="font-size: 24px; color: var(--vitta-gold);"></i>
                    </div>
                    <h4 style="color: var(--vitta-gold); margin-bottom: 16px; font-size: 16px; letter-spacing: 1px;">NOTAS DE CORAZÓN</h4>
                    @foreach($product->fragranceNotes->where('type', 'heart') as $note)
                    <p style="color: var(--vitta-pearl); font-size: 14px; margin-bottom: 8px;">
                        {{ $note->icon }} {{ $note->name }}
                    </p>
                    @endforeach
                </div>

                <!-- Base Notes -->
                <div style="text-align: center;">
                    <div style="width: 80px; height: 80px; margin: 0 auto 16px; background: rgba(212, 175, 55, 0.1); border: 2px solid rgba(212, 175, 55, 0.3); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                        <i class="bi bi-diamond-fill" style="font-size: 24px; color: var(--vitta-gold);"></i>
                    </div>
                    <h4 style="color: var(--vitta-gold); margin-bottom: 16px; font-size: 16px; letter-spacing: 1px;">NOTAS DE FONDO</h4>
                    @foreach($product->fragranceNotes->where('type', 'base') as $note)
                    <p style="color: var(--vitta-pearl); font-size: 14px; margin-bottom: 8px;">
                        {{ $note->icon }} {{ $note->name }}
                    </p>
                    @endforeach
                </div>

            </div>
        </div>
        @endif

        <!-- Related Products -->
        @if($relatedProducts->count() > 0)
        <div>
            <h2 style="font-size: 32px; color: var(--vitta-gold); text-align: center; margin-bottom: 48px;">
                También te puede interesar
            </h2>
            
            <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 24px;">
                @foreach($relatedProducts as $related)
                <div class="product-card" style="background: var(--vitta-black-soft); border: 1px solid rgba(212, 175, 55, 0.2); border-radius: 8px; overflow: hidden;">
                    <div style="height: 280px; overflow: hidden;">
                        <a href="{{ route('products.show', $related->slug) }}">
                            <img src="{{ $related->main_image ? asset('storage/' . $related->main_image) : 'https://via.placeholder.com/300x400/1A1A1A/D4AF37' }}" 
                                 alt="{{ $related->name }}"
                                 style="width: 100%; height: 100%; object-fit: cover; transition: transform 0.6s ease;">
                        </a>
                    </div>
                    <div style="padding: 16px;">
                        <p style="font-size: 11px; color: var(--vitta-gold); margin-bottom: 6px; text-transform: uppercase;">
                            {{ $related->category->name }}
                        </p>
                        <h3 style="font-size: 16px; color: var(--vitta-pearl); margin-bottom: 8px;">
                            <a href="{{ route('products.show', $related->slug) }}" style="text-decoration: none; color: inherit;">
                                {{ $related->name }}
                            </a>
                        </h3>
                        <span style="font-size: 18px; font-weight: 700; color: var(--vitta-gold);">
                            ${{ number_format($related->current_price, 0, ',', '.') }}
                        </span>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

    </div>
</section>

@endsection

@push('scripts')
<script>
// Variants data
const variants = @json($product->variants);

// Change main image
function changeImage(src) {
    document.getElementById('mainImage').src = src;
}

// Update variant information
function updateVariant() {
    const select = document.getElementById('variantSelector');
    const selectedOption = select.options[select.selectedIndex];
    
    const variantId = select.value;
    const price = selectedOption.dataset.price;
    const stock = selectedOption.dataset.stock;
    const sku = selectedOption.dataset.sku;
    const name = selectedOption.dataset.name;
    
    // Update price
    const priceElement = document.getElementById('currentPrice');
    priceElement.textContent = '$' + new Intl.NumberFormat('es-AR').format(price);
    
    // Update stock info
    const stockCountElement = document.getElementById('stockCount');
    const stockInfoElement = document.getElementById('stockInfo');
    const addToCartBtn = document.querySelector('button[onclick="addToCart()"]');
    
    stockCountElement.textContent = stock;
    
    // Update SKU
    document.getElementById('currentSku').textContent = sku;
    
    // Update stock warning and button state
    if (parseInt(stock) === 0) {
        stockInfoElement.style.background = 'rgba(220, 38, 38, 0.1)';
        stockInfoElement.style.borderColor = 'rgba(220, 38, 38, 0.3)';
        stockCountElement.style.color = '#dc2626';
        stockCountElement.textContent = 'Agotado';
        addToCartBtn.disabled = true;
        addToCartBtn.style.opacity = '0.5';
        addToCartBtn.style.cursor = 'not-allowed';
        addToCartBtn.innerHTML = '<i class="bi bi-x-circle" style="margin-right: 8px;"></i>SIN STOCK';
    } else if (parseInt(stock) <= 5) {
        stockInfoElement.style.background = 'rgba(234, 179, 8, 0.1)';
        stockInfoElement.style.borderColor = 'rgba(234, 179, 8, 0.3)';
        stockCountElement.style.color = '#eab308';
        addToCartBtn.disabled = false;
        addToCartBtn.style.opacity = '1';
        addToCartBtn.style.cursor = 'pointer';
        addToCartBtn.innerHTML = '<i class="bi bi-bag-plus" style="margin-right: 8px;"></i>AGREGAR AL CARRITO';
    } else {
        stockInfoElement.style.background = 'rgba(212, 175, 55, 0.05)';
        stockInfoElement.style.borderColor = 'rgba(212, 175, 55, 0.2)';
        stockCountElement.style.color = 'var(--vitta-gold)';
        addToCartBtn.disabled = false;
        addToCartBtn.style.opacity = '1';
        addToCartBtn.style.cursor = 'pointer';
        addToCartBtn.innerHTML = '<i class="bi bi-bag-plus" style="margin-right: 8px;"></i>AGREGAR AL CARRITO';
    }
    
    // Reset quantity to 1 when changing variant
    document.getElementById('quantity').value = 1;
}

// Change quantity
function changeQuantity(delta) {
    const input = document.getElementById('quantity');
    const select = document.getElementById('variantSelector');
    const selectedOption = select.options[select.selectedIndex];
    const maxStock = parseInt(selectedOption.dataset.stock);
    
    const newValue = parseInt(input.value) + delta;
    
    if (newValue >= 1 && newValue <= maxStock) {
        input.value = newValue;
    } else if (newValue > maxStock) {
        alert('Stock máximo disponible: ' + maxStock);
    }
}

// Add to cart
function addToCart() {
    const select = document.getElementById('variantSelector');
    const variantId = select.value;
    const quantity = document.getElementById('quantity').value;
    const productId = {{ $product->id }};
    
    if (!variantId) {
        alert('Por favor selecciona una presentación');
        return;
    }
    
    // Disable button while processing
    const btn = event.target;
    const originalContent = btn.innerHTML;
    btn.disabled = true;
    btn.innerHTML = '<i class="bi bi-arrow-repeat spin" style="margin-right: 8px;"></i>AGREGANDO...';
    
    fetch('{{ route("cart.store") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            product_id: productId,
            variant_id: parseInt(variantId),
            quantity: parseInt(quantity)
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Update cart badge
            const badge = document.querySelector('.cart-badge');
            if (badge) {
                badge.textContent = data.cart_count;
                badge.style.display = data.cart_count > 0 ? 'flex' : 'none';
            } else if (data.cart_count > 0) {
                // Create badge if it doesn't exist
                const cartLink = document.querySelector('a[href="{{ route("cart.index") }}"]');
                if (cartLink) {
                    const newBadge = document.createElement('span');
                    newBadge.className = 'cart-badge';
                    newBadge.textContent = data.cart_count;
                    cartLink.appendChild(newBadge);
                }
            }
            
            // Show success message
            btn.innerHTML = '<i class="bi bi-check-circle" style="margin-right: 8px;"></i>¡AGREGADO!';
            btn.style.background = '#10b981';
            
            setTimeout(() => {
                btn.innerHTML = originalContent;
                btn.style.background = '';
                btn.disabled = false;
            }, 2000);
        } else {
            alert('✗ ' + data.message);
            btn.innerHTML = originalContent;
            btn.disabled = false;
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Ocurrió un error al agregar el producto');
        btn.innerHTML = originalContent;
        btn.disabled = false;
    });
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    // Set initial variant state
    updateVariant();
    
    // Add spin animation for loading state
    const style = document.createElement('style');
    style.textContent = `
        @keyframes spin {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }
        .spin {
            animation: spin 1s linear infinite;
            display: inline-block;
        }
    `;
    document.head.appendChild(style);
});
</script>
@endpush