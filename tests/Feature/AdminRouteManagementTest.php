<?php

namespace Tests\Feature;

use App\Models\Bus;
use App\Models\Order;
use App\Models\TravelRoute;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminRouteManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_create_and_edit_a_route_with_database_time_format(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $bus = $this->createBus();
        $payload = $this->routePayload($bus);

        $this->actingAs($admin)
            ->post(route('admin.routes.store'), $payload)
            ->assertRedirect(route('admin.routes.index'));

        $route = TravelRoute::firstOrFail();
        $this->assertSame('08:00', $route->departure_time);

        $this->actingAs($admin)
            ->get(route('admin.routes.edit', $route))
            ->assertOk()
            ->assertSee('value="08:00"', false)
            ->assertSee('value="11:00"', false);

        $payload['price'] = 90000;
        $payload['departure_time'] = '09:30';
        $payload['estimated_arrival_time'] = '12:30';

        $this->actingAs($admin)
            ->put(route('admin.routes.update', $route), $payload)
            ->assertRedirect(route('admin.routes.index'));

        $this->assertDatabaseHas('routes', [
            'id' => $route->id,
            'price' => 90000,
            'departure_time' => '09:30',
            'estimated_arrival_time' => '12:30',
        ]);
    }

    public function test_admin_cannot_set_remaining_seats_beyond_capacity_after_bookings(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $customer = User::factory()->create(['role' => 'customer']);
        $bus = $this->createBus();
        $route = TravelRoute::create($this->routePayload($bus));
        Order::create([
            'user_id' => $customer->id,
            'route_id' => $route->id,
            'order_code' => 'PO-ROUTE-001',
            'total_passengers' => 2,
            'total_price' => 150000,
            'payment_status' => 'unpaid',
            'order_status' => 'pending',
            'expired_at' => now()->addMinutes(30),
        ]);

        $payload = $this->routePayload($bus);
        $payload['available_seats'] = 30;

        $this->actingAs($admin)
            ->from(route('admin.routes.edit', $route))
            ->put(route('admin.routes.update', $route), $payload)
            ->assertRedirect(route('admin.routes.edit', $route))
            ->assertSessionHasErrors('available_seats');
    }

    private function createBus(): Bus
    {
        return Bus::create([
            'bus_code' => 'BUS-ROUTE',
            'bus_name' => 'Bus Route',
            'bus_type' => 'executive',
            'plate_number' => 'B 5000 RTE',
            'total_seats' => 30,
            'status' => 'active',
        ]);
    }

    private function routePayload(Bus $bus): array
    {
        return [
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
        ];
    }
}
