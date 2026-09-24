<?php

namespace App\Services;

use App\Models\Order;
use Illuminate\Support\Facades\DB;

class OrderCancellationService
{
    public function cancel(Order $order, string $note): Order
    {
        return DB::transaction(function () use ($order, $note) {
            $order = Order::query()
                ->lockForUpdate()
                ->with(['route.bus', 'payment'])
                ->findOrFail($order->id);

            if (in_array($order->order_status, [
                'cancelled',
                'completed',
                'expired',
            ], true)) {
                throw new \RuntimeException(
                    'Pesanan tidak dapat dibatalkan pada status saat ini.'
                );
            }

            $this->restoreSeats($order);

            $order->update([
                'order_status' => 'cancelled',
                'cancellation_note' => $note,
            ]);

            if (
                $order->payment &&
                $order->payment->status !== 'verified'
            ) {
                $order->payment->update([
                    'status' => 'rejected',
                    'notes' => $note,
                ]);
            }

            $order->update([
                'payment_status' => $order->payment
                    ? $order->payment->fresh()->status
                    : $order->payment_status,
            ]);

            return $order->fresh([
                'route.bus',
                'payment',
                'details',
            ]);
        });
    }

    public function expire(Order $order): Order
    {
        return DB::transaction(function () use ($order) {
            $order = Order::query()
                ->lockForUpdate()
                ->with(['route.bus', 'payment', 'details'])
                ->findOrFail($order->id);

            /*
             * Bisa saja scheduler menemukan order,
             * tetapi sebelum diproses order sudah berubah.
             */
            if ($order->order_status !== 'pending') {
                return $order;
            }

            if (
                !$order->expired_at ||
                $order->expired_at->isFuture()
            ) {
                return $order;
            }

            $this->restoreSeats($order);

            $note = 'Pesanan otomatis kedaluwarsa karena melewati batas waktu pembayaran.';

            $order->update([
                'order_status' => 'expired',
                'payment_status' => 'rejected',
                'cancellation_note' => $note,
            ]);

            if (
                $order->payment &&
                $order->payment->status !== 'verified'
            ) {
                $order->payment->update([
                    'status' => 'rejected',
                    'notes' => $note,
                ]);
            }

            return $order->fresh([
                'route.bus',
                'payment',
                'details',
            ]);
        });
    }

    private function restoreSeats(Order $order): void
    {
        $route = $order->route;

        if (!$route) {
            return;
        }

        $route = $route->lockForUpdate()->first();

        if (!$route) {
            return;
        }

        // $seatCount = $order->details()->count();
        $seatCount = (int) $order->total_passengers;

        if ($seatCount < 1) {
            return;
        }

        $maxSeats = $route->bus?->total_seats;

        $newAvailableSeats = $route->available_seats + $seatCount;

        if ($maxSeats !== null) {
            $newAvailableSeats = min(
                $newAvailableSeats,
                $maxSeats
            );
        }

        $route->update([
            'available_seats' => $newAvailableSeats,
            'status' => $newAvailableSeats > 0
                ? 'available'
                : 'full',
        ]);
    }
}