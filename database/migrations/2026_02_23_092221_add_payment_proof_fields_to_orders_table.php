<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('payment_proof')->nullable()->after('payment_method');
            $table->timestamp('payment_proof_uploaded_at')->nullable()->after('payment_proof');
            $table->timestamp('payment_confirmed_at')->nullable()->after('payment_proof_uploaded_at');
            $table->timestamp('payment_reminder_sent_at')->nullable()->after('payment_confirmed_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn([
                'payment_proof',
                'payment_proof_uploaded_at',
                'payment_confirmed_at',
                'payment_reminder_sent_at'
            ]);
        });
    }
};
