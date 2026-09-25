<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\View\View;

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

    public function exportExcel(Request $request)
    {
        $query = Payment::with([
            'order.user',
            'order.route.bus',
        ]);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('payment_method')) {
            $query->where('payment_method', $request->payment_method);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function ($q) use ($search) {
                $q->where('transaction_id', 'like', "%{$search}%")
                    ->orWhereHas('order', function ($orderQuery) use ($search) {
                        $orderQuery->where('order_code', 'like', "%{$search}%")
                            ->orWhereHas('user', function ($uQuery) use ($search) {
                                $uQuery->where('name', 'like', "%{$search}%")
                                    ->orWhere('email', 'like', "%{$search}%");
                            });
                    });
            });
        }

        $payments = $query->latest()->get();

        $filename = 'laporan-keuangan-pendapatan-pocantravel-' . now()->format('Y-m-d_His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        return response()->stream(function () use ($payments, $request) {
            $handle = fopen('php://output', 'w');

            // UTF-8 BOM agar Excel membaca karakter & tanda aksen dengan benar
            fputs($handle, "\xEF\xBB\xBF");

            // Header Judul Laporan
            fputcsv($handle, ['LAPORAN KEUANGAN & PENDAPATAN — PO CAN TRAVEL']);
            fputcsv($handle, ['Waktu Cetak', now()->translatedFormat('d F Y, H:i') . ' WIB']);
            fputcsv($handle, ['Filter Status', $request->status ? strtoupper($request->status) : 'Semua Status']);
            fputcsv($handle, ['Filter Metode', $request->payment_method ? strtoupper($request->payment_method) : 'Semua Metode']);
            fputcsv($handle, ['Total Data', $payments->count() . ' Transaksi']);
            fputcsv($handle, []); // Baris Kosong

            // Header Tabel Kolom
            fputcsv($handle, [
                'No',
                'ID Transaksi',
                'Kode Order',
                'Tanggal Order',
                'Nama Pelanggan',
                'Email',
                'No. Telepon / WA',
                'Rute Perjalanan',
                'Armada Bus',
                'Tanggal Berangkat',
                'Jam Berangkat',
                'Metode Pembayaran',
                'Status Pembayaran',
                'Waktu Verifikasi / Bayar',
                'Nominal Pendapatan (Rp)',
            ]);

            $totalVerified = 0;
            $totalPending = 0;
            $totalRejected = 0;
            $totalAll = 0;

            foreach ($payments as $index => $payment) {
                $order = $payment->order;
                $user = $order?->user;
                $route = $order?->route;
                $bus = $route?->bus;

                $amount = (float) $payment->amount;
                $totalAll += $amount;

                if ($payment->status === 'verified') {
                    $totalVerified += $amount;
                } elseif ($payment->status === 'pending') {
                    $totalPending += $amount;
                } elseif ($payment->status === 'rejected') {
                    $totalRejected += $amount;
                }

                $statusLabel = match($payment->status) {
                    'verified' => 'Terverifikasi (Lunas)',
                    'pending' => 'Menunggu Verifikasi',
                    'rejected' => 'Ditolak',
                    'unpaid' => 'Belum Dibayar',
                    default => ucfirst($payment->status ?? '-'),
                };

                $methodLabel = match($payment->payment_method) {
                    'bca' => 'Bank BCA',
                    'bri' => 'Bank BRI',
                    'qris' => 'QRIS Simulasi',
                    'transfer' => 'Transfer Bank',
                    'virtual_account' => 'Virtual Account',
                    'e_wallet' => 'E-Wallet',
                    'cash' => 'Tunai',
                    default => strtoupper($payment->payment_method ?? '-'),
                };

                fputcsv($handle, [
                    $index + 1,
                    $payment->transaction_id ?? ('TRX-' . $payment->id),
                    $order?->order_code ?? '-',
                    $payment->created_at ? $payment->created_at->format('Y-m-d H:i') : '-',
                    $user?->name ?? 'Tamu / Umum',
                    $user?->email ?? '-',
                    $user?->phone ?? '-',
                    $route ? ($route->origin_city . ' → ' . $route->destination_city) : '-',
                    $bus?->bus_name ?? '-',
                    $route?->departure_date ? $route->departure_date->format('Y-m-d') : '-',
                    $route?->departure_time ? substr($route->departure_time, 0, 5) : '-',
                    $methodLabel,
                    $statusLabel,
                    $payment->paid_at ? $payment->paid_at->format('Y-m-d H:i') : '-',
                    $amount,
                ]);
            }

            // Summary Section
            fputcsv($handle, []);
            fputcsv($handle, ['RINGKASAN TOTAL PENDAPATAN & KEUANGAN']);
            fputcsv($handle, ['', '', '', '', '', '', '', '', '', '', '', '', 'Total Pendapatan Terverifikasi (Lunas)', 'Rp', $totalVerified]);
            fputcsv($handle, ['', '', '', '', '', '', '', '', '', '', '', '', 'Total Dana Pending Verifikasi', 'Rp', $totalPending]);
            fputcsv($handle, ['', '', '', '', '', '', '', '', '', '', '', '', 'Total Dana Ditolak', 'Rp', $totalRejected]);
            fputcsv($handle, ['', '', '', '', '', '', '', '', '', '', '', '', 'Total Keseluruhan Transaksi', 'Rp', $totalAll]);

            fclose($handle);
        }, 200, $headers);
    }
}

