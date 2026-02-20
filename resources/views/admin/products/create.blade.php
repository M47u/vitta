@extends('layouts.admin')

@section('header-title', 'Nuevo Producto')
@section('header-subtitle', 'Agregar producto al catálogo')

@section('content')

    <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div style="display: grid; grid-template-columns: 1fr 350px; gap: 24px;">

            <!-- Columna Principal -->
            <div style="display: flex; flex-direction: column; gap: 24px;">

                <!-- Información Básica -->
                <div class="card-admin">
                    <div class="card-header">
                        <h3 class="card-title">Información Básica</h3>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Nombre del Producto *</label>
                        <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
                        @error('name')
                            <span style="color: #ef4444; font-size: 12px; margin-top: 4px;">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">SKU *</label>
                        <input type="text" name="sku" class="form-control" value="{{ old('sku') }}" required>
                        <small style="color: rgba(248, 245, 240, 0.6); font-size: 12px;">Código único del producto. Ej:
                            VT-OUD-001</small>
                        @error('sku')
                            <span style="color: #ef4444; font-size: 12px; margin-top: 4px;">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Descripción Corta *</label>
                        <textarea name="description" class="form-control" rows="3" required>{{ old('description') }}</textarea>
                        <small style="color: rgba(248, 245, 240, 0.6); font-size: 12px;">Máximo 500 caracteres. Se muestra
                            en listados.</small>
                        @error('description')
                            <span style="color: #ef4444; font-size: 12px; margin-top: 4px;">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Descripción Larga</label>
                        <textarea name="long_description" class="form-control" rows="6">{{ old('long_description') }}</textarea>
                        <small style="color: rgba(248, 245, 240, 0.6); font-size: 12px;">Descripción detallada para la
                            página del producto.</small>
                        @error('long_description')
                            <span style="color: #ef4444; font-size: 12px; margin-top: 4px;">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <!-- Precios -->
                <div class="card-admin">
                    <div class="card-header">
                        <h3 class="card-title">Precios (Solo si NO usas presentaciones)</h3>
                    </div>

                    <div style="background: rgba(212, 175, 55, 0.1); border: 1px solid rgba(212, 175, 55, 0.3); border-radius: 6px; padding: 12px; margin-bottom: 16px;">
                        <p style="color: var(--vitta-gold); font-size: 13px; margin: 0;">
                            <i class="bi bi-info-circle" style="margin-right: 6px;"></i>
                            <strong>Importante:</strong> Estos precios solo se usan si el producto NO tiene presentaciones. Si agregas presentaciones más abajo, cada una tendrá su propio precio.
                        </p>
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                        <div class="form-group">
                            <label class="form-label">Precio Base</label>
                            <input type="number" name="base_price" class="form-control" step="0.01"
                                value="{{ old('base_price', 0) }}" min="0">
                            <small style="color: rgba(248, 245, 240, 0.6); font-size: 12px;">Dejar en 0 si usarás presentaciones</small>
                            @error('base_price')
                                <span style="color: #ef4444; font-size: 12px; margin-top: 4px;">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label class="form-label">Precio con Descuento</label>
                            <input type="number" name="discount_price" class="form-control" step="0.01"
                                value="{{ old('discount_price') }}" min="0">
                            <small style="color: rgba(248, 245, 240, 0.6); font-size: 12px;">Opcional - solo si el producto tiene descuento</small>
                            @error('discount_price')
                                <span style="color: #ef4444; font-size: 12px; margin-top: 4px;">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Imágenes -->
                <div class="card-admin">
                    <div class="card-header">
                        <h3 class="card-title">Imágenes del Producto</h3>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Subir Imágenes</label>
                        <input type="file" name="images[]" class="form-control" accept="image/*" multiple
                            id="imageInput">
                        <small style="color: rgba(248, 245, 240, 0.6); font-size: 12px;">Máximo 5 imágenes. JPG, PNG o
                            WEBP. Máx 2MB cada una.</small>
                        @error('images')
                            <span style="color: #ef4444; font-size: 12px; margin-top: 4px;">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Preview de imágenes -->
                    <div id="imagePreview"
                        style="display: grid; grid-template-columns: repeat(auto-fill, minmax(120px, 1fr)); gap: 12px; margin-top: 16px;">
                    </div>
                </div>

                <!-- Meta Tags -->
                <div class="card-admin">
                    <div class="card-header">
                        <h3 class="card-title">Etiquetas (Tags)</h3>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Meta Tags</label>
                        <input type="text" id="metaTagsInput" class="form-control"
                            placeholder="Escribe y presiona Enter para agregar tags">
                        <small style="color: rgba(248, 245, 240, 0.6); font-size: 12px;">Ej: oud, premium, árabe,
                            intenso</small>

                        <div id="tagsContainer" style="display: flex; flex-wrap: wrap; gap: 8px; margin-top: 12px;">
                        </div>
                    </div>
                </div>

                <!-- Variantes / Presentaciones -->
                <div class="card-admin">
                    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
                        <h3 class="card-title">Presentaciones / Tamaños (Recomendado)</h3>
                        <button type="button" onclick="addVariant()" class="btn-primary"
                            style="padding: 8px 16px; font-size: 12px;">
                            <i class="bi bi-plus-circle"></i> Agregar Presentación
                        </button>
                    </div>

                    <div style="background: rgba(34, 197, 94, 0.1); border: 1px solid rgba(34, 197, 94, 0.3); border-radius: 6px; padding: 12px; margin-bottom: 16px;">
                        <p style="color: #22c55e; font-size: 13px; margin: 0;">
                            <i class="bi bi-lightbulb" style="margin-right: 6px;"></i>
                            <strong>Recomendación:</strong> Agrega las presentaciones del producto aquí (Ej: 50ml, 100ml, 200ml). Cada presentación puede tener su propio precio y descuento.
                        </p>
                    </div>

                    <div id="variantsContainer">
                        <p style="color: rgba(248, 245, 240, 0.6); text-align: center; padding: 20px;">
                            <i class="bi bi-box-seam" style="font-size: 32px; display: block; margin-bottom: 8px; opacity: 0.5;"></i>
                            No hay presentaciones agregadas. Haz clic en "Agregar Presentación" para crear una.
                        </p>
                    </div>
                </div>

            </div>

            <!-- Columna Lateral -->
            <div style="display: flex; flex-direction: column; gap: 24px;">

                <!-- Acciones -->
                <div class="card-admin">
                    <div class="card-header">
                        <h3 class="card-title">Acciones</h3>
                    </div>

                    <div style="display: flex; flex-direction: column; gap: 12px;">
                        <button type="submit" class="btn-primary" style="width: 100%; justify-content: center;">
                            <i class="bi bi-plus-circle"></i> Crear Producto
                        </button>
                        <a href="{{ route('admin.products.index') }}" class="btn-secondary"
                            style="width: 100%; text-align: center;">
                            Cancelar
                        </a>
                    </div>
                </div>

                <!-- Estado -->
                <div class="card-admin">
                    <div class="card-header">
                        <h3 class="card-title">Estado</h3>
                    </div>

                    <div style="display: flex; flex-direction: column; gap: 16px;">
                        <label style="display: flex; align-items: center; gap: 12px; cursor: pointer;">
                            <input type="checkbox" name="is_active" value="1" checked
                                style="width: 18px; height: 18px;">
                            <span style="color: #F8F5F0;">Producto Activo</span>
                        </label>

                        <label style="display: flex; align-items: center; gap: 12px; cursor: pointer;">
                            <input type="checkbox" name="is_featured" value="1" style="width: 18px; height: 18px;">
                            <span style="color: #F8F5F0;">Producto Destacado</span>
                        </label>
                    </div>
                </div>

                <!-- Categorización -->
                <div class="card-admin">
                    <div class="card-header">
                        <h3 class="card-title">Categorización</h3>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Categoría *</label>
                        <select name="category_id" class="form-control" required>
                            <option value="">Seleccionar...</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}"
                                    {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('category_id')
                            <span style="color: #ef4444; font-size: 12px; margin-top: 4px;">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Familia Aromática *</label>
                        <select name="fragrance_family" class="form-control" required>
                            <option value="">Seleccionar...</option>
                            <option value="oriental" {{ old('fragrance_family') == 'oriental' ? 'selected' : '' }}>
                                Oriental</option>
                            <option value="woody" {{ old('fragrance_family') == 'woody' ? 'selected' : '' }}>Amaderado
                            </option>
                            <option value="floral" {{ old('fragrance_family') == 'floral' ? 'selected' : '' }}>Floral
                            </option>
                            <option value="fresh" {{ old('fragrance_family') == 'fresh' ? 'selected' : '' }}>Fresco
                            </option>
                            <option value="spicy" {{ old('fragrance_family') == 'spicy' ? 'selected' : '' }}>Especiado
                            </option>
                            <option value="citrus" {{ old('fragrance_family') == 'citrus' ? 'selected' : '' }}>Cítrico
                            </option>
                        </select>
                        @error('fragrance_family')
                            <span style="color: #ef4444; font-size: 12px; margin-top: 4px;">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Género *</label>
                        <select name="gender" class="form-control" required>
                            <option value="">Seleccionar...</option>
                            <option value="unisex" {{ old('gender') == 'unisex' ? 'selected' : '' }}>Unisex</option>
                            <option value="masculine" {{ old('gender') == 'masculine' ? 'selected' : '' }}>Masculino
                            </option>
                            <option value="feminine" {{ old('gender') == 'feminine' ? 'selected' : '' }}>Femenino
                            </option>
                        </select>
                        @error('gender')
                            <span style="color: #ef4444; font-size: 12px; margin-top: 4px;">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Marca</label>
                        <input type="text" name="brand" class="form-control" value="{{ old('brand', 'Vitta Perfumes') }}">
                        @error('brand')
                            <span style="color: #ef4444; font-size: 12px; margin-top: 4px;">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

            </div>

        </div>
    </form>

@endsection

@push('scripts')
    <script>
        let variantIndex = 0;

        // Preview de imágenes
        document.getElementById('imageInput').addEventListener('change', function(e) {
            const preview = document.getElementById('imagePreview');
            preview.innerHTML = '';

            Array.from(e.target.files).forEach((file, index) => {
                if (file.type.startsWith('image/')) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const div = document.createElement('div');
                        div.style.cssText =
                            'position: relative; border: 2px solid rgba(212, 175, 55, 0.5); border-radius: 8px; overflow: hidden;';
                        div.innerHTML = `
                            <img src="${e.target.result}" style="width: 100%; height: 120px; object-fit: cover;">
                            <div style="position: absolute; bottom: 4px; left: 4px; background: rgba(0,0,0,0.7); color: white; padding: 2px 6px; border-radius: 4px; font-size: 10px;">
                                ${index + 1}
                            </div>
                        `;
                        preview.appendChild(div);
                    };
                    reader.readAsDataURL(file);
                }
            });
        });

        // Sistema de tags
        const tagInput = document.getElementById('metaTagsInput');
        const tagsContainer = document.getElementById('tagsContainer');

        tagInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                const tag = this.value.trim();
                if (tag && !isTagExists(tag)) {
                    addTag(tag);
                    this.value = '';
                }
            }
        });

        function addTag(tag) {
            const tagElement = document.createElement('span');
            tagElement.className = 'tag-item';
            tagElement.setAttribute('data-tag', tag);
            tagElement.style.cssText =
                'background: rgba(212, 175, 55, 0.2); color: #D4AF37; padding: 6px 12px; border-radius: 20px; font-size: 12px; display: flex; align-items: center; gap: 8px;';
            tagElement.innerHTML = `
                ${tag}
                <i class="bi bi-x-circle" onclick="removeTag(this)" style="cursor: pointer;"></i>
                <input type="hidden" name="meta_tags[]" value="${tag}">
            `;
            tagsContainer.appendChild(tagElement);
        }

        function removeTag(element) {
            element.closest('.tag-item').remove();
        }

        function isTagExists(tag) {
            const tags = Array.from(tagsContainer.querySelectorAll('.tag-item'));
            return tags.some(t => t.getAttribute('data-tag') === tag);
        }

        // Sistema de variantes
        function addVariant() {
            const container = document.getElementById('variantsContainer');
            
            // Eliminar mensaje de "no hay presentaciones" si existe
            if (container.querySelector('p')) {
                container.innerHTML = '';
            }

            const variantHtml = `
                <div class="variant-item" data-index="${variantIndex}" style="border: 1px solid rgba(212, 175, 55, 0.3); border-radius: 8px; padding: 16px; margin-bottom: 16px; position: relative; background: rgba(0, 0, 0, 0.3);">
                    <button type="button" onclick="removeVariant(this)" style="position: absolute; top: 12px; right: 12px; background: #ef4444; color: white; border: none; border-radius: 4px; padding: 6px 12px; cursor: pointer; font-size: 12px;">
                        <i class="bi bi-trash"></i> Eliminar
                    </button>

                    <h4 style="color: var(--vitta-gold); font-size: 14px; margin-bottom: 16px;">Presentación #${variantIndex + 1}</h4>

                    <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 12px; margin-bottom: 12px;">
                        <div class="form-group" style="margin-bottom: 0;">
                            <label class="form-label" style="font-size: 13px;">Nombre de la Presentación *</label>
                            <input type="text" name="variants[${variantIndex}][name]" class="form-control" placeholder="Ej: 100ml - Eau de Parfum" required style="font-size: 13px; padding: 8px 12px;">
                        </div>
                        
                        <div class="form-group" style="margin-bottom: 0;">
                            <label class="form-label" style="font-size: 13px;">Tamaño (ml) *</label>
                            <input type="number" name="variants[${variantIndex}][ml_size]" class="form-control" placeholder="100" required style="font-size: 13px; padding: 8px 12px;">
                        </div>
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 12px; margin-bottom: 12px;">
                        <div class="form-group" style="margin-bottom: 0;">
                            <label class="form-label" style="font-size: 13px;">Precio Regular *</label>
                            <input type="number" name="variants[${variantIndex}][price]" class="form-control" step="0.01" placeholder="89999.00" required style="font-size: 13px; padding: 8px 12px;">
                            <small style="color: rgba(248, 245, 240, 0.5); font-size: 11px;">Precio sin descuento</small>
                        </div>

                        <div class="form-group" style="margin-bottom: 0;">
                            <label class="form-label" style="font-size: 13px;">Stock Inicial *</label>
                            <input type="number" name="variants[${variantIndex}][stock]" class="form-control" value="0" style="font-size: 13px; padding: 8px 12px;">
                            <small style="color: rgba(248, 245, 240, 0.5); font-size: 11px;">Unidades disponibles</small>
                        </div>

                        <div class="form-group" style="margin-bottom: 0;">
                            <label class="form-label" style="font-size: 13px;">Stock Mínimo</label>
                            <input type="number" name="variants[${variantIndex}][min_stock]" class="form-control" value="5" style="font-size: 13px; padding: 8px 12px;">
                            <small style="color: rgba(248, 245, 240, 0.5); font-size: 11px;">Alerta de stock bajo</small>
                        </div>
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr; gap: 12px; margin-bottom: 12px;">
                        <div class="form-group" style="margin-bottom: 0;">
                            <label class="form-label" style="font-size: 13px;">SKU (Código único) *</label>
                            <input type="text" name="variants[${variantIndex}][sku]" class="form-control" placeholder="VT-OUD-100" required style="font-size: 13px; padding: 8px 12px;">
                            <small style="color: rgba(248, 245, 240, 0.5); font-size: 11px;">Ejemplo: VT-PRODUCTO-100</small>
                        </div>
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr; gap: 12px; margin-bottom: 12px;">
                        <div class="form-group" style="margin-bottom: 0;">
                            <label class="form-label" style="font-size: 13px;">Imagen de la Presentación (Opcional)</label>
                            <input type="file" name="variant_images[${variantIndex}]" class="form-control" accept="image/jpeg,image/jpg,image/png,image/webp" style="font-size: 13px; padding: 8px 12px;">
                            <small style="color: rgba(248, 245, 240, 0.5); font-size: 11px;">Si no subes una imagen, se usará la imagen del producto</small>
                        </div>
                    </div>

                    <div style="margin-top: 12px;">
                        <label style="display: flex; align-items: center; gap: 8px; cursor: pointer;">
                            <input type="checkbox" name="variants[${variantIndex}][is_active]" value="1" checked style="width: 16px; height: 16px;">
                            <span style="color: #F8F5F0; font-size: 13px;">✓ Presentación activa y visible</span>
                        </label>
                    </div>
                </div>
            `;

            container.insertAdjacentHTML('beforeend', variantHtml);
            variantIndex++;
        }

        function removeVariant(button) {
            const variantItem = button.closest('.variant-item');
            variantItem.remove();

            // Si no quedan variantes, mostrar mensaje
            const container = document.getElementById('variantsContainer');
            if (container.children.length === 0) {
                container.innerHTML = '<p style="color: rgba(248, 245, 240, 0.6); text-align: center; padding: 20px;">No hay presentaciones agregadas.</p>';
            }
        }

        // Agregar una variante por defecto al cargar
        document.addEventListener('DOMContentLoaded', function() {
            // Puedes descomentar esto si quieres una variante por defecto
            // addVariant();
        });
    </script>
@endpush