<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE orders MODIFY COLUMN status ENUM('pending_payment','confirmed','preparing','in_transit','delivered','cancelled') NOT NULL DEFAULT 'confirmed'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE orders MODIFY COLUMN status ENUM('confirmed','preparing','in_transit','delivered','cancelled') NOT NULL DEFAULT 'confirmed'");
    }
};
