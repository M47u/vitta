<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Setting;

class ConfigureShipping extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'shipping:configure 
                            {--method= : Método de cálculo (mercadoenvios, weight, fixed)}
                            {--base-cost= : Costo base para envíos (hasta 500g)}
                            {--per-kg= : Costo por KG adicional}
                            {--fixed= : Costo fijo de envío}
                            {--free-min= : Mínimo para envío gratis}';

    /**
     * The console command description.
     */
    protected $description = 'Configurar método y costos de envío';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🚚 Configuración de Envíos - Vitta Perfumes');
        $this->newLine();

        // Método de cálculo
        if ($method = $this->option('method')) {
            if (!in_array($method, ['mercadoenvios', 'weight', 'fixed'])) {
                $this->error("Método inválido. Use: mercadoenvios, weight o fixed");
                return 1;
            }
            
            Setting::set('shipping_method', $method);
            $this->info("✅ Método configurado: {$method}");
        }

        // Costo base (para método weight)
        if ($baseCost = $this->option('base-cost')) {
            Setting::set('shipping_base_cost', $baseCost);
            $this->info("✅ Costo base: \${$baseCost}");
        }

        // Costo por KG (para método weight)
        if ($perKg = $this->option('per-kg')) {
            Setting::set('shipping_cost_per_kg', $perKg);
            $this->info("✅ Costo por KG: \${$perKg}");
        }

        // Costo fijo (para método fixed)
        if ($fixed = $this->option('fixed')) {
            Setting::set('shipping_cost', $fixed);
            $this->info("✅ Costo fijo: \${$fixed}");
        }

        // Mínimo para envío gratis
        if ($freeMin = $this->option('free-min')) {
            Setting::set('free_shipping_minimum', $freeMin);
            $this->info("✅ Envío gratis desde: \${$freeMin}");
        }

        // Mostrar configuración actual
        $hasOptions = $this->option('method') || $this->option('base-cost') || 
            $this->option('per-kg') || $this->option('fixed') || 
            $this->option('free-min');
            
        if (!$hasOptions) {
            $this->showCurrentConfig();
        }

        $this->newLine();
        $this->info('✨ Configuración actualizada correctamente');

        return 0;
    }

    /**
     * Mostrar configuración actual
     */
    private function showCurrentConfig()
    {
        $this->newLine();
        $this->info('📋 Configuración Actual:');
        $this->table(
            ['Setting', 'Valor'],
            [
                ['Método de cálculo', Setting::get('shipping_method', 'mercadoenvios')],
                ['Costo base (hasta 500g)', '$' . Setting::get('shipping_base_cost', 1500)],
                ['Costo por KG adicional', '$' . Setting::get('shipping_cost_per_kg', 800)],
                ['Costo fijo', '$' . Setting::get('shipping_cost', 2500)],
                ['Envío gratis desde', '$' . Setting::get('free_shipping_minimum', 50000)],
            ]
        );

        $this->newLine();
        $this->info('💡 Ejemplos de uso:');
        $this->line('  php artisan shipping:configure --method=weight');
        $this->line('  php artisan shipping:configure --base-cost=2000 --per-kg=1000');
        $this->line('  php artisan shipping:configure --free-min=60000');
    }
}
