@extends('layouts.admin')

@section('header-title', 'Nuevo Producto')

@section('content')

    <form action="{{ route('admin.products.store') }}" method="POST">
        @csrf

        <div class="card-admin" style="max-width: 800px;">

            <div class="form-group">
                <label class="form-label">Nombre *</label>
                <input type="text" name="name" class="form-control" required>
            </div>

            <div class="form-group">
                <label class="form-label">Descripción *</label>
                <textarea name="description" class="form-control" rows="3" required></textarea>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                <div class="form-group">
                    <label class="form-label">SKU *</label>
                    <input type="text" name="sku" class="form-control" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Precio Base *</label>
                    <input type="number" name="base_price" class="form-control" step="0.01" required>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Categoría *</label>
                <select name="category_id" class="form-control" required>
                    <option value="">Seleccionar...</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                <div class="form-group">
                    <label class="form-label">Familia Aromática *</label>
                    <select name="fragrance_family" class="form-control" required>
                        <option value="oriental">Oriental</option>
                        <option value="woody">Amaderado</option>
                        <option value="floral">Floral</option>
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">Género *</label>
                    <select name="gender" class="form-control" required>
                        <option value="unisex">Unisex</option>
                        <option value="masculine">Masculino</option>
                        <option value="feminine">Femenino</option>
                    </select>
                </div>
            </div>

            <div style="display: flex; gap: 16px; margin-top: 24px;">
                <button type="submit" class="btn-primary">Crear Producto</button>
                <a href="{{ route('admin.products.index') }}" class="btn-secondary">Cancelar</a>
            </div>

        </div>
    </form>

@endsection