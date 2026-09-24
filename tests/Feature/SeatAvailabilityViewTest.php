<?php

namespace Tests\Feature;

use App\Models\Bus;
use App\Models\Order;
use App\Models\TravelRoute;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SeatAvailabilityViewTest extends TestCase
{
    use RefreshDatabase;

    public function test_booking_page_marks_active_order_seats_as_booked(): void
    {
        $customer = User::factory()->create(['role' => 'customer']);
        $bus = Bus::create([
            'bus_code' => 'BUS-SEAT',
            'bus_name' => 'Bus Seat',
            'bus_type' => 'executive',
            'plate_number' => 'B 3000 SEA',
            'total_seats' => 30,
            'status' => 'active',
        ]);
        $route = TravelRoute::create([
            'bus_id' => $bus->id,
            'origin_city' => 'Jepara',
            'origin_terminal' => 'Terminal Jepara',
            'destination_city' => 'Semarang',
            'destination_terminal' => 'Terminal Terboyo',
            'departure_date' => now()->addDay()->toDateString(),
            'departure_time' => '08:00',
            'estimated_arrival_time' => '11:00',
            'price' => 75000,
            'available_seats' => 29,
            'status' => 'available',
        ]);
        $order = Order::create([
            'user_id' => $customer->id,
            'route_id' => $route->id,
            'order_code' => 'PO-SEAT-001',
            'total_passengers' => 1,
            'total_price' => 75000,
            'payment_status' => 'unpaid',
            'order_status' => 'pending',
            'expired_at' => now()->addMinutes(30),
        ]);
        $order->details()->create([
            'passenger_name' => 'Penumpang Satu',
            'passenger_phone' => '081234567890',
            'seat_number' => '7',
            'price' => 75000,
        ]);

        $this->actingAs($customer)
            ->get(route('customer.bookings.create', $route))
            ->assertOk()
            ->assertSee('bookedSeats: [7]', false)
            ->assertSee('Sudah dipesan');
    }
}
