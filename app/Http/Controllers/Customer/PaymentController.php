<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePaymentRequest;
use App\Models\Order;
use App\Services\PaymentService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use RuntimeException;

class PaymentController extends Controller
{
    public function __construct(
        private PaymentService $paymentService
    ) {
    }

    public function create(Order $order): View|RedirectResponse
    {
        abort_unless(
            $order->user_id === auth()->id(),
            403
        );

        if ($order->order_status === 'paid' || $order->payment?->status === 'verified') {
            return redirect()->route('customer.orders.show', $order);
        }

        abort_if(
            in_array($order->order_status, ['cancelled', 'expired', 'completed'], true),
            403,
            'Pesanan ini tidak dapat dibayar.'
        );

        $order->load([
            'route.bus',
            'payment',
        ]);

        return view(
            'customer.payments.create',
            compact('order')
        );
    }

    public function store(
        StorePaymentRequest $request,
        Order $order
    ): RedirectResponse {
        abort_unless(
            $order->user_id === auth()->id(),
            403
        );

        try {
            $this->paymentService->submit(
                $order,
                $request->payment_method,
                $request->payer_name,
                $request->payer_phone
            );

            return redirect()
                ->route('customer.payments.success', $order);
        } catch (RuntimeException $e) {
            return back()
                ->withInput()
                ->withErrors([
                    'payment' => $e->getMessage(),
                ]);
        }
    }

    public function success(Order $order): View
    {
        abort_unless($order->user_id === auth()->id(), 403);

        $order->load(['route.bus', 'payment']);

        abort_unless(
            $order->order_status === 'paid'
            && $order->payment?->status === 'verified',
            404
        );

        return view('customer.payments.success', compact('order'));
    }
}