<?php

namespace Tests\Feature;

use App\Models\Bus;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminBusManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_create_and_edit_a_bus_with_facilities(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin)
            ->get(route('admin.buses.create'))
            ->assertOk()
            ->assertSee('Tambah Bus');

        $this->actingAs($admin)
            ->post(route('admin.buses.store'), $this->busPayload())
            ->assertRedirect(route('admin.buses.index'));

        $bus = Bus::firstOrFail();
        $this->assertSame(['AC', 'WiFi'], $bus->facilities);

        $this->actingAs($admin)
            ->get(route('admin.buses.edit', $bus))
            ->assertOk()
            ->assertSee('name="facilities[]"', false)
            ->assertSee('value="AC"', false);

        $payload = $this->busPayload();
        $payload['bus_name'] = 'PO CAN Travel Executive Baru';
        $payload['facilities'] = 'AC, WiFi, Charger';

        $this->actingAs($admin)
            ->put(route('admin.buses.update', $bus), $payload)
            ->assertRedirect(route('admin.buses.index'));

        $bus->refresh();
        $this->assertSame('PO CAN Travel Executive Baru', $bus->bus_name);
        $this->assertSame(['AC', 'WiFi', 'Charger'], $bus->facilities);
    }

    private function busPayload(): array
    {
        return [
            'bus_code' => 'BUS-ADMIN-001',
            'bus_name' => 'PO CAN Travel Executive',
            'bus_type' => 'executive',
            'plate_number' => 'K 1234 CAN',
            'total_seats' => 30,
            'facilities' => ['AC', 'WiFi'],
            'description' => 'Bus untuk perjalanan antarkota.',
            'status' => 'active',
        ];
    }
}
