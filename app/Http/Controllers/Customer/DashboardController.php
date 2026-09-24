<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        // Otomatis perbarui status check-in dan selesai sesuai jadwal
        app(\App\Services\OrderScheduleSyncService::class)->syncAllActive();

        $user = auth()->user();

        $stats = [
            'total_orders' => $user->orders()->count(),

            'pending_orders' => $user->orders()
                ->where('order_status', 'pending')
                ->count(),

            'paid_orders' => $user->orders()
                ->where('order_status', 'paid')
                ->count(),

            'completed_orders' => $user->orders()
                ->where('order_status', 'completed')
                ->count(),
        ];

        $latestOrders = Order::query()
            ->where('user_id', $user->id)
            ->with([
                'route.bus',
                'payment',
            ])
            ->latest()
            ->limit(5)
            ->get();

        return view(
            'customer.dashboard.index',
            compact('stats', 'latestOrders')
        );
    }
}