<?php

namespace App\Console\Commands;

use App\Models\Order;
use App\Services\OrderCancellationService;
use Illuminate\Console\Command;

class ExpireOrders extends Command
{
    protected $signature = 'orders:expire';

    protected $description = 'Expire pending orders that have passed their payment deadline';

    public function handle(OrderCancellationService $orderCancellationService): int
    {
        $expiredOrders = Order::query()
            ->where('order_status', 'pending')
            ->whereNotNull('expired_at')
            ->where('expired_at', '<=', now())
            ->orderBy('id')
            ->get();

        if ($expiredOrders->isEmpty()) {
            $this->info('Tidak ada order yang perlu di-expire.');

            return self::SUCCESS;
        }

        $success = 0;
        $failed = 0;

        foreach ($expiredOrders as $order) {
            try {
                $orderCancellationService->expire($order);

                $this->info(
                    "Order {$order->order_code} berhasil di-expire."
                );

                $success++;
            } catch (\Throwable $e) {
                $this->error(
                    "Gagal expire order {$order->order_code}: {$e->getMessage()}"
                );

                report($e);

                $failed++;
            }
        }

        $this->newLine();

        $this->info("Berhasil expire: {$success}");

        if ($failed > 0) {
            $this->warn("Gagal expire: {$failed}");
        }

        return $failed > 0
            ? self::FAILURE
            : self::SUCCESS;
    }
}