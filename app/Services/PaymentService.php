<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use RuntimeException;

class PaymentService
{
    public function submit(
        Order $order,
        string $paymentMethod,
        ?UploadedFile $paymentProof = null,
        ?string $transactionId = null,
        ?string $notes = null
    ): Payment {
        return DB::transaction(function () use (
            $order,
            $paymentMethod,
            $paymentProof,
            $transactionId,
            $notes
        ) {
            $order = Order::query()
                ->whereKey($order->id)
                ->lockForUpdate()
                ->firstOrFail();

            if (! in_array($order->order_status, ['pending', 'confirmed'], true)) {
                throw new RuntimeException(
                    'Order tidak dapat menerima pembayaran pada status saat ini.'
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
                $payment = $order->payment()->create([
                    'payment_method' => $paymentMethod,
                    'amount' => $order->total_price,
                    'status' => 'unpaid',
                ]);
            }

            if ($payment->status === 'verified') {
                throw new RuntimeException(
                    'Pembayaran sudah diverifikasi.'
                );
            }

            $proofPath = $payment->payment_proof;

            if ($paymentProof) {
                if ($proofPath) {
                    Storage::disk('public')->delete($proofPath);
                }

                $proofPath = $paymentProof->store(
                    'payment-proofs',
                    'public'
                );
            }

            $payment->update([
                'payment_method' => $paymentMethod,
                'payment_proof' => $proofPath,
                'transaction_id' => $transactionId,
                'amount' => $order->total_price,
                'status' => 'pending',
                'notes' => $notes,
            ]);

            $order->update([
                'payment_status' => 'pending',
            ]);

            return $payment->fresh('order');
        });
    }
}
