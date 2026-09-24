<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;
use RuntimeException;

class PaymentController extends Controller
{
    public function index(Request $request): View
    {
        $query = Payment::with([
            'order.user',
            'order.route',
        ]);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('payment_method')) {
            $query->where(
                'payment_method',
                $request->payment_method
            );
        }

        if ($request->filled('search')) {
            $search = trim($request->search);

            $query->whereHas('order', function ($orderQuery) use ($search) {
                $orderQuery->where(
                    'order_code',
                    'like',
                    "%{$search}%"
                );
            });
        }

        $payments = $query
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view(
            'admin.payments.index',
            compact('payments')
        );
    }

    public function show(Payment $payment): View
    {
        $payment->load([
            'order.user',
            'order.route.bus',
            'order.details',
        ]);

        return view(
            'admin.payments.show',
            compact('payment')
        );
    }

    public function verify(
        Request $request,
        Payment $payment
    ): RedirectResponse {
        $validated = $request->validate([
            'notes' => [
                'nullable',
                'string',
                'max:1000',
            ],
        ]);

        if ($payment->status !== 'pending') {
            return back()->with(
                'error',
                'Hanya pembayaran yang sudah dikirim customer yang dapat diverifikasi.'
            );
        }

        try {
            DB::transaction(function () use ($payment, $validated) {
                $payment = Payment::query()
                    ->with('order')
                    ->lockForUpdate()
                    ->findOrFail($payment->id);

                $order = $payment->order()->lockForUpdate()->firstOrFail();

                if (
                    $payment->status !== 'pending'
                    || ! in_array($order->order_status, ['pending', 'confirmed'], true)
                    || ($order->expired_at && $order->expired_at->isPast())
                ) {
                    throw new RuntimeException(
                        'Pembayaran tidak dapat diverifikasi pada status order saat ini.'
                    );
                }

                $payment->update([
                    'status' => 'verified',
                    'paid_at' => now(),
                    'notes' => $validated['notes'] ?? null,
                ]);

                $order->update([
                    'payment_status' => 'verified',
                    'order_status' => 'paid',
                    'ticket_code' => $order->ticket_code ?? $this->newTicketCode(),
                    'ticket_issued_at' => $order->ticket_issued_at ?? now(),
                ]);
            });
        } catch (RuntimeException $exception) {
            return back()->with('error', $exception->getMessage());
        }

        return back()->with(
            'success',
            'Pembayaran berhasil diverifikasi.'
        );
    }

    public function reject(
        Request $request,
        Payment $payment
    ): RedirectResponse {
        $validated = $request->validate([
            'notes' => [
                'required',
                'string',
                'max:1000',
            ],
        ]);

        if ($payment->status !== 'pending') {
            return back()->with(
                'error',
                'Hanya pembayaran yang sudah dikirim customer yang dapat ditolak.'
            );
        }

        DB::transaction(function () use ($payment, $validated) {
            $payment->load('order');

            $payment->update([
                'status' => 'rejected',
                'notes' => $validated['notes'],
            ]);

            $payment->order()->update([
                'payment_status' => 'rejected',
            ]);
        });

        return back()->with(
            'success',
            'Pembayaran ditolak.'
        );
    }

    private function newTicketCode(): string
    {
        do {
            $ticketCode = 'CAN-' . now()->format('ymd') . '-' . Str::upper(Str::random(6));
        } while (Order::query()->where('ticket_code', $ticketCode)->exists());

        return $ticketCode;
    }
}
