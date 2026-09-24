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

}
