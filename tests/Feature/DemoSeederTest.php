<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DemoSeederTest extends TestCase
{
    use RefreshDatabase;

    public function test_demo_seed_creates_working_admin_customer_and_routes(): void
    {
        $this->seed();

        $this->assertDatabaseHas('users', [
            'email' => 'admin@pocantravel.test',
            'role' => 'admin',
        ]);
        $this->assertDatabaseHas('users', [
            'email' => 'customer@pocantravel.test',
            'role' => 'customer',
        ]);
        $this->assertDatabaseCount('buses', 5);
        $this->assertDatabaseCount('routes', 10);
        $this->assertDatabaseHas('buses', [
            'bus_code' => 'BUS-005',
            'status' => 'maintenance',
        ]);
        $this->assertDatabaseHas('routes', [
            'origin_city' => 'Jepara',
            'destination_city' => 'Pati',
            'status' => 'full',
        ]);
    }
}
