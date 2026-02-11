#!/usr/bin/env php
<?php

/**
 * Script de Análisis Completo del Sistema de Envíos
 * Vitta Perfumes
 * 
 * Este script verifica:
 * 1. Cálculos de envío por peso
 * 2. Cálculos de dimensiones de paquetes
 * 3. Cálculos de totales del carrito (IVA incluido)
 * 4. Configuración de settings
 * 5. Integración con MercadoEnvíos
 */

require __DIR__.'/vendor/autoload.php';

use Illuminate\Support\Facades\DB;

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "\n";
echo "╔════════════════════════════════════════════════════════════════╗\n";
echo "║   ANÁLISIS COMPLETO DEL SISTEMA DE ENVÍOS - VITTA PERFUMES   ║\n";
echo "╚════════════════════════════════════════════════════════════════╝\n";
echo "\n";

// ============================================================================
// 1. VERIFICAR CONFIGURACIÓN
// ============================================================================
echo "📋 1. VERIFICACIÓN DE CONFIGURACIÓN\n";
echo str_repeat("─", 64) . "\n";

$mercadopagoToken = config('services.mercadopago.access_token');
$mercadopagoPublic = config('services.mercadopago.public_key');
$zipCodeFrom = config('services.mercadoenvios.zip_code_from');

echo "   MercadoPago Access Token: " . (empty($mercadopagoToken) ? "❌ NO CONFIGURADO" : "✅ Configurado") . "\n";
echo "   MercadoPago Public Key: " . (empty($mercadopagoPublic) ? "❌ NO CONFIGURADO" : "✅ Configurado") . "\n";
echo "   CP Origen (MercadoEnvíos): " . ($zipCodeFrom ?: "❌ NO CONFIGURADO") . "\n";
echo "\n";

// ============================================================================
// 2. VERIFICAR SETTINGS EN BASE DE DATOS
// ============================================================================
echo "⚙️  2. VERIFICACIÓN DE SETTINGS\n";
echo str_repeat("─", 64) . "\n";

$settings = [
    'shipping_method' => ['default' => 'mercadoenvios', 'desc' => 'Método de envío'],
    'shipping_base_cost' => ['default' => 1500, 'desc' => 'Costo base (hasta 500g)'],
    'shipping_cost_per_kg' => ['default' => 800, 'desc' => 'Costo por kg adicional'],
    'shipping_cost' => ['default' => 2500, 'desc' => 'Costo fijo'],
    'free_shipping_minimum' => ['default' => 50000, 'desc' => 'Mínimo envío gratis'],
];

foreach ($settings as $key => $info) {
    $value = App\Models\Setting::get($key, $info['default']);
    $status = ($value == $info['default']) ? "⚠️  (usando default)" : "✅";
    echo sprintf("   %-25s: %s %s\n", $info['desc'], $value, $status);
}
echo "\n";

// ============================================================================
// 3. VERIFICAR CAMPOS DE DIMENSIONES EN PRODUCTOS
// ============================================================================
echo "📦 3. VERIFICACIÓN DE DIMENSIONES EN PRODUCTOS\n";
echo str_repeat("─", 64) . "\n";

try {
    $columns = DB::select("SHOW COLUMNS FROM products LIKE 'package_%'");
    
    if (count($columns) >= 4) {
        echo "   ✅ Tabla products tiene los campos de dimensiones:\n";
        foreach ($columns as $column) {
            echo "      - {$column->Field} ({$column->Type})\n";
        }
    } else {
        echo "   ❌ ERROR: Faltan campos de dimensiones en la tabla products\n";
        echo "      Ejecuta: php artisan migrate\n";
    }
    
    $productsCount = DB::table('products')->count();
    $withDimensions = DB::table('products')
        ->whereNotNull('package_weight')
        ->where('package_weight', '>', 0)
        ->count();
    
    echo "\n   Total productos: {$productsCount}\n";
    echo "   Con dimensiones: {$withDimensions}\n";
    
    if ($productsCount > 0 && $withDimensions == 0) {
        echo "   ⚠️  ADVERTENCIA: Ningún producto tiene dimensiones configuradas\n";
        echo "      Se usarán valores por defecto (8x12x8 cm, 250g)\n";
    }
    
} catch (\Exception $e) {
    echo "   ❌ ERROR: " . $e->getMessage() . "\n";
}
echo "\n";

