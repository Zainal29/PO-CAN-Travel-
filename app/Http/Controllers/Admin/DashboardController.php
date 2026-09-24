<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Bus;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Setting;
use App\Models\TravelRoute;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. Statistik Utama
        $totalRevenue = Payment::where('status', 'verified')->sum('amount');
        $pendingOrders = Order::where('order_status', 'pending')->count();
        $pendingVerifications = Payment::where('status', 'pending')->count();
        $activeBuses = Bus::where('status', 'active')->count();

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

        // 4. Ambil beberapa setting penting untuk ditampilkan
        $appName = Setting::get('app_name', 'PO CAN Travel');
        $paymentExpiry = Setting::get('payment_expiry_hours', '24');

        return view('admin.dashboard', compact(
            'totalRevenue',
            'pendingOrders',
            'pendingVerifications',
            'activeBuses',
            'recentOrders',
            'pendingPayments',
            'appName',
            'paymentExpiry'
        ));
    }
}