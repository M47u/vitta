<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Setting;

return new class extends Migration
{
    public function up(): void
    {
        // Agregar settings para transferencia bancaria
        $settings = [
            [
                'key' => 'bank_transfer_enabled',
                'value' => 'true',
                'type' => 'boolean',
                'group' => 'payment',
                'label' => 'Habilitar Transferencia',
                'description' => 'Habilitar pago por transferencia bancaria',
            ],
            [
                'key' => 'bank_name',
                'value' => 'Banco Galicia',
                'type' => 'text',
                'group' => 'payment',
                'label' => 'Nombre del Banco',
                'description' => 'Nombre del banco para transferencias',
            ],
            [
                'key' => 'bank_account_type',
                'value' => 'Cuenta Corriente',
                'type' => 'text',
                'group' => 'payment',
                'label' => 'Tipo de Cuenta',
                'description' => 'Tipo de cuenta bancaria (CA/CC)',
            ],
            [
                'key' => 'bank_account_number',
                'value' => '1234567890',
                'type' => 'text',
                'group' => 'payment',
                'label' => 'Número de Cuenta',
                'description' => 'Número de cuenta bancaria',
            ],
            [
                'key' => 'bank_cbu',
                'value' => '0070123430000012345678',
                'type' => 'text',
                'group' => 'payment',
                'label' => 'CBU',
                'description' => 'CBU de la cuenta bancaria',
            ],
            [
                'key' => 'bank_alias',
                'value' => 'VITTA.PERFUMES',
                'type' => 'text',
                'group' => 'payment',
                'label' => 'Alias CBU',
                'description' => 'Alias de la cuenta bancaria',
            ],
            [
                'key' => 'bank_holder',
                'value' => 'Vitta Perfumes SA',
                'type' => 'text',
                'group' => 'payment',
                'label' => 'Titular',
                'description' => 'Titular de la cuenta bancaria',
            ],
            [
                'key' => 'bank_cuit',
                'value' => '30-12345678-9',
                'type' => 'text',
                'group' => 'payment',
                'label' => 'CUIT',
                'description' => 'CUIT/CUIL del titular',
            ],
        ];

        foreach ($settings as $setting) {
            Setting::updateOrCreate(
                ['key' => $setting['key']],
                $setting
            );
        }
    }

    public function down(): void
    {
        $keys = [
            'bank_transfer_enabled',
            'bank_name',
            'bank_account_type',
            'bank_account_number',
            'bank_cbu',
            'bank_alias',
            'bank_holder',
            'bank_cuit',
        ];

        Setting::whereIn('key', $keys)->delete();
    }
};
