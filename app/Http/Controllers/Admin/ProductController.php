<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Http\Requests\Product\StoreProductRequest;
use App\Http\Requests\Product\UpdateProductRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with(['category', 'variants']);

        if ($request->has('search')) {
            $search = $request->search;
            $query->where('name', 'like', "%{$search}%")
                ->orWhere('sku', 'like', "%{$search}%");
        }

        $products = $query->latest()->paginate(20);

        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('admin.products.create', compact('categories'));
    }

    public function store(StoreProductRequest $request)
    {
        $validated = $request->validated();

        // Generar slug único si no existe
        if (empty($validated['slug'])) {
            $baseSlug = Str::slug($validated['name']);
            $slug = $baseSlug;
            $counter = 1;
            while (Product::where('slug', $slug)->exists()) {
                $slug = $baseSlug . '-' . $counter;
                $counter++;
            }
            $validated['slug'] = $slug;
        }

        // Calcular porcentaje de descuento solo si hay precio base
        if (!empty($validated['discount_price']) && !empty($validated['base_price']) && $validated['base_price'] > 0) {
            $validated['discount_percentage'] = round(
                (($validated['base_price'] - $validated['discount_price']) / $validated['base_price']) * 100
            );
        } else {
            $validated['discount_percentage'] = null;
        }

        // Manejar imágenes
        $images = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('products', 'public');
                $images[] = $path;
            }
        }
        $validated['images'] = $images;

        $product = Product::create($validated);

        // Crear variantes si existen
        if ($request->has('variants')) {
            foreach ($request->variants as $index => $variantData) {
                // Manejar imagen de la variante
                $variantImage = null;
                if ($request->hasFile("variant_images.{$index}")) {
                    $variantImage = $request->file("variant_images.{$index}")->store('variants', 'public');
                }

                $product->variants()->create([
                    'name' => $variantData['name'],
                    'sku' => $variantData['sku'],
                    'ml_size' => $variantData['ml_size'],
                    'price' => $variantData['price'],
                    'stock' => $variantData['stock'] ?? 0,
                    'min_stock' => $variantData['min_stock'] ?? 5,
                    'is_active' => isset($variantData['is_active']) ? true : false,
                    'image' => $variantImage,
                ]);
            }
        }

        return redirect()->route('admin.products.index')
            ->with('success', 'Producto creado exitosamente con ' . ($request->has('variants') ? count($request->variants) : 0) . ' presentaciones');
    }

    public function edit(Product $product)
    {
        $categories = Category::all();
        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function update(UpdateProductRequest $request, Product $product)
    {
        $validated = $request->validated();

        // Actualizar slug único si cambia el nombre
        if ($validated['name'] !== $product->name && empty($validated['slug'])) {
            $baseSlug = Str::slug($validated['name']);
            $slug = $baseSlug;
            $counter = 1;
            while (Product::where('slug', $slug)->where('id', '!=', $product->id)->exists()) {
                $slug = $baseSlug . '-' . $counter;
                $counter++;
            }
            $validated['slug'] = $slug;
        }

        // Calcular porcentaje de descuento solo si hay precio base
        if (!empty($validated['discount_price']) && !empty($validated['base_price']) && $validated['base_price'] > 0) {
            $validated['discount_percentage'] = round(
                (($validated['base_price'] - $validated['discount_price']) / $validated['base_price']) * 100
            );
        } else {
            $validated['discount_percentage'] = null;
        }

        // Manejar imágenes
        $images = $request->input('existing_images', []);

        // Eliminar imágenes que no están en existing_images
        if ($product->images) {
            foreach ($product->images as $oldImage) {
                if (!in_array($oldImage, $images)) {
                    Storage::disk('public')->delete($oldImage);
                }
            }
        }

        // Agregar nuevas imágenes
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('products', 'public');
                $images[] = $path;
            }
        }

        $validated['images'] = array_values($images);

        $product->update($validated);

        // Actualizar variantes existentes
        if ($request->has('existing_variants')) {
            foreach ($request->existing_variants as $variantId => $variantData) {
                $variant = $product->variants()->find($variantId);
                if ($variant) {
                    // Manejar eliminación de imagen
                    if (isset($variantData['remove_image']) && $variant->image) {
                        Storage::disk('public')->delete($variant->image);
                        $variant->image = null;
                    }

                    // Manejar nueva imagen
                    if ($request->hasFile("existing_variant_images.{$variantId}")) {
                        // Eliminar imagen anterior si existe
                        if ($variant->image) {
                            Storage::disk('public')->delete($variant->image);
                        }
                        $variant->image = $request->file("existing_variant_images.{$variantId}")->store('variants', 'public');
                    }

                    $variant->update([
                        'name' => $variantData['name'],
                        'sku' => $variantData['sku'],
                        'ml_size' => $variantData['ml_size'],
                        'price' => $variantData['price'],
                        'stock' => $variantData['stock'] ?? 0,
                        'min_stock' => $variantData['min_stock'] ?? 5,
                        'is_active' => isset($variantData['is_active']) ? true : false,
                        'image' => $variant->image,
                    ]);
                }
            }
        }

        // Crear nuevas variantes
        if ($request->has('variants')) {
            foreach ($request->variants as $index => $variantData) {
                // Manejar imagen de la variante
                $variantImage = null;
                if ($request->hasFile("variant_images.{$index}")) {
                    $variantImage = $request->file("variant_images.{$index}")->store('variants', 'public');
                }

                $product->variants()->create([
                    'name' => $variantData['name'],
                    'sku' => $variantData['sku'],
                    'ml_size' => $variantData['ml_size'],
                    'price' => $variantData['price'],
                    'stock' => $variantData['stock'] ?? 0,
                    'min_stock' => $variantData['min_stock'] ?? 5,
                    'is_active' => isset($variantData['is_active']) ? true : false,
                    'image' => $variantImage,
                ]);
            }
        }

        // Eliminar variantes marcadas para eliminación
        if ($request->has('delete_variants')) {
            $product->variants()->whereIn('id', $request->delete_variants)->delete();
        }

        return redirect()->route('admin.products.index')
            ->with('success', 'Producto actualizado exitosamente');
    }

    public function destroy(Product $product)
    {
        // Eliminar imágenes del producto
        if ($product->images) {
            foreach ($product->images as $image) {
                Storage::disk('public')->delete($image);
            }
        }

        $product->delete();

        return redirect()->route('admin.products.index')
            ->with('success', 'Producto eliminado exitosamente');
    }
}