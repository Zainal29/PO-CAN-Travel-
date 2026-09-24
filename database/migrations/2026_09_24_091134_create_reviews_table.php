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
    Schema::create('reviews', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained()->cascadeOnDelete();
        $table->foreignId('order_id')->constrained()->cascadeOnDelete()->unique(); // 1 order = 1 review
        $table->foreignId('route_id')->constrained()->cascadeOnDelete();
        $table->tinyInteger('rating')->unsigned(); // 1 sampai 5
        $table->text('comment')->nullable();
        $table->timestamps();
        
        // Index untuk mempercepat query rating per route
        $table->index('route_id');
    });
}

public function down(): void
{
    Schema::dropIfExists('reviews');
}
};
