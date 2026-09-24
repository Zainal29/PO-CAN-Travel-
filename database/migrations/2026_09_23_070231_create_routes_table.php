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
       Schema::create('routes', function (Blueprint $table) {
    $table->id();

    $table->foreignId('bus_id')
        ->constrained('buses')
        ->restrictOnDelete();

    $table->string('origin_city', 100);

    $table->string('origin_terminal', 150);

    $table->string('destination_city', 100);

    $table->string('destination_terminal', 150);

    $table->date('departure_date');

    $table->time('departure_time');

    $table->time('estimated_arrival_time');

    $table->decimal('price', 12, 2);

    $table->unsignedInteger('available_seats');

    $table->enum('status', [
        'available',
        'full',
        'cancelled',
    ])->default('available');

    $table->timestamps();
    $table->softDeletes();

    $table->index([
        'origin_city',
        'destination_city',
        'departure_date',
    ]);
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('routes');
    }
};
