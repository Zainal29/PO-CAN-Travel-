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
        private OrderCancellationService $cancellationService,
        private \App\Services\OrderScheduleSyncService $scheduleSyncService
    ) {
    }

    public function index(Request $request): View
    {
        // Otomatis sinkronkan status check-in & selesai berdasarkan jadwal
        $this->scheduleSyncService->syncAllActive();

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
            $query->whereHas('payment', function ($paymentQuery) use ($request) {
                $paymentQuery->where('status', $request->payment_status);
            });
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

    public function show(Order $order): View
    {
        // Jika order ter-soft-delete, restore dulu atau tampilkan pesan
        if ($order->trashed()) {
            return redirect()
                ->route('admin.orders.index')
                ->with('error', 'Order ini sudah diarsipkan.');
        }

        // Sinkronkan status jadwal order secara otomatis
        $order = $this->scheduleSyncService->syncOrder($order);

        $order->load([
            'user',
            'route.bus',
            'details',
            'payment',
        ]);

        $scheduleContext = $this->scheduleSyncService->getScheduleContext($order);

        return view('admin.orders.show', compact('order', 'scheduleContext'));
    }

    public function updateStatus(
        Request $request,
        Order $order
    ): RedirectResponse {
        $validated = $request->validate([
            'order_status' => [
                'required',
                'in:completed',
            ],
        ]);

        $allowedTransition = $order->order_status === 'paid'
            && $validated['order_status'] === 'completed';

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
            $order->payment?->status !== 'verified'
            || $order->order_status !== 'paid'
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

    public function checkInByQr(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'qr_payload' => ['required', 'string', 'max:5000'],
        ]);

        $payload = $validated['qr_payload'];
        preg_match('/^Order:\s*(\S+)/mi', $payload, $orderMatch);
        preg_match('/^Ticket:\s*(\S+)/mi', $payload, $ticketMatch);

        $orderCode = $orderMatch[1] ?? null;
        $ticketCode = $ticketMatch[1] ?? null;

        $order = Order::query()
            ->with('route')
            ->when(
                $ticketCode,
                fn ($query) => $query->where('ticket_code', $ticketCode)
            )
            ->when(
                ! $ticketCode && $orderCode,
                fn ($query) => $query->where('order_code', $orderCode)
            )
            ->first();

        if (! $order) {
            return back()->with('error', 'QR e-ticket tidak valid atau order tidak ditemukan.');
        }

        return $this->checkIn($order);
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