<?php

namespace Tests\Feature;

use App\Models\Bus;
use App\Models\Order;
use App\Models\Payment;
use App\Models\TravelRoute;
use App\Models\User;
use App\Services\OrderScheduleSyncService;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AutoCheckInAndCompletionTest extends TestCase
{
    use RefreshDatabase;

    public function test_order_is_automatically_checked_in_when_departure_time_arrives(): void
    {
        $customer = User::factory()->create(['role' => 'customer']);
        $admin = User::factory()->create(['role' => 'admin']);

        $bus = Bus::create([
            'bus_code' => 'BUS-AUTO-1',
            'bus_name' => 'Bus Auto Test',
            'bus_type' => 'executive',
            'plate_number' => 'K 1234 CAN',
            'total_seats' => 30,
            'status' => 'active',
        ]);

        // Rute yang berangkat 1 jam yang lalu, tiba 2 jam ke depan
        $route = TravelRoute::create([
            'bus_id' => $bus->id,
            'origin_city' => 'Jepara',
            'origin_terminal' => 'Terminal Jepara',
            'destination_city' => 'Semarang',
            'destination_terminal' => 'Terminal Terboyo',
            'departure_date' => Carbon::today('Asia/Jakarta')->toDateString(),
            'departure_time' => Carbon::now('Asia/Jakarta')->subHour()->format('H:i'),
            'estimated_arrival_time' => Carbon::now('Asia/Jakarta')->addHours(2)->format('H:i'),
            'price' => 50000,
            'available_seats' => 29,
            'status' => 'available',
        ]);

        $order = Order::create([
            'user_id' => $customer->id,
            'route_id' => $route->id,
            'order_code' => 'PO-AUTO-CHECKIN',
            'ticket_code' => 'TKT-AUTO-CHECKIN',
            'total_passengers' => 1,
            'total_price' => 50000,
            'order_status' => 'paid',
            'payment_status' => 'verified',
            'checked_in_at' => null,
        ]);

        Payment::create([
            'order_id' => $order->id,
            'payment_method' => 'qris',
            'amount' => 50000,
            'status' => 'verified',
            'transaction_id' => 'TRX-AUTO-01',
            'paid_at' => now(),
        ]);

        $this->assertNull($order->checked_in_at);

        // Buka halaman admin order show yang akan otomatis men-trigger auto-checkin
        $this->actingAs($admin)
            ->get(route('admin.orders.show', $order))
            ->assertOk()
            ->assertSee('Order PO-AUTO-CHECKIN')
            ->assertSee('Check-in Terverifikasi');

        $order->refresh();
        $this->assertNotNull($order->checked_in_at);
        $this->assertSame('paid', $order->order_status);
    }

    public function test_order_is_automatically_completed_when_arrival_time_passes(): void
    {
        $customer = User::factory()->create(['role' => 'customer']);
        $admin = User::factory()->create(['role' => 'admin']);

        $bus = Bus::create([
            'bus_code' => 'BUS-AUTO-2',
            'bus_name' => 'Bus Auto Complete',
            'bus_type' => 'executive',
            'plate_number' => 'K 5678 CAN',
            'total_seats' => 30,
            'status' => 'active',
        ]);

        // Rute yang berangkat 3 jam lalu dan tiba 1 jam lalu
        $route = TravelRoute::create([
            'bus_id' => $bus->id,
            'origin_city' => 'Jepara',
            'origin_terminal' => 'Terminal Jepara',
            'destination_city' => 'Semarang',
            'destination_terminal' => 'Terminal Terboyo',
            'departure_date' => Carbon::today('Asia/Jakarta')->toDateString(),
            'departure_time' => Carbon::now('Asia/Jakarta')->subHours(3)->format('H:i'),
            'estimated_arrival_time' => Carbon::now('Asia/Jakarta')->subHour()->format('H:i'),
            'price' => 50000,
            'available_seats' => 29,
            'status' => 'available',
        ]);

        $order = Order::create([
            'user_id' => $customer->id,
            'route_id' => $route->id,
            'order_code' => 'PO-AUTO-COMPLETE',
            'ticket_code' => 'TKT-AUTO-COMPLETE',
            'total_passengers' => 1,
            'total_price' => 50000,
            'order_status' => 'paid',
            'payment_status' => 'verified',
            'checked_in_at' => null,
        ]);

        Payment::create([
            'order_id' => $order->id,
            'payment_method' => 'bca',
            'amount' => 50000,
            'status' => 'verified',
            'transaction_id' => 'TRX-AUTO-02',
            'paid_at' => now(),
        ]);

        // Eksekusi sync service
        app(OrderScheduleSyncService::class)->syncAllActive();

        $order->refresh();
        $this->assertNotNull($order->checked_in_at);
        $this->assertSame('completed', $order->order_status);

        // Di halaman admin terlihat status selesai
        $this->actingAs($admin)
            ->get(route('admin.orders.show', $order))
            ->assertOk()
            ->assertSee('Perjalanan Telah Selesai');
    }

    public function test_artisan_command_syncs_order_statuses(): void
    {
        $this->artisan('travel:sync-statuses')
            ->assertSuccessful()
            ->expectsOutputToContain('Sinkronisasi selesai.');
    }
}