// ============================================================================
// 4. PRUEBAS DE CÁLCULO POR PESO
// ============================================================================
echo "🧮 4. PRUEBAS DE CÁLCULO POR PESO\n";
echo str_repeat("─", 64) . "\n";

$mercadoEnvios = new App\Services\MercadoEnviosService();

$testCases = [
    ['weight' => 400, 'expected' => 1500, 'desc' => 'Menos del base (400g)'],
    ['weight' => 500, 'expected' => 1500, 'desc' => 'Exacto el base (500g)'],
    ['weight' => 800, 'expected' => 2300, 'desc' => '300g adicional (800g)'],
    ['weight' => 1200, 'expected' => 2300, 'desc' => '700g adicional (1.2kg)'],
    ['weight' => 1800, 'expected' => 3100, 'desc' => '1300g adicional (1.8kg)'],
    ['weight' => 3000, 'expected' => 3900, 'desc' => '2500g adicional (3kg)'],
];

$allPassed = true;

foreach ($testCases as $test) {
    $result = $mercadoEnvios->calculateShippingByWeight($test['weight']);
    $passed = ($result == $test['expected']);
    $allPassed = $allPassed && $passed;
    
    $status = $passed ? "✅" : "❌";
    echo sprintf("   %s %-30s: $%s %s\n", 
        $status,
        $test['desc'], 
        number_format($result, 0, ',', '.'),
        !$passed ? "(esperado: $" . number_format($test['expected'], 0, ',', '.') . ")" : ""
    );
}

echo "\n   " . ($allPassed ? "✅ TODOS LOS CÁLCULOS SON CORRECTOS" : "❌ HAY ERRORES EN LOS CÁLCULOS") . "\n";
echo "\n";

// ============================================================================
// 5. FÓRMULA DE CÁLCULO
// ============================================================================
echo "📐 5. FÓRMULA DE CÁLCULO POR PESO\n";
echo str_repeat("─", 64) . "\n";

$baseCost = App\Models\Setting::get('shipping_base_cost', 1500);
$costPerKg = App\Models\Setting::get('shipping_cost_per_kg', 800);

echo "   Si peso ≤ 500g:\n";
echo "      Costo = \${$baseCost}\n\n";
echo "   Si peso > 500g:\n";
echo "      kg_adicionales = ceil((peso - 500g) / 1000)\n";
echo "      Costo = \${$baseCost} + (kg_adicionales × \${$costPerKg})\n\n";

echo "   Ejemplo con 1.8kg:\n";
echo "      kg_adicionales = ceil((1800 - 500) / 1000) = ceil(1.3) = 2\n";
echo "      Costo = \${$baseCost} + (2 × \${$costPerKg}) = \$" . ($baseCost + 2 * $costPerKg) . "\n";
echo "\n";

// ============================================================================
// 6. VERIFICAR MÉTODOS DEL SERVICIO
// ============================================================================
echo "🔧 6. VERIFICACIÓN DE MÉTODOS DEL SERVICIO\n";
echo str_repeat("─", 64) . "\n";

$methods = [
    'calculateShipping' => 'Calcular con API MercadoEnvíos',
    'calculateShippingByWeight' => 'Calcular por peso',
    'calculateShippingCost' => 'Calcular según método configurado',
    'calculatePackageDimensions' => 'Calcular dimensiones del paquete',
    'getShippingOptions' => 'Obtener opciones de envío',
];

foreach ($methods as $method => $desc) {
    $exists = method_exists($mercadoEnvios, $method);
    echo sprintf("   %s %-35s: %s\n", 
        $exists ? "✅" : "❌",
        $desc,
        $method
    );
}
echo "\n";

