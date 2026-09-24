<?php

namespace Tests\Feature;

use App\Models\Bus;
use App\Models\Order;
use App\Models\TravelRoute;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PaymentSimulationTest extends TestCase
{
    use RefreshDatabase;

    public function test_customer_can_open_payment_page_without_changing_status(): void
    {
        [$customer, $order] = $this->createPendingOrder();

        $this->actingAs($customer)
            ->get(route('customer.payments.create', $order))
            ->assertOk()
            ->assertSee('simulasi pembayaran internal')
            ->assertSee('Bayar Sekarang');

        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'order_status' => 'pending',
            'payment_status' => 'unpaid',
        ]);
        $this->assertDatabaseHas('payments', [
            'order_id' => $order->id,
            'status' => 'unpaid',
        ]);
    }

    /** @dataProvider paymentMethods */
    public function test_customer_can_complete_simulated_payment(string $method): void
    {
        [$customer, $order] = $this->createPendingOrder();

        $this->actingAs($customer)
            ->post(route('customer.payments.store', $order), [
                'payment_method' => $method,
                'payer_name' => 'Nama Pembayar',
                'payer_phone' => '081234567890',
            ])
            ->assertRedirect(route('customer.payments.success', $order));

        $this->assertDatabaseHas('payments', [
            'order_id' => $order->id,
            'payment_method' => $method,
            'status' => 'verified',
            'amount' => 150000,
        ]);
        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'order_status' => 'paid',
            'payment_status' => 'verified',
        ]);

        $payment = $order->fresh()->payment;
        $this->assertNotNull($payment->paid_at);
        $this->assertStringStartsWith('PAY-', $payment->transaction_id);
        $this->assertNotNull($order->fresh()->ticket_code);

        $this->actingAs($customer)
            ->get(route('customer.payments.success', $order))
            ->assertOk()
            ->assertSee('Pembayaran Berhasil')
            ->assertSee($payment->transaction_id);
    }

    public static function paymentMethods(): array
    {
        return [['bca'], ['bri'], ['qris']];
    }

    public function test_customer_cannot_pay_another_customers_order(): void
    {
        [$owner, $order] = $this->createPendingOrder();
        $otherCustomer = User::factory()->create(['role' => 'customer']);

        $this->actingAs($otherCustomer)
            ->post(route('customer.payments.store', $order), [
                'payment_method' => 'bca',
                'payer_name' => 'Pembayar',
                'payer_phone' => '081234567890',
            ])
            ->assertForbidden();
    }

    public function test_payment_form_requires_valid_simulation_data(): void
    {
        [$customer, $order] = $this->createPendingOrder();

        $this->actingAs($customer)
            ->post(route('customer.payments.store', $order), [])
            ->assertSessionHasErrors(['payment_method', 'payer_name', 'payer_phone']);

        $this->assertDatabaseHas('payments', [
            'order_id' => $order->id,
            'status' => 'unpaid',
        ]);
    }

    public function test_rejected_payment_can_be_retried_before_expiration(): void
    {
        [$customer, $order] = $this->createPendingOrder();
        $order->payment()->update(['status' => 'rejected']);
        $order->update(['payment_status' => 'rejected']);

        $this->actingAs($customer)
            ->post(route('customer.payments.store', $order), [
                'payment_method' => 'qris',
                'payer_name' => 'Pembayar Retry',
                'payer_phone' => '081234567890',
            ])
            ->assertRedirect(route('customer.payments.success', $order));

        $this->assertDatabaseHas('payments', [
            'order_id' => $order->id,
            'status' => 'verified',
            'payment_method' => 'qris',
        ]);
    }

    /** @dataProvider terminalOrderStatuses */
    public function test_terminal_order_cannot_be_paid(string $orderStatus, string $paymentStatus): void
    {
        [$customer, $order] = $this->createPendingOrder();
        $order->update([
            'order_status' => $orderStatus,
            'payment_status' => $paymentStatus,
        ]);
        $order->payment()->update(['status' => $paymentStatus]);

        $this->actingAs($customer)
            ->post(route('customer.payments.store', $order), [
                'payment_method' => 'bca',
                'payer_name' => 'Pembayar',
                'payer_phone' => '081234567890',
            ])
            ->assertSessionHasErrors('payment');

        $this->assertDatabaseHas('payments', [
            'order_id' => $order->id,
            'status' => $paymentStatus,
        ]);
    }

    public static function terminalOrderStatuses(): array
    {
        return [
            ['paid', 'verified'],
            ['completed', 'verified'],
            ['cancelled', 'rejected'],
            ['expired', 'rejected'],
        ];
    }

    public function test_expired_order_cannot_be_paid_and_keeps_released_seats(): void
    {
        [$customer, $order] = $this->createPendingOrder();
        $order->update([
            'expired_at' => now()->subMinute(),
            'order_status' => 'expired',
            'payment_status' => 'rejected',
            'seats_released' => true,
        ]);
        $order->payment()->update(['status' => 'rejected']);

        $this->actingAs($customer)
            ->post(route('customer.payments.store', $order), [
                'payment_method' => 'bca',
                'payer_name' => 'Pembayar',
                'payer_phone' => '081234567890',
            ])
            ->assertSessionHasErrors('payment');

        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'order_status' => 'expired',
            'seats_released' => true,
        ]);
    }

    private function createPendingOrder(): array
    {
        $customer = User::factory()->create(['role' => 'customer']);
        $suffix = strtoupper(substr(str_replace('.', '', uniqid()), -6));
        $bus = Bus::create([
            'bus_code' => 'BUS-' . $suffix,
            'bus_name' => 'Bus Simulasi',
            'bus_type' => 'economy',
            'plate_number' => 'K ' . rand(1000, 9999) . ' SIM',
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
            'user_id' => $customer->id,
            'route_id' => $route->id,
            'order_code' => 'PO-' . $suffix,
            'total_passengers' => 2,
            'total_price' => 150000,
            'payment_status' => 'unpaid',
            'order_status' => 'pending',
            'expired_at' => now()->addMinutes(30),
        ]);
        $order->payment()->create([
            'payment_method' => 'bca',
            'amount' => 150000,
            'status' => 'unpaid',
        ]);

        return [$customer, $order];
    }
}
