<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete(); // producer
            $table->string('name');
            $table->text('description')->nullable();
            $table->enum('category', ['freshwater', 'ocean', 'shellfish', 'smoked', 'dried', 'other'])->default('freshwater');
            $table->enum('freshness', ['super_fresh', 'fresh', 'smoked', 'dried'])->default('fresh');
            $table->decimal('price_per_kg', 10, 2);
            $table->decimal('stock_kg', 8, 2)->default(0);
            $table->boolean('is_available')->default(true);
            $table->date('catch_date')->nullable();
            $table->string('image')->nullable(); // relative path in storage/app/public
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
