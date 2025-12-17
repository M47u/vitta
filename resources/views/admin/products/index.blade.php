@extends('layouts.admin')

@section('header-title', 'Productos')
@section('header-subtitle', 'Gestiona tu catálogo')

@section('content')

    <div style="display: flex; justify-content: space-between; margin-bottom: 24px;">
        <form method="GET" style="flex: 1; max-width: 400px;">
            <input type="text" name="search" placeholder="Buscar productos..." value="{{ request('search') }}"
                class="form-control">
        </form>
        <a href="{{ route('admin.products.create') }}" class="btn-primary">
            <i class="bi bi-plus-circle"></i> Nuevo Producto
        </a>
    </div>

    <div class="card-admin">
        <table class="table-vitta">
            <thead>
                <tr>
                    <th>Producto</th>
                    <th>SKU</th>
                    <th>Categoría</th>
                    <th>Precio</th>
                    <th>Estado</th>
                    <th style="text-align: center;">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($products as $product)
                    <tr>
                        <td>
                            <div style="display: flex; align-items: center; gap: 12px;">
                                <img src="{{ $product->main_image ?? 'https://via.placeholder.com/60' }}"
                                    style="width: 50px; height: 50px; object-fit: cover; border-radius: 4px;">
                                <span style="font-weight: 600;">{{ $product->name }}</span>
                            </div>
                        </td>
                        <td>{{ $product->sku }}</td>
                        <td>{{ $product->category->name }}</td>
                        <td style="color: #D4AF37; font-weight: 700;">${{ number_format($product->current_price, 0, ',', '.') }}
                        </td>
                        <td>
                            @if($product->is_active)
                                <span class="badge badge-success">Activo</span>
                            @else
                                <span class="badge badge-danger">Inactivo</span>
                            @endif
                        </td>
                        <td style="text-align: center;">
                            <a href="{{ route('admin.products.edit', $product) }}" style="color: #D4AF37; margin-right: 12px;">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form action="{{ route('admin.products.destroy', $product) }}" method="POST"
                                style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" onclick="return confirm('¿Eliminar?')"
                                    style="background: none; border: none; color: #ef4444; cursor: pointer;">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="text-align: center; padding: 48px;">No hay productos</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        @if($products->hasPages())
            <div style="padding: 24px;">
                {{ $products->links() }}
            </div>
        @endif
    </div>

@endsection