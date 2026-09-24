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
        Schema::create('buses', function (Blueprint $table) {
    $table->id();

    $table->string('bus_code', 20)->unique();

    $table->string('bus_name');

    $table->enum('bus_type', [
        'economy',
        'executive',
        'vip',
        'super_vip',
    ]);

    $table->string('plate_number', 15)->unique();

    $table->unsignedInteger('total_seats')
        ->default(30);

    $table->json('facilities')
        ->nullable();

    $table->text('description')
        ->nullable();

    $table->string('image')
        ->nullable();

    $table->enum('status', [
        'active',
        'maintenance',
        'inactive',
    ])->default('active');

    $table->timestamps();
    $table->softDeletes();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('buses');
    }
};
