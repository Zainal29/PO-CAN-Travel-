<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Payment;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use RuntimeException;

class PaymentService
{
    public function submit(
        Order $order,
        string $paymentMethod,
        string $payerName,
        string $payerPhone
    ): Payment {
        return DB::transaction(function () use (
            $order,
            $paymentMethod,
            $payerName,
            $payerPhone
        ) {
            if (! auth()->check() || auth()->id() !== $order->user_id) {
                throw new RuntimeException('Anda tidak memiliki akses untuk membayar order ini.');
            }

            if (! in_array($paymentMethod, ['bca', 'bri', 'qris'], true)) {
                throw new RuntimeException('Metode pembayaran simulasi tidak valid.');
            }

            $order = Order::query()
                ->whereKey($order->id)
                ->lockForUpdate()
                ->firstOrFail();

            if ($order->order_status !== 'pending') {
                throw new RuntimeException(
                    'Order hanya dapat dibayar saat berstatus pending.'
                );
            }

            if (
                $order->expired_at &&
                $order->expired_at->isPast()
            ) {
                throw new RuntimeException(
                    'Order sudah kedaluwarsa.'
                );
            }

            $payment = $order->payment()
                ->lockForUpdate()
                ->first();

            if (! $payment) {
                throw new RuntimeException('Data pembayaran order tidak tersedia.');
            }

            if (! in_array($payment->status, ['unpaid', 'rejected'], true)) {
                throw new RuntimeException(
                    'Pembayaran order ini tidak dapat diproses ulang.'
                );
            }

            $transactionId = $this->newTransactionId();
            $notes = "Simulasi pembayaran\nPembayar: {$payerName}\nTelepon: {$payerPhone}";

            $payment->update([
                'payment_method' => $paymentMethod,
                'payment_proof' => null,
                'transaction_id' => $transactionId,
                'amount' => $order->total_price,
                'status' => 'verified',
                'paid_at' => now(),
                'notes' => $notes,
            ]);

            $order->update([
                'payment_status' => 'verified',
                'order_status' => 'paid',
                'ticket_code' => $order->ticket_code ?? $this->newTicketCode(),
                'ticket_issued_at' => $order->ticket_issued_at ?? now(),
            ]);

            return $payment->fresh('order');
        });
    }

    private function newTransactionId(): string
    {
        do {
            $transactionId = 'PAY-' . now()->format('Ymd') . '-' . Str::upper(Str::random(6));
        } while (Payment::query()->where('transaction_id', $transactionId)->exists());

        return $transactionId;
    }

    private function newTicketCode(): string
    {
        do {
            $ticketCode = 'CAN-' . now()->format('ymd') . '-' . Str::upper(Str::random(6));
        } while (Order::query()->where('ticket_code', $ticketCode)->exists());

        return $ticketCode;
    }
}
