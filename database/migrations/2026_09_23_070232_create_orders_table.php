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
        Schema::create('orders', function (Blueprint $table) {
    $table->id();

    $table->foreignId('user_id')
        ->constrained('users')
        ->restrictOnDelete();

    $table->foreignId('route_id')
        ->constrained('routes')
        ->restrictOnDelete();

    $table->string('order_code', 30)
        ->unique();

    $table->unsignedInteger('total_passengers');

    $table->decimal('total_price', 12, 2);

    $table->enum('payment_status', [
        'unpaid',
        'pending',
        'verified',
        'rejected',
    ])->default('unpaid');

    $table->enum('order_status', [
        'pending',
        'confirmed',
        'paid',
        'cancelled',
        'completed',
        'expired',
    ])->default('pending');

    $table->text('cancellation_note')
        ->nullable();

    $table->timestamp('expired_at')
        ->nullable();
      

    $table->timestamps();
    $table->softDeletes();

    $table->index('order_code');
    $table->index('payment_status');
    $table->index('order_status');
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
