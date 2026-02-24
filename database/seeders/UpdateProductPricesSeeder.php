<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\ProductVariant;

class UpdateProductPricesSeeder extends Seeder
{
    /**
     * Actualizar precios de productos con 55% de ganancia
     * Cálculo: USD × 1500 (cambio) × 1.55 (55% ganancia)
     */
    public function run(): void
    {
        $priceUpdates = [
            'RSI-HWI-100' => 74400.00,  // 32 USD × 1500 × 1.55
            'ARM-CDN-105' => 67425.00,  // 29 USD × 1500 × 1.55
            'ARM-OMS-100' => 60450.00,  // 26 USD × 1500 × 1.55
            'LAT-YAR-100' => 53475.00,  // 23 USD × 1500 × 1.55
            'LAT-ASD-100' => 51150.00,  // 22 USD × 1500 × 1.55
            'LAT-KHM-100' => 51150.00,  // 22 USD × 1500 × 1.55
            'ARM-CNM-100' => 58125.00,  // 25 USD × 1500 × 1.55 (precio promo)
            'AHR-AGE-050' => 100050.00, // 43 USD × 1500 × 1.55 (precio promo)
        ];

        $updated = 0;
        
        foreach ($priceUpdates as $sku => $newPrice) {
            $product = Product::withTrashed()->where('sku', $sku)->first();
            
            if ($product) {
                // Restaurar si estaba eliminado
                if ($product->trashed()) {
                    $product->restore();
                }
                
                $oldPrice = $product->base_price;
                $product->base_price = $newPrice;
                $product->save();
                
                // Actualizar también la variante
                $variant = ProductVariant::where('product_id', $product->id)->first();
                if ($variant) {
                    $variant->price = $newPrice;
                    $variant->save();
                }
                
                $difference = $newPrice - $oldPrice;
                $updated++;
                
                echo sprintf(
                    "✓ %s: $%s → $%s (%s)\n",
                    $product->name,
                    number_format($oldPrice, 2),
                    number_format($newPrice, 2),
                    $difference < 0 ? number_format($difference, 2) : '+' . number_format($difference, 2)
                );
            }
        }

        echo "\n✅ Se actualizaron $updated productos\n";
        echo "📊 Reducción de margen: 65% → 55% de ganancia\n";
        echo "💵 Nuevo valor del catálogo: $" . number_format(array_sum($priceUpdates), 2) . " ARS\n";
    }
}
