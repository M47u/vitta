<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // Dimensiones del paquete individual (en cm)
            $table->decimal('package_width', 8, 2)->default(8)->after('images')->comment('Ancho del paquete en cm');
            $table->decimal('package_height', 8, 2)->default(12)->after('package_width')->comment('Alto del paquete en cm');
            $table->decimal('package_length', 8, 2)->default(8)->after('package_height')->comment('Largo del paquete en cm');
            
            // Peso del producto empaquetado (en gramos)
            $table->integer('package_weight')->default(250)->after('package_length')->comment('Peso en gramos (incluye packaging)');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['package_width', 'package_height', 'package_length', 'package_weight']);
        });
    }
};
