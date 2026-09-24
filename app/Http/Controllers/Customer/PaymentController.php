<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePaymentRequest;
use App\Models\Order;
use App\Services\PaymentService;
use App\Services\QrCodeService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use RuntimeException;

class PaymentController extends Controller
{
    public function __construct(
        private PaymentService $paymentService,
        private QrCodeService $qrCodeService
    ) {
    }

    public function create(Order $order): View
    {
        abort_unless(
            $order->user_id === auth()->id(),
            403
        );

        $order->load([
            'route.bus',
            'payment',
        ]);

        $accountInfo = implode("\n", [
            'Transfer ke:',
            'Bank: BCA',
            'No. Rek: 1234567890',
            'Atas Nama: PO CAN Travel',
            'Nominal: Rp ' . number_format($order->total_price, 0, ',', '.'),
        ]);
        $paymentQrCode = $this->qrCodeService->dataUri($accountInfo);

        return view(
            'customer.payments.create',
            compact('order', 'paymentQrCode')
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
                $request->file('payment_proof'),
                $request->transaction_id,
                $request->notes
            );

            return redirect()
                ->route(
                    'customer.orders.show',
                    $order
                )
                ->with(
                    'success',
                    'Data pembayaran berhasil dikirim dan menunggu verifikasi admin.'
                );
        } catch (RuntimeException $e) {
            return back()
                ->withInput()
                ->withErrors([
                    'payment' => $e->getMessage(),
                ]);
        }
    }
}