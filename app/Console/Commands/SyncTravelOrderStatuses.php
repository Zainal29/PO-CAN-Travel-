<?php

namespace App\Console\Commands;

use App\Services\OrderScheduleSyncService;
use Illuminate\Console\Command;

class SyncTravelOrderStatuses extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'travel:sync-statuses';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sinkronkan status tiket: otomatis check-in saat waktu berangkat & otomatis selesai saat waktu tiba.';

    /**
     * Execute the console command.
     */
    public function handle(OrderScheduleSyncService $syncService): int
    {
        $this->info('Memulai sinkronisasi status perjalanan tiket...');

        $updatedCount = $syncService->syncAllActive();

        $this->info("Sinkronisasi selesai. {$updatedCount} order diperbarui sesuai jadwal real-time.");

        return Command::SUCCESS;
    }
}
