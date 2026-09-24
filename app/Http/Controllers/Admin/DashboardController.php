<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Bus;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Setting;
use App\Models\TravelRoute;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Carbon\CarbonPeriod;

class DashboardController extends Controller
{
    public function index()
    {
        // Otomatis sinkronkan status check-in & selesai sesuai jadwal real-time
        app(\App\Services\OrderScheduleSyncService::class)->syncAllActive();

        // 1. Statistik Utama
        $totalRevenue = Payment::where('status', 'verified')->sum('amount');
        $pendingOrders = Order::where('order_status', 'pending')->count();
        $pendingVerifications = Payment::where('status', 'pending')->count();
        $activeBuses = Bus::where('status', 'active')->count();
        $totalOrders = Order::count();
        $activeRoutes = TravelRoute::where('status', 'available')->count();
        $paymentCounts = Payment::query()
            ->selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        $revenueMonths = collect(CarbonPeriod::create(
            now()->startOfMonth()->subMonths(5),
            '1 month',
            now()->startOfMonth()
        ));
        $verifiedPayments = Payment::query()
            ->where('status', 'verified')
            ->whereNotNull('paid_at')
            ->where('paid_at', '>=', now()->startOfMonth()->subMonths(5))
            ->get(['paid_at', 'amount']);
        $revenueByMonth = $revenueMonths->map(function ($month) use ($verifiedPayments) {
            $month = Carbon::instance($month);
            $key = $month->format('Y-m');

            return [
                'label' => $month->translatedFormat('M Y'),
                'total' => $verifiedPayments
                    ->filter(fn ($payment) => $payment->paid_at?->format('Y-m') === $key)
                    ->sum('amount'),
            ];
        });

        $ordersByStatus = Order::query()
            ->selectRaw('order_status, COUNT(*) as total')
            ->groupBy('order_status')
            ->pluck('total', 'order_status');
$statusLabels = ['pending', 'paid', 'cancelled', 'completed', 'expired'];
        $ordersTrend = collect(range(6, 0))->map(function (int $daysAgo) {
            $date = now()->subDays($daysAgo);

            return [
                'label' => $date->format('d M'),
                'total' => Order::whereDate('created_at', $date->toDateString())->count(),
            ];
        });

        $routeStats = TravelRoute::with(['bus', 'orders' => function ($query) {
            $query->whereIn('order_status', ['paid', 'completed']);
        }])->get()->map(function (TravelRoute $route) {
            $passengers = $route->orders->sum('total_passengers');
            $capacity = $route->bus?->total_seats ?? 0;

            return [
                'route' => $route->origin_city . ' → ' . $route->destination_city,
                'occupancy' => $capacity > 0 ? min(100, round(($passengers / $capacity) * 100, 2)) : 0,
            ];
        })->sortByDesc('occupancy')->take(5)->values();

        $busStats = Bus::with(['routes.orders' => function ($query) {
            $query->whereIn('order_status', ['paid', 'completed']);
        }])->where('status', 'active')->get()->map(function (Bus $bus) {
            $routes = $bus->routes;
            $passengers = $routes->flatMap->orders->sum('total_passengers');
            $capacity = $bus->total_seats * max(1, $routes->count());

            return [
                'name' => $bus->bus_name,
                'type' => $bus->bus_type,
                'total_seats' => $bus->total_seats,
                'passengers' => $passengers,
                'routes' => $routes->where('status', 'available')->count(),
                'available_seats' => $routes->sum('available_seats'),
                'occupancy' => $capacity > 0 ? round(($passengers / $capacity) * 100, 2) : 0,
            ];
        });

        $routeDetails = TravelRoute::with(['bus', 'orders' => function ($query) {
            $query->whereIn('order_status', ['paid', 'completed'])->with('payment');
        }])->latest('departure_date')->latest('departure_time')->get()->map(function (TravelRoute $route) {
            $orders = $route->orders;

            return [
                'route' => $route->origin_city . ' → ' . $route->destination_city,
                'departure' => $route->departure_date->format('d M Y') . ' ' . $route->departure_time,
                'price' => $route->price,
                'available_seats' => $route->available_seats,
                'total_seats' => $route->bus?->total_seats ?? 0,
                'passengers' => $orders->sum('total_passengers'),
                'orders' => $orders->count(),
                'revenue' => $orders->sum(fn ($order) => $order->payment?->status === 'verified'
                    ? (float) $order->payment->amount
                    : 0),
                'status' => $route->status,
            ];
        });

        // 2. Order Terbaru (5 terakhir)
        $recentOrders = Order::with(['user', 'route.bus'])
            ->latest()
            ->take(5)
            ->get();

        // 3. Pembayaran Menunggu Verifikasi (5 terakhir)
        $pendingPayments = Payment::with(['order.user', 'order.route'])
            ->where('status', 'pending')
            ->latest()
            ->take(5)
            ->get();

        $recentPayments = Payment::with(['order.user', 'order.details'])
            ->latest()
            ->take(10)
            ->get();

        // 4. Ambil beberapa setting penting untuk ditampilkan
        $appName = Setting::get('app_name', 'PO CAN Travel');
        $paymentExpiry = Setting::get('payment_expiry_hours', '24');

        return view('admin.dashboard', compact(
            'totalRevenue',
            'pendingOrders',
            'pendingVerifications',
            'activeBuses',
            'totalOrders',
            'activeRoutes',
            'paymentCounts',
            'revenueByMonth',
            'ordersByStatus',
            'statusLabels',
            'ordersTrend',
            'routeStats',
            'busStats',
            'routeDetails',
            'recentOrders',
            'pendingPayments',
            'recentPayments',
            'appName',
            'paymentExpiry'
        ));
    }
}