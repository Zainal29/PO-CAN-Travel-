<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\CancelOrderRequest;
use App\Http\Requests\StoreReviewRequest;
use App\Models\Order;
use App\Models\Review;
use App\Services\OrderCancellationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
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
            'review',
        ]);

        return view(
            'customer.orders.show',
            compact('order')
        );
    }

    public function review(
        StoreReviewRequest $request,
        Order $order
    ): RedirectResponse {
        abort_unless($order->user_id === auth()->id(), 403);

        try {
            DB::transaction(function () use ($request, $order) {
                $lockedOrder = Order::query()
                    ->lockForUpdate()
                    ->findOrFail($order->id);

                if ($lockedOrder->order_status !== 'completed') {
                    throw new RuntimeException(
                        'Review hanya dapat diberikan setelah perjalanan selesai.'
                    );
                }

                if ($lockedOrder->review()->exists()) {
                    throw new RuntimeException('Order ini sudah memiliki review.');
                }

                Review::create([
                    'user_id' => auth()->id(),
                    'order_id' => $lockedOrder->id,
                    'route_id' => $lockedOrder->route_id,
                    'rating' => $request->integer('rating'),
                    'comment' => $request->input('comment'),
                ]);
            });
        } catch (RuntimeException $exception) {
            return back()->withErrors(['review' => $exception->getMessage()]);
        }

        return back()->with('success', 'Review berhasil disimpan.');
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
