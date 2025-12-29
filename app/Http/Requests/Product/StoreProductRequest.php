<?php

namespace App\Http\Requests\Product;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:products,slug'],
            'description' => ['required', 'string', 'max:500'],
            'long_description' => ['nullable', 'string', 'max:5000'],
            'sku' => ['required', 'string', 'max:100', 'unique:products,sku'],
            'base_price' => ['required', 'numeric', 'min:0'],
            'discount_price' => ['nullable', 'numeric', 'min:0', 'lt:base_price'],
            'category_id' => ['required', 'exists:categories,id'],
            'brand' => ['nullable', 'string', 'max:255'],
            'fragrance_family' => ['required', Rule::in(['oriental', 'woody', 'floral', 'fresh', 'spicy', 'citrus'])],
            'gender' => ['required', Rule::in(['unisex', 'masculine', 'feminine'])],
            'is_featured' => ['boolean'],
            'is_active' => ['boolean'],
            'images' => ['nullable', 'array', 'max:5'],
            'images.*' => ['image', 'mimes:jpeg,jpg,png,webp', 'max:2048'],
            'meta_tags' => ['nullable', 'array'],
            'meta_tags.*' => ['string', 'max:50'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'El nombre del producto es obligatorio',
            'name.max' => 'El nombre no puede exceder 255 caracteres',
            'description.required' => 'La descripción es obligatoria',
            'description.max' => 'La descripción no puede exceder 500 caracteres',
            'long_description.max' => 'La descripción larga no puede exceder 5000 caracteres',
            'sku.required' => 'El SKU es obligatorio',
            'sku.unique' => 'Este SKU ya está en uso',
            'base_price.required' => 'El precio base es obligatorio',
            'base_price.min' => 'El precio debe ser mayor o igual a 0',
            'discount_price.lt' => 'El precio con descuento debe ser menor al precio base',
            'category_id.required' => 'Debe seleccionar una categoría',
            'category_id.exists' => 'La categoría seleccionada no existe',
            'fragrance_family.required' => 'Debe seleccionar una familia aromática',
            'fragrance_family.in' => 'La familia aromática seleccionada no es válida',
            'gender.required' => 'Debe seleccionar un género',
            'gender.in' => 'El género seleccionado no es válido',
            'images.max' => 'Puede subir máximo 5 imágenes',
            'images.*.image' => 'El archivo debe ser una imagen',
            'images.*.mimes' => 'Las imágenes deben ser JPG, JPEG, PNG o WEBP',
            'images.*.max' => 'Cada imagen no puede exceder 2MB',
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'is_featured' => $this->has('is_featured'),
            'is_active' => $this->has('is_active') ? true : false,
        ]);

        // Si no hay slug, no establecer uno aquí (se generará en el controlador)
        if (!$this->slug && $this->name) {
            // Dejar que el modelo lo genere automáticamente
        }
    }
}
