<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Payment;
use App\Models\Order;
use App\Models\TravelRoute;
use App\Models\Bus;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminPaymentExportTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_access_payment_export(): void
    {
        $response = $this->get(route('admin.payments.export-excel'));
        $response->assertRedirect(route('login'));
    }

    public function test_customer_cannot_access_payment_export(): void
    {
        $customer = User::factory()->create(['role' => 'customer']);

        $response = $this->actingAs($customer)->get(route('admin.payments.export-excel'));
        $response->assertForbidden();
    }

    public function test_admin_can_export_payments_excel(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $customer = User::factory()->create(['role' => 'customer']);

        $bus = Bus::create([
            'bus_name' => 'CAN Executive 1',
            'bus_code' => 'BUS-001',
            'plate_number' => 'K 1234 AB',
            'total_seats' => 30,
            'bus_type' => 'executive',
            'status' => 'active',
            'facilities' => ['AC', 'WiFi'],
        ]);

        $route = TravelRoute::create([
            'bus_id' => $bus->id,
            'origin_city' => 'Jepara',
            'destination_city' => 'Semarang',
            'origin_terminal' => 'Terminal Jepara',
            'destination_terminal' => 'Terminal Terboyo',
            'departure_date' => now()->addDays(2)->toDateString(),
            'departure_time' => '08:00:00',
            'estimated_arrival_time' => '11:00:00',
            'price' => 75000,
            'available_seats' => 30,
            'status' => 'available',
        ]);

        $order = Order::create([
            'user_id' => $customer->id,
            'route_id' => $route->id,
            'order_code' => 'PO-TEST-001',
            'total_passengers' => 1,
            'total_price' => 75000,
            'payment_status' => 'verified',
            'order_status' => 'paid',
        ]);

        $payment = Payment::create([
            'order_id' => $order->id,
            'transaction_id' => 'TRX-PO-TEST-001',
            'payment_method' => 'bca',
            'amount' => 75000,
            'status' => 'verified',
            'paid_at' => now(),
        ]);

        $response = $this->actingAs($admin)->get(route('admin.payments.export-excel'));

        $response->assertOk();
        $this->assertStringContainsString('text/csv', $response->headers->get('Content-Type'));
        $this->assertStringContainsString('attachment; filename=', $response->headers->get('Content-Disposition'));

        ob_start();
        $response->sendContent();
        $content = ob_get_clean();

        $this->assertStringContainsString('LAPORAN KEUANGAN & PENDAPATAN', $content);
        $this->assertStringContainsString('PO-TEST-001', $content);
        $this->assertStringContainsString('75000', $content);
    }
}
