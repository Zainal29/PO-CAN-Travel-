<?php

namespace App\Services;

use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class OrderScheduleSyncService
{
    /**
     * Sinkronkan status satu pesanan berdasarkan jadwal perjalanan real-time.
     * - Jika waktu sekarang >= jam tiba: otomatis check-in & tandai order selesai ('completed')
     * - Jika waktu sekarang >= jam berangkat: otomatis check-in ('checked_in_at' terisi)
     */
    public function syncOrder(Order $order): Order
    {
        // Hanya proses jika order sudah dibayar dan memiliki rute
        if ($order->order_status !== 'paid' || ! $order->route) {
            return $order;
        }

        // Pastikan payment terverifikasi (jika relasi payment ada)
        if ($order->relationLoaded('payment') && $order->payment && $order->payment->status !== 'verified') {
            return $order;
        }

        $now = Carbon::now('Asia/Jakarta');

        $depDateStr = $order->route->departure_date instanceof Carbon
            ? $order->route->departure_date->toDateString()
            : Carbon::parse($order->route->departure_date)->toDateString();

        $departureAt = Carbon::parse($depDateStr . ' ' . $order->route->departure_time, 'Asia/Jakarta');
        
        $arrivalTimeStr = $order->route->estimated_arrival_time ?: $order->route->departure_time;
        $arrivalAt = Carbon::parse($depDateStr . ' ' . $arrivalTimeStr, 'Asia/Jakarta');

        if ($arrivalAt->lessThanOrEqualTo($departureAt)) {
            $arrivalAt->addDay();
        }

        $changed = false;

        // Kondisi 1: Waktu sekarang sudah melewati estimasi tiba -> Perjalanan Selesai
        if ($now->greaterThanOrEqualTo($arrivalAt)) {
            if (! $order->checked_in_at) {
                $order->checked_in_at = $departureAt;
                $changed = true;
            }

            $order->order_status = 'completed';
            $changed = true;
        }
        // Kondisi 2: Waktu sekarang sudah melewati jadwal keberangkatan -> Otomatis Check-In
        elseif ($now->greaterThanOrEqualTo($departureAt)) {
            if (! $order->checked_in_at) {
                $order->checked_in_at = $departureAt;
                $changed = true;
            }
        }

        if ($changed) {
            $order->save();
        }

        return $order;
    }

    /**
     * Sinkronkan semua pesanan aktif yang statusnya 'paid'.
     */
    public function syncAllActive(): int
    {
        $orders = Order::query()
            ->where('order_status', 'paid')
            ->whereHas('route')
            ->with(['route', 'payment'])
            ->get();

        $count = 0;
        foreach ($orders as $order) {
            $prevStatus = $order->order_status;
            $prevCheckin = $order->checked_in_at;

            $this->syncOrder($order);

            if ($order->order_status !== $prevStatus || $order->checked_in_at !== $prevCheckin) {
                $count++;
            }
        }

        return $count;
    }

    /**
     * Dapatkan metadata waktu dan fase perjalanan untuk timer dan countdown.
     */
    public function getScheduleContext(Order $order): array
    {
        if (! $order->route) {
            return [
                'phase' => 'unknown',
                'departure_iso' => null,
                'arrival_iso' => null,
                'departure_formatted' => '-',
                'arrival_formatted' => '-',
                'is_checked_in' => false,
                'checked_in_at' => null,
            ];
        }

        $now = Carbon::now('Asia/Jakarta');

        $depDateStr = $order->route->departure_date instanceof Carbon
            ? $order->route->departure_date->toDateString()
            : Carbon::parse($order->route->departure_date)->toDateString();

        $departureAt = Carbon::parse($depDateStr . ' ' . $order->route->departure_time, 'Asia/Jakarta');
        
        $arrivalTimeStr = $order->route->estimated_arrival_time ?: $order->route->departure_time;
        $arrivalAt = Carbon::parse($depDateStr . ' ' . $arrivalTimeStr, 'Asia/Jakarta');

        if ($arrivalAt->lessThanOrEqualTo($departureAt)) {
            $arrivalAt->addDay();
        }

        $phase = 'upcoming'; // Menunggu keberangkatan
        if ($order->order_status === 'completed' || $now->greaterThanOrEqualTo($arrivalAt)) {
            $phase = 'completed'; // Sudah tiba di tujuan
        } elseif ($now->greaterThanOrEqualTo($departureAt)) {
            $phase = 'in_transit'; // Sedang dalam perjalanan
        }

        return [
            'phase' => $phase,
            'now_iso' => $now->toIso8601String(),
            'departure_iso' => $departureAt->toIso8601String(),
            'arrival_iso' => $arrivalAt->toIso8601String(),
            'departure_formatted' => $departureAt->translatedFormat('d M Y, H:i') . ' WIB',
            'arrival_formatted' => $arrivalAt->translatedFormat('d M Y, H:i') . ' WIB',
            'is_checked_in' => (bool) $order->checked_in_at,
            'checked_in_at' => $order->checked_in_at?->translatedFormat('d M Y, H:i') . ' WIB',
        ];
    }
}
