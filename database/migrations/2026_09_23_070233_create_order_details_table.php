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
       Schema::create('order_details', function (Blueprint $table) {
    $table->id();

    $table->foreignId('order_id')
        ->constrained('orders')
        ->cascadeOnDelete();

    $table->string('passenger_name');

    $table->string('passenger_phone', 20);

    $table->string('passenger_email')
        ->nullable();

    $table->string('seat_number', 10);

    $table->decimal('price', 12, 2);

    $table->timestamps();

    $table->index([
        'order_id',
        'seat_number',
    ]);
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_details');
    }
};
