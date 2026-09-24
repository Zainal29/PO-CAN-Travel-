<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\CancelOrderRequest;
use App\Models\Order;
use App\Services\OrderCancellationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use RuntimeException;

class OrderController extends Controller
{
    public function __construct(
        private OrderCancellationService $cancellationService
    ) {
    }

    public function index(): View
    {
        $orders = Order::query()
            ->where('user_id', auth()->id())
            ->with([
                'route.bus',
                'payment',
            ])
            ->latest()
            ->paginate(10);

        return view(
            'customer.orders.index',
            compact('orders')
        );
    }

    public function show(Order $order): View
    {
        abort_unless(
            $order->user_id === auth()->id(),
            403
        );

        $order->load([
            'route.bus',
            'details',
            'payment',
        ]);

        return view(
            'customer.orders.show',
            compact('order')
        );
    }

    public function ticket(Order $order): View
    {
        abort_unless($order->user_id === auth()->id(), 403);
        abort_unless(
            $order->payment_status === 'verified'
            && $order->ticket_code,
            404
        );

        $order->load(['route.bus', 'details', 'payment']);

        return view('customer.orders.ticket', compact('order'));
    }

    public function cancel(
        CancelOrderRequest $request,
        Order $order
    ): RedirectResponse {
        abort_unless(
            $order->user_id === auth()->id(),
            403
        );

        try {
            $this->cancellationService->cancel(
                $order,
                $request->cancellation_note
            );

            return back()->with(
                'success',
                'Order berhasil dibatalkan.'
            );
        } catch (RuntimeException $e) {
            return back()->withErrors([
                'order' => $e->getMessage(),
            ]);
        }
    }
}
