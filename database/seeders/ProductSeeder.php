<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\FragranceNote;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $products = [
            [
                'name' => 'Oud Noir Intense',
                'description' => 'Fragancia intensa con oud camboyano auténtico y especias orientales',
                'long_description' => 'Una composición cautivadora que combina el oud más puro de Camboya con notas de azafrán y rosa damascena. La evolución revela un corazón especiado con pachulí y vainilla bourbon, creando una estela inolvidable y magnética.',
                'sku' => 'VT-OUD-001',
                'base_price' => 89999.00,
                'discount_price' => 74999.00,
                'discount_percentage' => 17,
                'category_id' => 1,
                'fragrance_family' => 'oriental',
                'gender' => 'unisex',
                'is_featured' => true,
                'images' => ['products/oud-noir-1.jpg', 'products/oud-noir-2.jpg'],
                'meta_tags' => ['oud', 'premium', 'árabe', 'intenso'],
            ],
            [
                'name' => 'Musk Al Haramain',
                'description' => 'Almizcle blanco suave y envolvente con toques florales',
                'long_description' => 'Un almizcle refinado que evoca la elegancia de las fragancias árabes tradicionales. Notas de almizcle blanco se entrelazan con jazmín y vainilla, creando una fragancia limpia, sensual y duradera.',
                'sku' => 'VT-MUSK-001',
                'base_price' => 54999.00,
                'category_id' => 2,
                'fragrance_family' => 'floral',
                'gender' => 'unisex',
                'is_featured' => true,
                'images' => ['products/musk-1.jpg'],
            ],
            [
                'name' => 'Bakhoor Royal',
                'description' => 'Incienso de madera de agar con ámbar y sándalo',
                'long_description' => 'Bakhoor tradicional elaborado con las mejores maderas aromáticas. Perfecto para aromatizar espacios con el lujo de Oriente Medio.',
                'sku' => 'VT-BAK-001',
                'base_price' => 34999.00,
                'category_id' => 3,
                'fragrance_family' => 'woody',
                'gender' => 'unisex',
                'images' => ['products/bakhoor-1.jpg'],
            ],
            [
                'name' => 'Inspo Oud Millésime',
                'description' => 'Inspirado en los grandes clásicos orientales de lujo',
                'long_description' => 'Una reinterpretación de los perfumes más codiciados. Combina oud sintético de alta calidad con especias, cítricos y maderas nobles.',
                'sku' => 'VT-INS-001',
                'base_price' => 64999.00,
                'discount_price' => 49999.00,
                'discount_percentage' => 23,
                'category_id' => 5,
                'fragrance_family' => 'oriental',
                'gender' => 'masculine',
                'is_featured' => true,
                'images' => ['products/inspo-1.jpg'],
            ],
            [
                'name' => 'Aceite Concentrado Sahara Gold',
                'description' => 'Aceite puro sin alcohol, máxima duración',
                'long_description' => 'Aceite concentrado que ofrece hasta 12 horas de duración. Notas de ámbar dorado, incienso y almizcle blanco.',
                'sku' => 'VT-OIL-001',
                'base_price' => 39999.00,
                'category_id' => 4,
                'fragrance_family' => 'oriental',
                'gender' => 'unisex',
                'images' => ['products/oil-1.jpg'],
            ],
        ];

        foreach ($products as $productData) {
            $product = Product::create($productData);

            // Create variants
            $variants = [
                ['name' => '50ml', 'ml_size' => 50, 'price' => $product->base_price * 0.7, 'stock' => 25],
                ['name' => '100ml', 'ml_size' => 100, 'price' => $product->base_price, 'stock' => 15],
                ['name' => '200ml', 'ml_size' => 200, 'price' => $product->base_price * 1.6, 'stock' => 8],
            ];

            foreach ($variants as $variant) {
                ProductVariant::create([
                    'product_id' => $product->id,
                    'name' => $variant['name'],
                    'sku' => $product->sku . '-' . $variant['ml_size'],
                    'ml_size' => $variant['ml_size'],
                    'price' => $variant['price'],
                    'stock' => $variant['stock'],
                    'min_stock' => 5,
                ]);
            }

            // Attach fragrance notes
            $noteIds = FragranceNote::inRandomOrder()->limit(rand(4, 6))->pluck('id');
            $product->fragranceNotes()->attach($noteIds);
        }
    }
}