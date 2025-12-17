<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->string('transaction_id')->unique()->nullable();
            $table->string('payment_platform')->default('mercadopago');
            $table->string('payment_method')->nullable();
            $table->enum('status', [
                'pending',
                'processing',
                'approved',
                'rejected',
                'cancelled',
                'refunded'
            ]);
            $table->decimal('amount', 12, 2);
            $table->string('currency', 3)->default('ARS');
            $table->text('gateway_response')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamp('processed_at')->nullable();
            $table->timestamps();

            $table->index('transaction_id');
            $table->index(['order_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
