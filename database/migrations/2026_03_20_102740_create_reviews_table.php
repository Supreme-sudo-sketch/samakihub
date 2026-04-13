<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->foreignId('consumer_id')->constrained('users')->cascadeOnDelete();
            $table->tinyInteger('rating')->unsigned();         // 1–5
            $table->text('comment')->nullable();
            $table->timestamps();
            $table->unique(['product_id', 'consumer_id']);    // one review per consumer per product
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
