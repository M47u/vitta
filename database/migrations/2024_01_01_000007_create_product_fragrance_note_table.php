<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void
    {
        Schema::create('product_fragrance_note', function (Blueprint $table) {
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->foreignId('fragrance_note_id')->constrained()->cascadeOnDelete();

            $table->primary(['product_id', 'fragrance_note_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_fragrance_note');
    }
};