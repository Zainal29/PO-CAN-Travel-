<?php

namespace Tests\Feature;

use App\Models\Bus;
use App\Models\TravelRoute;
use App\Models\User;
use App\Services\BookingService;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use RuntimeException;
use Tests\TestCase;

class BookingServiceTest extends TestCase
{
    use RefreshDatabase;

    protected function tearDown(): void
    {
        Carbon::setTestNow();

        parent::tearDown();
    }

    public function test_booking_creates_order_payment_and_prevents_duplicate_seat(): void
    {
        $user = User::factory()->create();
        $bus = Bus::create([
            'bus_code' => 'BUS-BOOKING',
            'bus_name' => 'Bus Booking',
            'bus_type' => 'executive',
            'plate_number' => 'B 1000 BKG',
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
            'available_seats' => 30,
            'status' => 'available',
        ]);
        $passengers = [[
            'name' => 'Penumpang Satu',
            'phone' => '081234567890',
            'email' => 'penumpang@example.test',
            'seat_number' => '1',
        ]];

        $order = app(BookingService::class)->createOrder($user->id, $route->id, $passengers);

        $this->assertSame('pending', $order->order_status);
        $this->assertSame('unpaid', $order->payment_status);
        $this->assertSame('75000.00', $order->total_price);
        $this->assertSame('1', $order->details->first()->seat_number);
        $this->assertSame('unpaid', $order->payment->status);
        $this->assertDatabaseHas('routes', ['id' => $route->id, 'available_seats' => 29]);

        $this->expectException(RuntimeException::class);
        app(BookingService::class)->createOrder($user->id, $route->id, $passengers);
    }

    public function test_booking_allows_a_route_later_today_and_rejects_a_departed_route(): void
    {
        Carbon::setTestNow('2026-09-23 19:00:00');

        $user = User::factory()->create();
        $bus = Bus::create([
            'bus_code' => 'BUS-TODAY',
            'bus_name' => 'Bus Today',
            'bus_type' => 'executive',
            'plate_number' => 'B 4000 TDA',
            'total_seats' => 30,
            'status' => 'active',
        ]);
        $passengers = [[
            'name' => 'Penumpang Hari Ini',
            'phone' => '081234567890',
            'email' => null,
            'seat_number' => '1',
        ]];
        $futureToday = TravelRoute::create([
            'bus_id' => $bus->id,
            'origin_city' => 'Jepara',
            'origin_terminal' => 'Terminal Jepara',
            'destination_city' => 'Semarang',
            'destination_terminal' => 'Terminal Terboyo',
            'departure_date' => '2026-09-23',
            'departure_time' => '20:00',
            'estimated_arrival_time' => '23:00',
            'price' => 75000,
            'available_seats' => 30,
            'status' => 'available',
        ]);

        $order = app(BookingService::class)->createOrder($user->id, $futureToday->id, $passengers);
        $this->assertSame('pending', $order->order_status);

        $pastToday = TravelRoute::create([
            ...$futureToday->only([
                'bus_id', 'origin_city', 'origin_terminal', 'destination_city',
                'destination_terminal', 'departure_date', 'estimated_arrival_time',
                'price', 'available_seats', 'status',
            ]),
            'departure_time' => '18:00',
        ]);

        $this->expectExceptionObject(new RuntimeException('Waktu keberangkatan sudah lewat.'));
        app(BookingService::class)->createOrder($user->id, $pastToday->id, $passengers);
    }
}
