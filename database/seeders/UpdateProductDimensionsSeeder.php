<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use Illuminate\Support\Facades\Log;

class UpdateProductDimensionsSeeder extends Seeder
{
    /**
     * Actualiza todos los productos existentes con dimensiones estándar de envío
     */
    public function run(): void
    {
        $updated = Product::query()->update([
            'package_width' => 8,
            'package_height' => 12,
            'package_length' => 8,
            'package_weight' => 250,
        ]);

        $this->command->info("✅ {$updated} productos actualizados con dimensiones de envío");
        
        Log::info("Productos actualizados con dimensiones de envío", [
            'count' => $updated,
            'dimensions' => [
                'width' => 8,
                'height' => 12,
                'length' => 8,
                'weight' => 250
            ]
        ]);
    }
}
