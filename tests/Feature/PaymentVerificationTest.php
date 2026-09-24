<?php

namespace Tests\Feature;

use App\Models\Bus;
use App\Models\Order;
use App\Models\TravelRoute;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PaymentVerificationTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_verify_a_submitted_payment_only(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $customer = User::factory()->create(['role' => 'customer']);
        $bus = Bus::create([
            'bus_code' => 'BUS-PAYMENT',
            'bus_name' => 'Bus Payment',
            'bus_type' => 'executive',
            'plate_number' => 'B 2000 PAY',
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
            'order_code' => 'PO-PAYMENT-001',
            'total_passengers' => 1,
            'total_price' => 75000,
            'payment_status' => 'pending',
            'order_status' => 'pending',
            'expired_at' => now()->addMinutes(30),
        ]);
        $payment = $order->payment()->create([
            'payment_method' => 'transfer',
            'amount' => 75000,
            'status' => 'pending',
        ]);

        $this->actingAs($admin)
            ->patch(route('admin.payments.verify', $payment), ['notes' => 'Bukti valid'])
            ->assertRedirect();

        $this->assertDatabaseHas('payments', ['id' => $payment->id, 'status' => 'verified']);
        $this->assertDatabaseHas('orders', ['id' => $order->id, 'payment_status' => 'verified', 'order_status' => 'paid']);

        $order->refresh();
        $this->assertNotNull($order->ticket_code);

        $this->actingAs($customer)
            ->get(route('customer.orders.ticket', $order))
            ->assertOk()
            ->assertSee($order->ticket_code)
            ->assertSee('Penumpang dan kursi');

        $this->actingAs(User::factory()->create(['role' => 'customer']))
            ->get(route('customer.orders.ticket', $order))
            ->assertForbidden();

        $this->actingAs($admin)
            ->patch(route('admin.orders.check-in', $order))
            ->assertRedirect();

        $this->assertDatabaseHas('orders', ['id' => $order->id]);
        $this->assertNotNull($order->fresh()->checked_in_at);

        $this->actingAs($admin)
            ->patch(route('admin.orders.status', $order), ['order_status' => 'completed'])
            ->assertRedirect();

        $this->assertDatabaseHas('orders', ['id' => $order->id, 'order_status' => 'completed']);
    }

    public function test_confirmed_order_can_be_paid_and_completed(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $order = Order::create([
            'user_id' => User::factory()->create(['role' => 'customer'])->id,
            'route_id' => TravelRoute::create([
                'bus_id' => Bus::create([
                    'bus_code' => 'BUS-PENDING',
                    'bus_name' => 'Bus Pending',
                    'bus_type' => 'economy',
                    'plate_number' => 'B 3000 PND',
                    'total_seats' => 20,
                    'status' => 'active',
                ])->id,
                'origin_city' => 'Jepara',
                'origin_terminal' => 'Terminal Jepara',
                'destination_city' => 'Semarang',
                'destination_terminal' => 'Terminal Terboyo',
                'departure_date' => now()->addDay()->toDateString(),
                'departure_time' => '08:00',
                'estimated_arrival_time' => '11:00',
                'price' => 75000,
                'available_seats' => 20,
                'status' => 'available',
            ])->id,
            'order_code' => 'PO-PENDING-001',
            'total_passengers' => 1,
            'total_price' => 75000,
            'payment_status' => 'unpaid',
            'order_status' => 'pending',
            'expired_at' => now()->addMinutes(30),
        ]);

        $this->actingAs($admin)
            ->from(route('admin.orders.show', $order))
            ->patch(route('admin.orders.status', $order), ['order_status' => 'confirmed'])
            ->assertRedirect();

        $this->assertDatabaseHas('orders', ['id' => $order->id, 'order_status' => 'confirmed']);

        $payment = $order->payment()->create([
            'payment_method' => 'transfer',
            'amount' => 75000,
            'status' => 'pending',
        ]);

        $this->actingAs($admin)
            ->patch(route('admin.payments.verify', $payment), ['notes' => 'Bukti valid'])
            ->assertRedirect();

        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'payment_status' => 'verified',
            'order_status' => 'paid',
        ]);

        $this->actingAs($admin)
            ->from(route('admin.orders.show', $order))
            ->patch(route('admin.orders.status', $order), ['order_status' => 'completed'])
            ->assertRedirect();

        $this->assertDatabaseHas('orders', ['id' => $order->id, 'order_status' => 'completed']);
    }
}
