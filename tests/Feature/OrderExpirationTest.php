<?php

namespace Tests\Feature;

use App\Models\Bus;
use App\Models\Order;
use App\Models\TravelRoute;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderExpirationTest extends TestCase
{
    use RefreshDatabase;

    public function test_expired_unpaid_order_releases_its_seats(): void
    {
        $user = User::factory()->create();
        $bus = Bus::create([
            'bus_code' => 'BUS-TEST',
            'bus_name' => 'Bus Test',
            'bus_type' => 'executive',
            'plate_number' => 'B 1234 TEST',
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
            'available_seats' => 28,
            'status' => 'available',
        ]);
        $order = Order::create([
            'user_id' => $user->id,
            'route_id' => $route->id,
            'order_code' => 'PO-TEST-001',
            'total_passengers' => 2,
            'total_price' => 150000,
            'payment_status' => 'unpaid',
            'order_status' => 'pending',
            'expired_at' => now()->subMinute(),
        ]);
        $order->payment()->create([
            'payment_method' => 'transfer',
            'amount' => 150000,
            'status' => 'unpaid',
        ]);

        $this->artisan('orders:expire')->assertSuccessful();

        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'order_status' => 'expired',
        ]);
        $this->assertDatabaseHas('payments', [
            'order_id' => $order->id,
            'status' => 'rejected',
        ]);
        $this->assertDatabaseHas('routes', [
            'id' => $route->id,
            'available_seats' => 30,
        ]);
    }
}
