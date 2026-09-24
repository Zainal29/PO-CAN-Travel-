<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminAuthorizationTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_access_admin_dashboard(): void
    {
        $response = $this->get('/admin/dashboard');

        $response->assertRedirect('/login');
    }

    public function test_customer_cannot_access_admin_dashboard(): void
    {
        $customer = User::factory()->create([
            'role' => 'customer',
        ]);

        $response = $this
            ->actingAs($customer)
            ->get('/admin/dashboard');

        $response->assertForbidden();
    }

    public function test_admin_can_access_admin_dashboard(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        $response = $this
            ->actingAs($admin)
            ->get('/admin/dashboard');

        $response
            ->assertOk()
            ->assertSee('cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js')
            ->assertSee('new Chart(document.getElementById(\'revenueChart\')', false)
            ->assertSee('10 Pembayaran Terbaru')
            ->assertSee('Detail Armada Aktif')
            ->assertSee('Detail Rute');
    }

    public function test_admin_can_access_management_pages(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin)->get('/admin/buses')->assertOk();
        $this->actingAs($admin)->get('/admin/routes')->assertOk();
        $this->actingAs($admin)->get('/admin/customers')->assertOk();
        $this->actingAs($admin)->get('/admin/orders')->assertOk();
        $this->actingAs($admin)->get('/admin/payments')->assertOk();
    }

    public function test_admin_cannot_access_customer_pages(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin)
            ->get('/customer/dashboard')
            ->assertForbidden();
    }

    public function test_guest_can_browse_available_trip_pages_but_cannot_book(): void
    {
        $this->get('/perjalanan')->assertOk();
        $this->get('/customer/perjalanan/1/pesan')->assertRedirect('/login');
    }
}
