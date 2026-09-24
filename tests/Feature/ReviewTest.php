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

        return Order::create([
            'user_id' => $user->id,
            'route_id' => $route->id,
            'order_code' => 'PO-REVIEW-' . $user->id . '-' . $status,
            'total_passengers' => 2,
            'total_price' => 150000,
            'payment_status' => 'verified',
            'order_status' => $status,
        ]);
    }
}