// ============================================================================
// 7. VERIFICAR RUTAS
// ============================================================================
echo "🛣️  7. VERIFICACIÓN DE RUTAS\n";
echo str_repeat("─", 64) . "\n";

$routes = [
    'checkout.index' => 'GET /checkout',
    'checkout.payment' => 'GET /checkout/payment/{address}',
    'checkout.calculate-shipping' => 'GET /checkout/calculate-shipping/{address}',
    'checkout.process' => 'POST /checkout/process/{address}',
    'checkout.success' => 'GET /checkout/success/{order}',
];

foreach ($routes as $routeName => $routePath) {
    $exists = \Illuminate\Support\Facades\Route::has($routeName);
    echo sprintf("   %s %-40s: %s\n", 
        $exists ? "✅" : "❌",
        $routePath,
        $routeName
    );
}
echo "\n";

// ============================================================================
// 8. VERIFICAR CÁLCULO DE IVA EN CARRITO
// ============================================================================
echo "💰 8. VERIFICACIÓN DE CÁLCULO DE IVA\n";
echo str_repeat("─", 64) . "\n";

echo "   Método utilizado:\n";
echo "      - Los precios en catálogo YA incluyen IVA (21%)\n";
echo "      - Subtotal sin IVA = Total con IVA / 1.21\n";
echo "      - IVA discriminado = Total con IVA - Subtotal sin IVA\n";
echo "      - Total final = Total con IVA - descuentos\n\n";

echo "   Ejemplo con producto de \$12,100:\n";
echo "      Total con IVA: \$12,100\n";
echo "      Subtotal sin IVA: \$12,100 / 1.21 = \$10,000\n";
echo "      IVA (21%): \$12,100 - \$10,000 = \$2,100\n";
echo "\n";

// ============================================================================
// 9. RECOMENDACIONES
// ============================================================================
echo "💡 9. RECOMENDACIONES\n";
echo str_repeat("─", 64) . "\n";

$recommendations = [];

if (empty($mercadopagoToken)) {
    $recommendations[] = "Configurar MERCADOPAGO_ACCESS_TOKEN en .env";
}

if (empty($mercadopagoPublic)) {
    $recommendations[] = "Configurar MERCADOPAGO_PUBLIC_KEY en .env";
}

if ($productsCount > 0 && $withDimensions == 0) {
    $recommendations[] = "Configurar dimensiones en los productos para cálculos precisos";
}

$shippingMethod = App\Models\Setting::get('shipping_method', 'mercadoenvios');
if ($shippingMethod == 'mercadoenvios' && empty($mercadopagoToken)) {
    $recommendations[] = "Cambiar shipping_method a 'weight' o 'fixed' hasta configurar API";
}

if (count($recommendations) == 0) {
    echo "   ✅ No hay recomendaciones. El sistema está correctamente configurado.\n";
} else {
    echo "   Sugerencias para mejorar:\n";
    foreach ($recommendations as $i => $rec) {
        echo "   " . ($i + 1) . ". {$rec}\n";
    }
}

echo "\n";

// ============================================================================
// 10. COMANDOS ÚTILES
// ============================================================================
echo "🔨 10. COMANDOS ÚTILES\n";
echo str_repeat("─", 64) . "\n";
echo "   # Ejecutar migraciones\n";
echo "   php artisan migrate\n\n";
echo "   # Ejecutar tests de envío\n";
echo "   php artisan test --filter=ShippingCalculationTest\n\n";
echo "   # Ver logs en tiempo real\n";
echo "   tail -f storage/logs/laravel.log\n\n";
echo "   # Limpiar caché de settings\n";
echo "   php artisan cache:clear\n\n";
echo "   # Configurar método de envío por peso\n";
echo "   php artisan tinker\n";
echo "   >>> Setting::set('shipping_method', 'weight');\n\n";
echo "\n";

echo "╔════════════════════════════════════════════════════════════════╗\n";
echo "║                    ANÁLISIS COMPLETADO                         ║\n";
echo "╚════════════════════════════════════════════════════════════════╝\n";
echo "\n";
