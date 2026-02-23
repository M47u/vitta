<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Setting;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            // Shop Settings
            [
                'key' => 'shop_name',
                'value' => 'Vitta Perfumes',
                'type' => 'text',
                'group' => 'general',
                'label' => 'Nombre de la Tienda',
                'description' => 'Nombre que aparecerá en el sitio web',
            ],
            [
                'key' => 'shop_email',
                'value' => 'contacto@vittaperfumes.com',
                'type' => 'text',
                'group' => 'general',
                'label' => 'Email de Contacto',
                'description' => 'Email principal para contacto con clientes',
            ],
            [
                'key' => 'shop_phone',
                'value' => '+54 9 351 123 4567',
                'type' => 'text',
                'group' => 'general',
                'label' => 'Teléfono de Contacto',
                'description' => 'Número de WhatsApp o teléfono principal',
            ],

            // Shipping Settings
            [
                'key' => 'free_shipping_minimum',
                'value' => '50000',
                'type' => 'number',
                'group' => 'shipping',
                'label' => 'Mínimo para Envío Gratis',
                'description' => 'Monto mínimo de compra para acceder a envío gratis (en pesos)',
            ],
            [
                'key' => 'shipping_cost',
                'value' => '2500',
                'type' => 'number',
                'group' => 'shipping',
                'label' => 'Costo de Envío',
                'description' => 'Costo fijo de envío cuando no califica para envío gratis',
            ],
            [
                'key' => 'shipping_method',
                'value' => 'mercadoenvios',
                'type' => 'select',
                'group' => 'shipping',
                'label' => 'Método de Cálculo de Envío',
                'description' => 'mercadoenvios: API MercadoEnvíos, weight: Por peso, fixed: Costo fijo',
            ],
            [
                'key' => 'shipping_base_cost',
                'value' => '1500',
                'type' => 'number',
                'group' => 'shipping',
                'label' => 'Costo Base de Envío (Por Peso)',
                'description' => 'Costo base para cálculo por peso (hasta 500g)',
            ],
            [
                'key' => 'shipping_cost_per_kg',
                'value' => '800',
                'type' => 'number',
                'group' => 'shipping',
                'label' => 'Costo por KG Adicional',
                'description' => 'Costo adicional por cada kg sobre el peso base',
            ],

            // Tax Settings
            [
                'key' => 'tax_rate',
                'value' => '21',
                'type' => 'number',
                'group' => 'shop',
                'label' => 'Tasa de IVA (%)',
                'description' => 'Porcentaje de IVA aplicado a las compras',
            ],

            // Payment Settings
            [
                'key' => 'mercadopago_enabled',
                'value' => 'true',
                'type' => 'boolean',
                'group' => 'payment',
                'label' => 'MercadoPago Habilitado',
                'description' => 'Activar o desactivar pagos con MercadoPago',
            ],

            // Discount Settings
            [
                'key' => 'global_discount',
                'value' => '0',
                'type' => 'number',
                'group' => 'shop',
                'label' => 'Descuento Global (%)',
                'description' => 'Descuento aplicado a todos los productos (0 para desactivar)',
            ],

            // Stock Alerts
            [
                'key' => 'low_stock_threshold',
                'value' => '5',
                'type' => 'number',
                'group' => 'shop',
                'label' => 'Umbral de Stock Bajo',
                'description' => 'Cantidad mínima para mostrar alerta de stock bajo',
            ],

            // Bank Transfer Settings
            [
                'key' => 'bank_name',
                'value' => 'Banco Galicia',
                'type' => 'text',
                'group' => 'payment',
                'label' => 'Nombre del Banco',
                'description' => 'Banco donde se recibirán las transferencias',
            ],
            [
                'key' => 'bank_account_type',
                'value' => 'Cuenta Corriente',
                'type' => 'text',
                'group' => 'payment',
                'label' => 'Tipo de Cuenta',
                'description' => 'Tipo de cuenta bancaria (Cuenta Corriente, Caja de Ahorro, etc.)',
            ],
            [
                'key' => 'bank_holder',
                'value' => 'Vitta Perfumes SA',
                'type' => 'text',
                'group' => 'payment',
                'label' => 'Titular de la Cuenta',
                'description' => 'Nombre del titular de la cuenta bancaria',
            ],
            [
                'key' => 'bank_cuit',
                'value' => '30-12345678-9',
                'type' => 'text',
                'group' => 'payment',
                'label' => 'CUIT/CUIL',
                'description' => 'CUIT o CUIL del titular de la cuenta',
            ],
            [
                'key' => 'bank_cbu',
                'value' => '0070123430800012345678',
                'type' => 'text',
                'group' => 'payment',
                'label' => 'CBU',
                'description' => 'CBU de la cuenta bancaria (22 dígitos)',
            ],
            [
                'key' => 'bank_alias',
                'value' => 'VITTA.PERFUMES',
                'type' => 'text',
                'group' => 'payment',
                'label' => 'Alias',
                'description' => 'Alias de la cuenta bancaria',
            ],
        ];

        foreach ($settings as $setting) {
            Setting::updateOrCreate(
                ['key' => $setting['key']],
                $setting
            );
        }
    }
}
