<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description');
            $table->text('long_description')->nullable();
            $table->string('sku')->unique();
            $table->decimal('base_price', 12, 2);
            $table->decimal('discount_price', 12, 2)->nullable();
            $table->integer('discount_percentage')->nullable();
            $table->foreignId('category_id')->constrained()->cascadeOnUpdate()->restrictOnDelete();
            $table->string('brand')->default('Vitta Perfumes');
            $table->enum('fragrance_family', ['oriental', 'woody', 'floral', 'fresh', 'spicy', 'citrus']);
            $table->enum('gender', ['unisex', 'masculine', 'feminine']);
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_active')->default(true);
            $table->integer('views_count')->default(0);
            $table->integer('sales_count')->default(0);
            $table->decimal('rating_avg', 3, 2)->default(0);
            $table->integer('rating_count')->default(0);
            $table->json('images')->nullable();
            $table->json('meta_tags')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['slug', 'is_active']);
            $table->index(['category_id', 'is_active']);
            $table->index(['is_featured', 'is_active']);
            $table->index('fragrance_family');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};