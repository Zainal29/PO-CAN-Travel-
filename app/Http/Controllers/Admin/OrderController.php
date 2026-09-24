<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\OrderCancellationService;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use RuntimeException;

class OrderController extends Controller
{
    public function __construct(
        private OrderCancellationService $cancellationService
    ) {
    }

    public function index(Request $request): View
    {
        $query = Order::with([
            'user',
            'route.bus',
            'details',
            'payment',
        ]);

        if ($request->filled('status')) {
            $query->where('order_status', $request->status);
        }

        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }

        if ($request->filled('search')) {
            $search = trim($request->search);

            $query->where(function ($q) use ($search) {
                $q->where('order_code', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($userQuery) use ($search) {
                        $userQuery
                            ->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                    });
            });
        }

        $orders = $query
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.orders.index', compact('orders'));
    }

    // public function show(Order $order): View
    // {
    //     $order->load([
    //         'user',
    //         'route.bus',
    //         'details',
    //         'payment',
    //     ]);

    //     return view('admin.orders.show', compact('order'));
    // }

    public function show(Order $order): View
{
    // Jika order ter-soft-delete, restore dulu atau tampilkan pesan
    if ($order->trashed()) {
        return redirect()
            ->route('admin.orders.index')
            ->with('error', 'Order ini sudah diarsipkan.');
    }

    $order->load([
        'user',
        'route.bus',
        'details',
        'payment',
    ]);

    return view('admin.orders.show', compact('order'));
}

    public function updateStatus(
        Request $request,
        Order $order
    ): RedirectResponse {
        $validated = $request->validate([
            'order_status' => [
                'required',
                'in:confirmed,completed',
            ],
        ]);

        $allowedTransition = ($order->order_status === 'pending'
                && $validated['order_status'] === 'confirmed')
            || ($order->order_status === 'paid'
                && $validated['order_status'] === 'completed');

        if (! $allowedTransition) {
            return back()->with('error', 'Perubahan status tidak sesuai alur order.');
        }

        $order->update(['order_status' => $validated['order_status']]);

        return back()->with(
            'success',
            'Status order berhasil diperbarui.'
        );
    }

    public function cancel(
        Request $request,
        Order $order
    ): RedirectResponse {
        $validated = $request->validate([
            'cancellation_note' => [
                'required',
                'string',
                'max:1000',
            ],
        ]);

        try {
            $this->cancellationService->cancel(
                $order,
                $validated['cancellation_note']
            );
        } catch (RuntimeException $exception) {
            return back()->with('error', $exception->getMessage());
        }

        return back()->with(
            'success',
            'Order berhasil dibatalkan dan kursi dikembalikan.'
        );
    }

    public function checkIn(Order $order): RedirectResponse
    {
        $departureAt = Carbon::parse(
            $order->route->departure_date->toDateString() . ' ' . $order->route->departure_time
        );

        if (
            $order->payment_status !== 'verified'
            || ! in_array($order->order_status, ['paid', 'confirmed'], true)
            || $departureAt->isPast()
        ) {
            return back()->with('error', 'Order ini belum memenuhi syarat check-in.');
        }

        if ($order->checked_in_at) {
            return back()->with('error', 'Order ini sudah check-in.');
        }

        $order->update(['checked_in_at' => now()]);

        return back()->with('success', 'Check-in berhasil dicatat.');
    }

    /**
     * Release seats dari order yang cancelled/expired/rejected
     */
    public function releaseSeats(Order $order): RedirectResponse
    {
        try {
            $this->cancellationService->releaseSeats($order);
        } catch (RuntimeException $exception) {
            return back()->with('error', $exception->getMessage());
        }

        return back()->with('success', 'Kursi berhasil dilepas dan tersedia untuk penumpang lain.');
    }

    

    /**
     * Arsipkan order (soft delete)
     */
    public function archive(Order $order): RedirectResponse
    {
        // Pastikan order sudah cancelled/expired sebelum diarsipkan
        if (!in_array($order->order_status, ['cancelled', 'expired', 'completed'], true)) {
            return back()->with('error', 'Hanya order yang sudah selesai/dibatalkan yang bisa diarsipkan.');
        }

        if (
            in_array($order->order_status, ['cancelled', 'expired'], true)
            && ! $order->seats_released
        ) {
            return back()->with('error', 'Lepaskan kursi terlebih dahulu sebelum mengarsipkan order.');
        }

        $order->delete(); // Soft delete karena model menggunakan SoftDeletes

        return redirect()
            ->route('admin.orders.index')
            ->with('success', 'Order berhasil diarsipkan.');
    }
}