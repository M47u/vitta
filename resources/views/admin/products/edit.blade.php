@extends('layouts.admin')

@section('header-title', 'Editar Producto')
@section('header-subtitle', $product->name)

@section('content')

    <form action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

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
                        <input type="text" name="name" class="form-control" value="{{ old('name', $product->name) }}"
                            required>
                        @error('name')
                            <span style="color: #ef4444; font-size: 12px; margin-top: 4px;">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">SKU *</label>
                        <input type="text" name="sku" class="form-control" value="{{ old('sku', $product->sku) }}"
                            required>
                        @error('sku')
                            <span style="color: #ef4444; font-size: 12px; margin-top: 4px;">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Descripción Corta *</label>
                        <textarea name="description" class="form-control" rows="3"
                            required>{{ old('description', $product->description) }}</textarea>
                        <small style="color: rgba(248, 245, 240, 0.6); font-size: 12px;">Máximo 500 caracteres. Se muestra
                            en listados.</small>
                        @error('description')
                            <span style="color: #ef4444; font-size: 12px; margin-top: 4px;">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Descripción Larga</label>
                        <textarea name="long_description" class="form-control" rows="6">{{ old('long_description', $product->long_description) }}</textarea>
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
                        <h3 class="card-title">Precios</h3>
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                        <div class="form-group">
                            <label class="form-label">Precio Base *</label>
                            <input type="number" name="base_price" class="form-control" step="0.01"
                                value="{{ old('base_price', $product->base_price) }}" required>
                            @error('base_price')
                                <span style="color: #ef4444; font-size: 12px; margin-top: 4px;">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label class="form-label">Precio con Descuento</label>
                            <input type="number" name="discount_price" class="form-control" step="0.01"
                                value="{{ old('discount_price', $product->discount_price) }}">
                            <small style="color: rgba(248, 245, 240, 0.6); font-size: 12px;">Dejar vacío si no hay
                                descuento</small>
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

                    <!-- Imágenes existentes -->
                    @if($product->images && count($product->images) > 0)
                        <div style="margin-bottom: 24px;">
                            <label class="form-label">Imágenes Actuales</label>
                            <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(120px, 1fr)); gap: 12px;">
                                @foreach($product->images as $index => $image)
                                    <div style="position: relative; border: 2px solid rgba(212, 175, 55, 0.3); border-radius: 8px; overflow: hidden;">
                                        <img src="{{ asset('storage/' . $image) }}" alt="Imagen {{ $index + 1 }}"
                                            style="width: 100%; height: 120px; object-fit: cover;">
                                        <div style="position: absolute; top: 4px; right: 4px;">
                                            <label style="display: flex; align-items: center; background: rgba(0,0,0,0.7); padding: 4px 8px; border-radius: 4px; cursor: pointer;">
                                                <input type="checkbox" name="existing_images[]" value="{{ $image }}"
                                                    checked style="margin-right: 4px;">
                                                <span style="color: white; font-size: 10px;">Mantener</span>
                                            </label>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <small style="color: rgba(248, 245, 240, 0.6); font-size: 12px; margin-top: 8px; display: block;">
                                Desmarca las imágenes que deseas eliminar
                            </small>
                        </div>
                    @endif

                    <!-- Subir nuevas imágenes -->
                    <div class="form-group">
                        <label class="form-label">Agregar Nuevas Imágenes</label>
                        <input type="file" name="images[]" class="form-control" accept="image/*" multiple
                            id="imageInput">
                        <small style="color: rgba(248, 245, 240, 0.6); font-size: 12px;">Máximo 5 imágenes. JPG, PNG o
                            WEBP. Máx 2MB cada una.</small>
                        @error('images')
                            <span style="color: #ef4444; font-size: 12px; margin-top: 4px;">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Preview de nuevas imágenes -->
                    <div id="imagePreview" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(120px, 1fr)); gap: 12px; margin-top: 16px;"></div>
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
                            @if($product->meta_tags)
                                @foreach($product->meta_tags as $tag)
                                    <span class="tag-item" data-tag="{{ $tag }}"
                                        style="background: rgba(212, 175, 55, 0.2); color: #D4AF37; padding: 6px 12px; border-radius: 20px; font-size: 12px; display: flex; align-items: center; gap: 8px;">
                                        {{ $tag }}
                                        <i class="bi bi-x-circle" onclick="removeTag(this)"
                                            style="cursor: pointer;"></i>
                                        <input type="hidden" name="meta_tags[]" value="{{ $tag }}">
                                    </span>
                                @endforeach
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Variantes / Presentaciones -->
                <div class="card-admin">
                    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
                        <h3 class="card-title">Presentaciones / Tamaños</h3>
                        <button type="button" onclick="addVariant()" class="btn-primary"
                            style="padding: 8px 16px; font-size: 12px;">
                            <i class="bi bi-plus-circle"></i> Agregar Presentación
                        </button>
                    </div>

                    <div id="variantsContainer">
                        @if($product->variants && $product->variants->count() > 0)
                            @foreach($product->variants as $variant)
                                <div class="variant-item" data-id="{{ $variant->id }}"
                                    style="border: 1px solid rgba(212, 175, 55, 0.3); border-radius: 8px; padding: 16px; margin-bottom: 16px; position: relative;">
                                    <input type="hidden" name="existing_variants[{{ $variant->id }}][id]"
                                        value="{{ $variant->id }}">

                                    <button type="button" onclick="removeExistingVariant(this, {{ $variant->id }})"
                                        style="position: absolute; top: 12px; right: 12px; background: #ef4444; color: white; border: none; border-radius: 4px; padding: 4px 8px; cursor: pointer; font-size: 12px;">
                                        <i class="bi bi-trash"></i> Eliminar
                                    </button>

                                    <div style="display: grid; grid-template-columns: 2fr 1fr 1fr; gap: 12px; margin-bottom: 12px;">
                                        <div class="form-group" style="margin-bottom: 0;">
                                            <label class="form-label" style="font-size: 13px;">Nombre *</label>
                                            <input type="text" name="existing_variants[{{ $variant->id }}][name]"
                                                class="form-control" value="{{ $variant->name }}" required
                                                style="font-size: 13px; padding: 8px 12px;">
                                        </div>

                                        <div class="form-group" style="margin-bottom: 0;">
                                            <label class="form-label" style="font-size: 13px;">Tamaño (ml) *</label>
                                            <input type="number" name="existing_variants[{{ $variant->id }}][ml_size]"
                                                class="form-control" value="{{ $variant->ml_size }}" required
                                                style="font-size: 13px; padding: 8px 12px;">
                                        </div>

                                        <div class="form-group" style="margin-bottom: 0;">
                                            <label class="form-label" style="font-size: 13px;">Precio *</label>
                                            <input type="number" name="existing_variants[{{ $variant->id }}][price]"
                                                class="form-control" step="0.01" value="{{ $variant->price }}"
                                                required style="font-size: 13px; padding: 8px 12px;">
                                        </div>
                                    </div>

                                    <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 12px;">
                                        <div class="form-group" style="margin-bottom: 0;">
                                            <label class="form-label" style="font-size: 13px;">SKU *</label>
                                            <input type="text" name="existing_variants[{{ $variant->id }}][sku]"
                                                class="form-control" value="{{ $variant->sku }}" required
                                                style="font-size: 13px; padding: 8px 12px;">
                                        </div>

                                        <div class="form-group" style="margin-bottom: 0;">
                                            <label class="form-label" style="font-size: 13px;">Stock
                                                @if($variant->isLowStock())
                                                    <span style="color: #ef4444; font-size: 11px;">⚠️ BAJO</span>
                                                @endif
                                            </label>
                                            <input type="number" name="existing_variants[{{ $variant->id }}][stock]"
                                                class="form-control" value="{{ $variant->stock }}"
                                                style="font-size: 13px; padding: 8px 12px;">
                                        </div>

                                        <div class="form-group" style="margin-bottom: 0;">
                                            <label class="form-label" style="font-size: 13px;">Stock Mínimo</label>
                                            <input type="number"
                                                name="existing_variants[{{ $variant->id }}][min_stock]"
                                                class="form-control" value="{{ $variant->min_stock }}"
                                                style="font-size: 13px; padding: 8px 12px;">
                                        </div>
                                    </div>

                                    <div style="margin-top: 12px;">
                                        <label style="display: flex; align-items: center; gap: 8px; cursor: pointer;">
                                            <input type="checkbox"
                                                name="existing_variants[{{ $variant->id }}][is_active]" value="1"
                                                {{ $variant->is_active ? 'checked' : '' }}
                                                style="width: 16px; height: 16px;">
                                            <span style="color: #F8F5F0; font-size: 13px;">Presentación activa</span>
                                        </label>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <p style="color: rgba(248, 245, 240, 0.6); text-align: center; padding: 20px;">
                                No hay presentaciones agregadas. Haz clic en "Agregar Presentación" para crear una.
                            </p>
                        @endif
                    </div>

                    <!-- Contenedor para variantes a eliminar -->
                    <div id="variantsToDelete"></div>
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
                            <i class="bi bi-save"></i> Guardar Cambios
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
                            <input type="checkbox" name="is_active" value="1"
                                {{ old('is_active', $product->is_active) ? 'checked' : '' }}
                                style="width: 18px; height: 18px;">
                            <span style="color: #F8F5F0;">Producto Activo</span>
                        </label>

                        <label style="display: flex; align-items: center; gap: 12px; cursor: pointer;">
                            <input type="checkbox" name="is_featured" value="1"
                                {{ old('is_featured', $product->is_featured) ? 'checked' : '' }}
                                style="width: 18px; height: 18px;">
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
                                    {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
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
                            <option value="oriental"
                                {{ old('fragrance_family', $product->fragrance_family) == 'oriental' ? 'selected' : '' }}>
                                Oriental</option>
                            <option value="woody"
                                {{ old('fragrance_family', $product->fragrance_family) == 'woody' ? 'selected' : '' }}>
                                Amaderado</option>
                            <option value="floral"
                                {{ old('fragrance_family', $product->fragrance_family) == 'floral' ? 'selected' : '' }}>
                                Floral</option>
                            <option value="fresh"
                                {{ old('fragrance_family', $product->fragrance_family) == 'fresh' ? 'selected' : '' }}>
                                Fresco</option>
                            <option value="spicy"
                                {{ old('fragrance_family', $product->fragrance_family) == 'spicy' ? 'selected' : '' }}>
                                Especiado</option>
                            <option value="citrus"
                                {{ old('fragrance_family', $product->fragrance_family) == 'citrus' ? 'selected' : '' }}>
                                Cítrico</option>
                        </select>
                        @error('fragrance_family')
                            <span style="color: #ef4444; font-size: 12px; margin-top: 4px;">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Género *</label>
                        <select name="gender" class="form-control" required>
                            <option value="">Seleccionar...</option>
                            <option value="unisex"
                                {{ old('gender', $product->gender) == 'unisex' ? 'selected' : '' }}>Unisex</option>
                            <option value="masculine"
                                {{ old('gender', $product->gender) == 'masculine' ? 'selected' : '' }}>Masculino</option>
                            <option value="feminine"
                                {{ old('gender', $product->gender) == 'feminine' ? 'selected' : '' }}>Femenino</option>
                        </select>
                        @error('gender')
                            <span style="color: #ef4444; font-size: 12px; margin-top: 4px;">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Marca</label>
                        <input type="text" name="brand" class="form-control"
                            value="{{ old('brand', $product->brand) }}">
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
        let variantIndex = 1000; // Empezar con un índice alto para evitar conflictos

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
                                Nueva ${index + 1}
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
            const emptyMessage = container.querySelector('p');
            if (emptyMessage) {
                emptyMessage.remove();
            }

            const variantHtml = `
                <div class="variant-item" data-index="${variantIndex}" style="border: 1px solid rgba(212, 175, 55, 0.3); border-radius: 8px; padding: 16px; margin-bottom: 16px; position: relative;">
                    <button type="button" onclick="removeVariant(this)" style="position: absolute; top: 12px; right: 12px; background: #ef4444; color: white; border: none; border-radius: 4px; padding: 4px 8px; cursor: pointer; font-size: 12px;">
                        <i class="bi bi-trash"></i> Eliminar
                    </button>

                    <div style="display: grid; grid-template-columns: 2fr 1fr 1fr; gap: 12px; margin-bottom: 12px;">
                        <div class="form-group" style="margin-bottom: 0;">
                            <label class="form-label" style="font-size: 13px;">Nombre *</label>
                            <input type="text" name="variants[${variantIndex}][name]" class="form-control" placeholder="Ej: Oud Noir 100ml" required style="font-size: 13px; padding: 8px 12px;">
                        </div>
                        
                        <div class="form-group" style="margin-bottom: 0;">
                            <label class="form-label" style="font-size: 13px;">Tamaño (ml) *</label>
                            <input type="number" name="variants[${variantIndex}][ml_size]" class="form-control" placeholder="100" required style="font-size: 13px; padding: 8px 12px;">
                        </div>

                        <div class="form-group" style="margin-bottom: 0;">
                            <label class="form-label" style="font-size: 13px;">Precio *</label>
                            <input type="number" name="variants[${variantIndex}][price]" class="form-control" step="0.01" placeholder="0.00" required style="font-size: 13px; padding: 8px 12px;">
                        </div>
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 12px;">
                        <div class="form-group" style="margin-bottom: 0;">
                            <label class="form-label" style="font-size: 13px;">SKU *</label>
                            <input type="text" name="variants[${variantIndex}][sku]" class="form-control" placeholder="VT-OUD-100" required style="font-size: 13px; padding: 8px 12px;">
                        </div>

                        <div class="form-group" style="margin-bottom: 0;">
                            <label class="form-label" style="font-size: 13px;">Stock Inicial</label>
                            <input type="number" name="variants[${variantIndex}][stock]" class="form-control" value="0" style="font-size: 13px; padding: 8px 12px;">
                        </div>

                        <div class="form-group" style="margin-bottom: 0;">
                            <label class="form-label" style="font-size: 13px;">Stock Mínimo</label>
                            <input type="number" name="variants[${variantIndex}][min_stock]" class="form-control" value="5" style="font-size: 13px; padding: 8px 12px;">
                        </div>
                    </div>

                    <div style="margin-top: 12px;">
                        <label style="display: flex; align-items: center; gap: 8px; cursor: pointer;">
                            <input type="checkbox" name="variants[${variantIndex}][is_active]" value="1" checked style="width: 16px; height: 16px;">
                            <span style="color: #F8F5F0; font-size: 13px;">Presentación activa</span>
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
            checkEmptyVariants();
        }

        function removeExistingVariant(button, variantId) {
            if (confirm('¿Estás seguro de eliminar esta presentación?')) {
                const variantItem = button.closest('.variant-item');
                variantItem.remove();

                // Agregar ID a la lista de eliminación
                const deleteContainer = document.getElementById('variantsToDelete');
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'delete_variants[]';
                input.value = variantId;
                deleteContainer.appendChild(input);

                checkEmptyVariants();
            }
        }

        function checkEmptyVariants() {
            const container = document.getElementById('variantsContainer');
            if (container.children.length === 0) {
                container.innerHTML =
                    '<p style="color: rgba(248, 245, 240, 0.6); text-align: center; padding: 20px;">No hay presentaciones agregadas.</p>';
            }
        }
    </script>
@endpush
