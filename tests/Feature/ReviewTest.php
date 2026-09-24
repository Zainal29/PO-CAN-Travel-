<?php

namespace Tests\Feature;

use App\Models\Bus;
use App\Models\Order;
use App\Models\Review;
use App\Models\TravelRoute;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReviewTest extends TestCase
{
    use RefreshDatabase;

    public function test_customer_can_review_completed_order_once(): void
    {
        $user = User::factory()->create(['role' => 'customer']);
        $order = $this->createOrder($user, 'completed');

        $this->actingAs($user)
            ->post(route('customer.orders.review', $order), [
                'rating' => 5,
                'comment' => 'Perjalanan nyaman.',
            ])
            ->assertSessionHas('success');

        $this->assertDatabaseHas('reviews', [
            'order_id' => $order->id,
            'user_id' => $user->id,
            'rating' => 5,
        ]);

        $this->actingAs($user)
            ->post(route('customer.orders.review', $order), [
                'rating' => 4,
            ])
            ->assertSessionHasErrors('review');

        $this->assertDatabaseCount('reviews', 1);
    }

    public function test_customer_cannot_review_incomplete_order(): void
    {
        $user = User::factory()->create(['role' => 'customer']);
        $order = $this->createOrder($user, 'confirmed');

        $this->actingAs($user)
            ->post(route('customer.orders.review', $order), [
                'rating' => 5,
            ])
            ->assertSessionHasErrors('review');

        $this->assertDatabaseCount('reviews', 0);
    }

    public function test_customer_cannot_review_another_customers_order(): void
    {
        $owner = User::factory()->create(['role' => 'customer']);
        $otherUser = User::factory()->create(['role' => 'customer']);
        $order = $this->createOrder($owner, 'completed');

        $this->actingAs($otherUser)
            ->post(route('customer.orders.review', $order), [
                'rating' => 5,
            ])
            ->assertForbidden();

        $this->assertDatabaseCount('reviews', 0);
    }

    public function test_owner_can_download_qr_for_verified_ticket(): void
    {
        $user = User::factory()->create(['role' => 'customer']);
        $order = $this->createOrder($user, 'paid');
        $order->update([
            'payment_status' => 'verified',
            'ticket_code' => 'CAN-TEST-QR',
        ]);

        $response = $this->actingAs($user)
            ->get(route('customer.orders.ticket.qr', $order));

        $response
            ->assertOk()
            ->assertHeader('Content-Type', 'image/png');
    }

    public function test_customer_cannot_download_another_customers_ticket_qr(): void
    {
        $owner = User::factory()->create(['role' => 'customer']);
        $otherUser = User::factory()->create(['role' => 'customer']);
        $order = $this->createOrder($owner, 'paid');
        $order->update([
            'payment_status' => 'verified',
            'ticket_code' => 'CAN-TEST-QR-2',
        ]);

        $this->actingAs($otherUser)
            ->get(route('customer.orders.ticket.qr', $order))
            ->assertForbidden();
    }

    public function test_admin_can_check_in_order_from_ticket_qr_payload(): void
    {
        $customer = User::factory()->create(['role' => 'customer']);
        $admin = User::factory()->create(['role' => 'admin']);
        $order = $this->createOrder($customer, 'paid');
        $order->update([
            'payment_status' => 'verified',
            'ticket_code' => 'CAN-TEST-SCAN',
        ]);
        $order->route->update([
            'departure_date' => now()->addDay()->toDateString(),
        ]);

        $this->actingAs($admin)
            ->patch(route('admin.orders.qr.check-in'), [
                'qr_payload' => "Order: {$order->order_code}\nTicket: {$order->ticket_code}",
            ])
            ->assertSessionHas('success');

        $this->assertNotNull($order->fresh()->checked_in_at);
    }

    public function test_invalid_ticket_qr_payload_is_rejected(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin)
            ->patch(route('admin.orders.qr.check-in'), [
                'qr_payload' => 'invalid qr payload',
            ])
            ->assertSessionHas('error');
    }

    private function createOrder(User $user, string $status): Order
    {
        $bus = Bus::create([
            'bus_code' => 'BUS-REVIEW-' . $user->id . '-' . $status,
            'bus_name' => 'Bus Review',
            'bus_type' => 'executive',
            'plate_number' => 'B 1234 RV',
            'total_seats' => 30,
            'status' => 'active',
        ]);

        $route = TravelRoute::create([
            'bus_id' => $bus->id,
            'origin_city' => 'Jepara',
            'origin_terminal' => 'Terminal Jepara',
            'destination_city' => 'Semarang',
            'destination_terminal' => 'Terminal Terboyo',
            'departure_date' => now()->subDay()->toDateString(),
            'departure_time' => '08:00',
            'estimated_arrival_time' => '11:00',
            'price' => 75000,
            'available_seats' => 28,
            'status' => 'available',
        ]);

        $order = Order::create([
            'user_id' => $user->id,
            'route_id' => $route->id,
            'order_code' => 'PO-REVIEW-' . $user->id . '-' . $status,
            'total_passengers' => 2,
            'total_price' => 150000,
            'payment_status' => in_array($status, ['paid', 'completed'], true) ? 'verified' : 'unpaid',
            'order_status' => $status,
        ]);

        $order->payment()->create([
            'payment_method' => 'bca',
            'amount' => 150000,
            'status' => in_array($status, ['paid', 'completed'], true) ? 'verified' : 'unpaid',
            'paid_at' => in_array($status, ['paid', 'completed'], true) ? now() : null,
        ]);

        return $order;
    }
}
