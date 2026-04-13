<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Payment tracking
            $table->enum('payment_status', ['pending', 'paid', 'failed'])->default('pending')->after('status');
            $table->string('mpesa_phone', 20)->nullable()->after('payment_status');
            $table->string('mpesa_receipt_number', 50)->nullable()->after('mpesa_phone');
            $table->string('checkout_request_id', 100)->nullable()->after('mpesa_receipt_number');
            $table->string('merchant_request_id', 100)->nullable()->after('checkout_request_id');
        });

        // Update existing confirmed/in-progress orders to paid so they aren't broken
        DB::table('orders')
            ->whereIn('status', ['confirmed', 'preparing', 'in_transit', 'delivered'])
            ->update(['payment_status' => 'paid']);
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn([
                'payment_status',
                'mpesa_phone',
                'mpesa_receipt_number',
                'checkout_request_id',
                'merchant_request_id',
            ]);
        });
    }
};
