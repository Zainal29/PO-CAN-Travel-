<?php

namespace App\Services;

use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\TravelRoute;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;
use RuntimeException;

class BookingService
{
    public function createOrder(
        int $userId,
        int $routeId,
        array $passengers
    ): Order {
        return DB::transaction(function () use (
            $userId,
            $routeId,
            $passengers
        ) {
            $route = TravelRoute::query()
                ->with('bus')
                ->whereKey($routeId)
                ->lockForUpdate()
                ->firstOrFail();

            if ($route->status !== 'available' || $route->bus->status !== 'active') {
                throw new RuntimeException(
                    'Rute tidak tersedia untuk dipesan.'
                );
            }

            $departureAt = Carbon::parse(
                $route->departure_date->toDateString() .
                ' ' .
                $route->departure_time
            );

            if ($departureAt->isPast()) {
                throw new RuntimeException(
                    'Waktu keberangkatan sudah lewat.'
                );
            }

            $passengerCount = count($passengers);

            if ($passengerCount < 1) {
                throw new RuntimeException(
                    'Minimal satu penumpang.'
                );
            }

            if ($passengerCount > 10) {
                throw new RuntimeException(
                    'Maksimal 10 penumpang dalam satu order.'
                );
            }

            if ($route->available_seats < $passengerCount) {
                throw new RuntimeException(
                    'Jumlah kursi tersedia tidak mencukupi.'
                );
            }

            $seatNumbers = collect($passengers)
                ->map(function ($passenger) {
                    return strtoupper(
                        trim($passenger['seat_number'])
                    );
                })
                ->values();

            if (
                $seatNumbers->unique()->count()
                !== $seatNumbers->count()
            ) {
                throw new RuntimeException(
                    'Terdapat nomor kursi yang dipilih lebih dari satu kali.'
                );
            }

            $bookedSeats = OrderDetail::query()
                ->whereIn('seat_number', $seatNumbers)
                ->whereHas('order', function ($query) use ($routeId) {
                    $query
                        ->where('route_id', $routeId)
                        ->whereIn('order_status', [
                            'pending',
                            'confirmed',
                            'paid',
                        ]);
                })
                ->lockForUpdate()
                ->pluck('seat_number')
                ->map(fn ($seat) => strtoupper(trim($seat)));

            $alreadyBooked = $seatNumbers
                ->intersect($bookedSeats)
                ->values();

            if ($alreadyBooked->isNotEmpty()) {
                throw new RuntimeException(
                    'Kursi berikut sudah dipesan: ' .
                    $alreadyBooked->implode(', ')
                );
            }

            foreach ($seatNumbers as $seatNumber) {
                if (! preg_match('/^\d+$/', $seatNumber)) {
                    throw new RuntimeException(
                        "Nomor kursi {$seatNumber} tidak valid."
                    );
                }

                $seatNumberInt = (int) $seatNumber;

                if (
                    $seatNumberInt < 1 ||
                    $seatNumberInt > $route->bus->total_seats
                ) {
                    throw new RuntimeException(
                        "Nomor kursi {$seatNumber} melebihi kapasitas bus."
                    );
                }
            }

            $price = (float) $route->price;
            $totalPrice = $price * $passengerCount;

            do {
                $orderCode =
                    'PO-' .
                    now()->format('Ymd') .
                    '-' .
                    strtoupper(Str::random(6));
            } while (
                Order::query()
                    ->where('order_code', $orderCode)
                    ->exists()
            );

            $order = Order::create([
                'user_id' => $userId,
                'route_id' => $route->id,
                'order_code' => $orderCode,
                'total_passengers' => $passengerCount,
                'total_price' => $totalPrice,
                'payment_status' => 'unpaid',
                'order_status' => 'pending',
                'expired_at' => now()->addMinutes(30),
            ]);

            foreach ($passengers as $index => $passenger) {
                $order->details()->create([
                    'passenger_name' => trim($passenger['name']),
                    'passenger_phone' => trim($passenger['phone']),
                    'passenger_email' =>
                        ! empty($passenger['email'])
                            ? trim($passenger['email'])
                            : null,
                    'seat_number' => $seatNumbers[$index],
                    'price' => $price,
                ]);
            }

            $order->payment()->create([
                'payment_method' => 'transfer',
                'amount' => $totalPrice,
                'status' => 'unpaid',
            ]);

            $route->decrement(
                'available_seats',
                $passengerCount
            );

            if ($route->available_seats <= 0) {
                $route->update([
                    'available_seats' => 0,
                    'status' => 'full',
                ]);
            }

            return $order->load([
                'user',
                'route.bus',
                'details',
                'payment',
            ]);
        });
    }
}
