<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateBookingRequest;
use App\Models\OrderDetail;
use App\Models\TravelRoute;
use App\Services\BookingService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use RuntimeException;

class BookingController extends Controller
{
    public function __construct(
        private BookingService $bookingService
    ) {
    }

    public function create(
        TravelRoute $route
    ): View {
        $route->load('bus');

        abort_if(
            $route->status !== 'available' ||
            $route->available_seats < 1 ||
            $route->bus->status !== 'active',
            404
        );

        $bookedSeats = $this->bookingService->getBookedSeatsForRoute($route);

        return view(
            'customer.bookings.create',
            compact('route', 'bookedSeats')
        );
    }

    public function store(
        CreateBookingRequest $request
    ): RedirectResponse {
        try {
            $order = $this->bookingService->createOrder(
                auth()->id(),
                (int) $request->route_id,
                $request->passengers
            );

            return redirect()
                ->route(
                    'customer.orders.show',
                    $order
                )
                ->with(
                    'success',
                    'Booking berhasil dibuat. Silakan lanjutkan pembayaran.'
                );
        } catch (RuntimeException $e) {
            return back()
                ->withInput()
                ->withErrors([
                    'booking' => $e->getMessage(),
                ]);
        }
    }
}
