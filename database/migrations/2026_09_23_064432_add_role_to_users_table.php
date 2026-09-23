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
        Schema::table('users', function (Blueprint $table) {
    $table->enum('role', [
        'admin',
        'customer',
    ])->default('customer')->after('email');

    $table->string('phone', 20)
        ->nullable()
        ->after('role');

    $table->string('avatar')
        ->nullable()
        ->after('phone');

    $table->text('address')
        ->nullable()
        ->after('avatar');

    $table->date('birth_date')
        ->nullable()
        ->after('address');

    $table->enum('gender', [
        'male',
        'female',
    ])->nullable()
      ->after('birth_date');
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
};
