<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Support\Str;

class CatalogProductsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * 
     * Precios calculados: USD → ARS (x1500) → Precio final con 55% ganancia (x1.55)
     */
    public function run(): void
    {
        $products = [
            [
                'name' => 'Rasasi Hawas Ice For Him Eau de Parfum Masculino 100ML',
                'description' => 'Fragancia refrescante y vibrante con notas acuáticas y un toque helado que evoca la frescura del océano',
                'long_description' => 'Hawas Ice de Rasasi es una interpretación fresca y moderna del clásico acuático. Se abre con explosivas notas de manzana, bergamota y limón que dan paso a un corazón de cardamomo, salvia y geranio. La base combina almizcle, ámbar gris y cedro, creando una estela masculina, fresca y sofisticada perfecta para el día a día y ocasiones especiales. Una fragancia que transmite elegancia y frescura en cada aplicación.',
                'sku' => 'RSI-HWI-100',
                'base_price' => 74400.00,
                'category_id' => 5, // Inspiraciones
                'brand' => 'Rasasi',
                'fragrance_family' => 'fresh',
                'gender' => 'masculine',
                'is_featured' => true,
                'is_active' => true,
                'images' => [
                    'https://images.unsplash.com/photo-1541643600914-78b084683601?w=800',
                    'https://images.unsplash.com/photo-1585386959984-a4155224a1ad?w=800'
                ],
                'meta_tags' => ['rasasi', 'hawas ice', 'acuático', 'fresco', 'masculino', 'verano'],
                'package_width' => 8.5,
                'package_height' => 16.0,
                'package_length' => 6.0,
                'package_weight' => 350,
            ],
            [
                'name' => 'Armaf Club de Nuit Impériale Eau de Parfum 105ML',
                'description' => 'Elegancia y sofisticación en una fragancia intensa inspirada en los grandes clásicos de la perfumería de lujo',
                'long_description' => 'Club de Nuit Impériale de Armaf es uno de los perfumes más aclamados en la perfumería árabe. Se abre con notas brillantes de piña, manzana, grosella negra y bergamota. El corazón revela rosa, abedul y pachulí, mientras que la base de almizcle, ámbar gris, vainilla y roble musgoso crea una estela poderosa y duradera. Esta fragancia ofrece una proyección excepcional y una longevidad impresionante, ideal para el hombre que busca destacar con elegancia en cualquier ocasión.',
                'sku' => 'ARM-CDN-105',
                'base_price' => 67425.00,
                'category_id' => 5, // Inspiraciones
                'brand' => 'Armaf',
                'fragrance_family' => 'woody',
                'gender' => 'masculine',
                'is_featured' => true,
                'is_active' => true,
                'images' => [
                    'https://images.unsplash.com/photo-1592945403244-b3fbafd7f539?w=800',
                    'https://images.unsplash.com/photo-1594035910387-fea47794261f?w=800'
                ],
                'meta_tags' => ['armaf', 'club de nuit', 'lujo', 'intenso', 'masculino', 'noche'],
                'package_width' => 8.0,
                'package_height' => 15.5,
                'package_length' => 6.5,
                'package_weight' => 380,
            ],
            [
                'name' => 'Armaf Odyssey Mandarin Sky Eau de Parfum 100ML',
                'description' => 'Frescura cítrica vibrante con mandarina jugosa y toques florales para un aroma luminoso y edificante',
                'long_description' => 'Odyssey Mandarin Sky de Armaf es una fragancia unisex que captura la esencia del cielo mediterráneo. Las notas de salida explotan con mandarina brillante, bergamota y limón. El corazón floral de neroli, jazmín y flor de azahar aporta delicadeza y sofisticación, mientras que la base de almizcle blanco, ámbar y madera de cedro proporciona calidez y duración. Perfecta para quienes buscan una fragancia fresca, alegre y versátil que funciona tanto de día como de noche.',
                'sku' => 'ARM-OMS-100',
                'base_price' => 60450.00,
                'category_id' => 6, // Unisex
                'brand' => 'Armaf',
                'fragrance_family' => 'citrus',
                'gender' => 'unisex',
                'is_featured' => false,
                'is_active' => true,
                'images' => [
                    'https://images.unsplash.com/photo-1563170351-be82bc888aa4?w=800',
                    'https://images.unsplash.com/photo-1541643600914-78b084683601?w=800'
                ],
                'meta_tags' => ['armaf', 'odyssey', 'mandarina', 'cítrico', 'unisex', 'fresco'],
                'package_width' => 7.5,
                'package_height' => 15.0,
                'package_length' => 5.5,
                'package_weight' => 320,
            ],
            [
                'name' => 'Lattafa Yara Eau de Parfum Feminino 100ML',
                'description' => 'Dulzura gourmand con orquídea, heliotropo y vainilla para una fragancia femenina irresistible',
                'long_description' => 'Yara de Lattafa es una fragancia gourmand cautivadora que combina notas dulces y florales. Se abre con jugosas notas de frutas tropicales y heliotropo, dando paso a un corazón de orquídea, nardo y azahar que aporta elegancia floral. La base golosa de vainilla, almizcle, sándalo y crema de coco crea una estela dulce, sensual y extremadamente adictiva. Esta fragancia ha conquistado a miles de mujeres por su excelente calidad, proyección y precio accesible. Ideal para quienes aman los perfumes dulces y sensuales.',
                'sku' => 'LAT-YAR-100',
                'base_price' => 53475.00,
                'category_id' => 5, // Inspiraciones
                'brand' => 'Lattafa',
                'fragrance_family' => 'floral',
                'gender' => 'feminine',
                'is_featured' => true,
                'is_active' => true,
                'images' => [
                    'https://images.unsplash.com/photo-1588405748880-12d1d2a59db9?w=800',
                    'https://images.unsplash.com/photo-1523293182086-7651a899d37f?w=800'
                ],
                'meta_tags' => ['lattafa', 'yara', 'gourmand', 'dulce', 'femenino', 'vainilla'],
                'package_width' => 7.0,
                'package_height' => 14.5,
                'package_length' => 5.0,
                'package_weight' => 310,
            ],
            [
                'name' => 'Lattafa Asad Eau de Parfum Masculino 100ML',
                'description' => 'Masculinidad potente con especias orientales, tabaco y oud para el hombre audaz y seguro',
                'long_description' => 'Asad de Lattafa es una fragancia masculina intensa que significa "león" en árabe, reflejando fuerza y valentía. Se abre con especias vibrantes de pimienta negra, cardamomo y azafrán. El corazón revela tabaco, oud ahumado y pachulí, mientras que la base de vainilla, ámbar, almizcle y cuero crea una estela profunda, sensual y extremadamente duradera. Perfecta para el hombre que busca una fragancia con carácter, proyección excepcional y un aroma distintivo que no pasa desapercibido.',
                'sku' => 'LAT-ASD-100',
                'base_price' => 51150.00,
                'category_id' => 1, // Oud Premium
                'brand' => 'Lattafa',
                'fragrance_family' => 'spicy',
                'gender' => 'masculine',
                'is_featured' => true,
                'is_active' => true,
                'images' => [
                    'https://images.unsplash.com/photo-1595425970377-c9703cf48b6d?w=800',
                    'https://images.unsplash.com/photo-1587017539504-67cfbddac569?w=800'
                ],
                'meta_tags' => ['lattafa', 'asad', 'oud', 'especias', 'masculino', 'intenso'],
                'package_width' => 7.5,
                'package_height' => 15.0,
                'package_length' => 5.5,
                'package_weight' => 330,
            ],
            [
                'name' => 'Lattafa Khamrah Eau de Parfum Unissex 100ML',
                'description' => 'Dulzura oriental con dátiles, canela y vainilla en una fragancia unisex cálida y envolvente',
                'long_description' => 'Khamrah de Lattafa es una obra maestra gourmand-oriental que ha conquistado el mundo de la perfumería. Se abre con notas de canela, nuez moscada y bergamota. El corazón combina dátiles, praline, nardo y jazmín, creando una mezcla única y adictiva. La base de vainilla, tonka, benjuí, almizcle, ámbar gris y akigalawood ofrece una profundidad excepcional. Esta fragancia unisex es perfecta para quienes buscan algo diferente, dulce pero sofisticado, con una proyección y duración sobresalientes.',
                'sku' => 'LAT-KHM-100',
                'base_price' => 51150.00,
                'category_id' => 6, // Unisex
                'brand' => 'Lattafa',
                'fragrance_family' => 'oriental',
                'gender' => 'unisex',
                'is_featured' => true,
                'is_active' => true,
                'images' => [
                    'https://images.unsplash.com/photo-1590736969955-71cc94901144?w=800',
                    'https://images.unsplash.com/photo-1615634260167-c8cdede054de?w=800'
                ],
                'meta_tags' => ['lattafa', 'khamrah', 'gourmand', 'oriental', 'unisex', 'dulce'],
                'package_width' => 7.0,
                'package_height' => 14.0,
                'package_length' => 5.0,
                'package_weight' => 315,
            ],
            [
                'name' => 'Armaf Connoisseur Man Eau de Parfum Masculino 100ML',
                'description' => 'Elegancia aromática con lavanda, bergamota y maderas nobles para el conocedor refinado',
                'long_description' => 'Connoisseur Man de Armaf es una fragancia diseñada para el hombre de gusto refinado. Se abre con frescas notas de bergamota, limón y menta que dan paso a un corazón aromático de lavanda, geranio y pimienta rosa. La base amaderada combina vetiver, pachulí, cedro y tonka, creando una fragancia versátil y elegante. Perfecta para uso diario en la oficina o eventos formales, proyecta profesionalismo, sofisticación y buen gusto. Una opción excelente para quienes buscan un aroma clásico y atemporal.',
                'sku' => 'ARM-CNM-100',
                'base_price' => 58125.00,
                'discount_price' => null,
                'discount_percentage' => null,
                'category_id' => 5, // Inspiraciones
                'brand' => 'Armaf',
                'fragrance_family' => 'woody',
                'gender' => 'masculine',
                'is_featured' => false,
                'is_active' => true,
                'images' => [
                    'https://images.unsplash.com/photo-1587017539504-67cfbddac569?w=800',
                    'https://images.unsplash.com/photo-1592945403244-b3fbafd7f539?w=800'
                ],
                'meta_tags' => ['armaf', 'connoisseur', 'elegante', 'aromático', 'masculino', 'oficina'],
                'package_width' => 8.0,
                'package_height' => 15.5,
                'package_length' => 6.0,
                'package_weight' => 340,
            ],
            [
                'name' => 'Al Haramain Amber Oud Gold Edition Extreme',
                'description' => 'Lujo absoluto con ámbar dorado y oud premium en una concentración extrema de aceites',
                'long_description' => 'Amber Oud Gold Edition Extreme de Al Haramain representa la cumbre de la perfumería árabe de lujo. Esta edición extrema combina ámbar dorado de la más alta calidad con oud premium de Camboya. Se abre con notas de azafrán y bergamota, dando paso a un corazón de rosa búlgara, jazmín sambac y oud ahumado. La base exquisita de ámbar, almizcle, pachulí y cuero crea una experiencia olfativa inolvidable. Con una concentración extrema de aceites esenciales, ofrece una proyección poderosa y una duración de más de 12 horas. Una joya para conocedores y amantes del lujo oriental.',
                'sku' => 'AHR-AGE-050',
                'base_price' => 100050.00,
                'discount_price' => null,
                'discount_percentage' => null,
                'category_id' => 1, // Oud Premium
                'brand' => 'Al Haramain',
                'fragrance_family' => 'oriental',
                'gender' => 'unisex',
                'is_featured' => true,
                'is_active' => true,
                'images' => [
                    'https://images.unsplash.com/photo-1541643600914-78b084683601?w=800',
                    'https://images.unsplash.com/photo-1590736969955-71cc94901144?w=800'
                ],
                'meta_tags' => ['al haramain', 'amber oud', 'gold edition', 'premium', 'oud', 'lujo', 'unisex'],
                'package_width' => 9.0,
                'package_height' => 17.0,
                'package_length' => 7.0,
                'package_weight' => 420,
            ],
        ];

        foreach ($products as $productData) {
            // Crear el producto
            $product = Product::create($productData);

            // Crear variante única de 100ml (ya que son productos con tamaño fijo)
            $mlSize = 100;
            
            // Caso especial para Al Haramain que puede tener menos ml
            if (str_contains($product->name, 'Al Haramain')) {
                $mlSize = 50; // Algunos Amber Oud vienen en 50ml o 60ml
            }

            ProductVariant::create([
                'product_id' => $product->id,
                'name' => $mlSize . 'ml',
                'sku' => $product->sku . '-' . $mlSize,
                'ml_size' => $mlSize,
                'price' => $product->base_price,
                'stock' => rand(10, 30),
                'min_stock' => 5,
                'is_active' => true,
            ]);

            echo "✓ Creado: {$product->name} - \${$product->base_price}\n";
        }

        echo "\n✅ Se crearon " . count($products) . " productos exitosamente\n";
        echo "💰 Total invertido estimado: $" . number_format(array_sum(array_map(function($p) {
            return $p['base_price'] / 1.55 / 1500; // Revertir a USD de costo
        }, $products)), 2) . " USD\n";
        echo "💵 Valor total del catálogo: $" . number_format(array_sum(array_column($products, 'base_price')), 2) . " ARS\n";
    }
}
