<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('ticket_code', 30)->nullable()->unique()->after('order_code');
            $table->timestamp('ticket_issued_at')->nullable()->after('expired_at');
            $table->timestamp('checked_in_at')->nullable()->after('ticket_issued_at');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropUnique(['ticket_code']);
            $table->dropColumn(['ticket_code', 'ticket_issued_at', 'checked_in_at']);
        });
    }
};
