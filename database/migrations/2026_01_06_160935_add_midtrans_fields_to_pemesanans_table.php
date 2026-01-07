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
        Schema::table('pemesanans', function (Blueprint $table) {
            $table->string('gateway')->nullable()->after('status'); // midtrans
            $table->string('gateway_order_id')->nullable()->unique()->after('gateway');
            $table->string('gateway_transaction_id')->nullable()->after('gateway_order_id');
            $table->string('gateway_payment_type')->nullable()->after('gateway_transaction_id');
            $table->timestamp('paid_at')->nullable()->after('gateway_payment_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pemesanans', function (Blueprint $table) {
            $table->dropColumn([
                'gateway',
                'gateway_order_id',
                'gateway_transaction_id',
                'gateway_payment_type',
                'paid_at'
            ]);
        });
    }
};
