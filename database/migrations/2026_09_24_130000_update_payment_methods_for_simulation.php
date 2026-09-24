<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE payments MODIFY payment_method ENUM('transfer', 'virtual_account', 'e_wallet', 'cash', 'bca', 'bri', 'qris') NOT NULL");
            DB::statement("UPDATE payments SET payment_method = CASE payment_method WHEN 'transfer' THEN 'bca' WHEN 'virtual_account' THEN 'bri' WHEN 'e_wallet' THEN 'qris' WHEN 'cash' THEN 'bca' ELSE 'bca' END");
            DB::statement("ALTER TABLE payments MODIFY payment_method ENUM('bca', 'bri', 'qris') NOT NULL");
        } elseif (DB::getDriverName() === 'sqlite') {
            DB::statement('PRAGMA foreign_keys = OFF');
            DB::statement('DROP INDEX IF EXISTS payments_order_id_unique');
            Schema::rename('payments', 'payments_legacy');

            Schema::create('payments', function (Blueprint $table) {
                $table->id();
                $table->foreignId('order_id')->unique()->constrained('orders')->cascadeOnDelete();
                $table->string('payment_method');
                $table->string('payment_proof')->nullable();
                $table->string('transaction_id', 100)->nullable();
                $table->decimal('amount', 12, 2);
                $table->string('status')->default('unpaid');
                $table->timestamp('paid_at')->nullable();
                $table->text('notes')->nullable();
                $table->timestamps();
            });

            DB::statement("INSERT INTO payments (id, order_id, payment_method, payment_proof, transaction_id, amount, status, paid_at, notes, created_at, updated_at) SELECT id, order_id, CASE payment_method WHEN 'transfer' THEN 'bca' WHEN 'virtual_account' THEN 'bri' WHEN 'e_wallet' THEN 'qris' WHEN 'cash' THEN 'bca' ELSE 'bca' END, payment_proof, transaction_id, amount, status, paid_at, notes, created_at, updated_at FROM payments_legacy");
            Schema::drop('payments_legacy');
            DB::statement('PRAGMA foreign_keys = ON');
        }
    }

    public function down(): void
    {
        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE payments MODIFY payment_method ENUM('transfer', 'virtual_account', 'e_wallet', 'cash') NOT NULL");
        }
    }
};
