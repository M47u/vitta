<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number')->unique();
            $table->foreignId('user_id')->constrained()->restrictOnDelete();
            $table->foreignId('address_id')->nullable()->constrained()->nullOnDelete();
            $table->enum('status', [
                'pending',
                'processing',
                'paid',
                'shipped',
                'delivered',
                'cancelled',
                'refunded'
            ])->default('pending');
            $table->enum('payment_status', [
                'pending',
                'processing',
                'approved',
                'rejected',
                'cancelled',
                'refunded'
            ])->default('pending');
            $table->enum('payment_method', ['mercadopago', 'transfer', 'cash']);
            $table->decimal('subtotal', 12, 2);
            $table->decimal('discount', 12, 2)->default(0);
            $table->decimal('shipping', 12, 2)->default(0);
            $table->decimal('tax', 12, 2)->default(0);
            $table->decimal('total', 12, 2);
            $table->string('coupon_code')->nullable();
            $table->text('notes')->nullable();
            $table->json('shipping_address')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamp('shipped_at')->nullable();
            $table->timestamp('delivered_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('order_number');
            $table->index(['user_id', 'status']);
            $table->index('status');
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};