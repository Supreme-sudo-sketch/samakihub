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
            $table->string('pesapal_phone', 20)->nullable()->after('mpesa_receipt_number');
            $table->string('pesapal_transaction_id', 100)->nullable()->after('pesapal_phone');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['pesapal_phone', 'pesapal_transaction_id']);
        });
    }
};
