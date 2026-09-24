<?php

namespace Tests\Feature;

use App\Models\Bus;
use App\Models\TravelRoute;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TripSearchFilterTest extends TestCase
{
    use RefreshDatabase;

    public function test_search_only_returns_bookable_routes_matching_the_selected_filters(): void
    {
        $date = now()->addDay()->toDateString();

        $matchingBus = $this->createBus('BUS-SEARCH-1', 'Bus Sesuai', 'executive', 'active');
        $inactiveBus = $this->createBus('BUS-SEARCH-2', 'Bus Nonaktif', 'executive', 'maintenance');
        $otherTypeBus = $this->createBus('BUS-SEARCH-3', 'Bus Economy', 'economy', 'active');

        $this->createRoute($matchingBus, $date, '08:00', 4, 90000);
        $this->createRoute($inactiveBus, $date, '08:00', 10, 80000);
        $this->createRoute($otherTypeBus, $date, '14:00', 10, 70000);

        $this->get(route('customer.trips.index', [
            'origin_city' => 'Jepara',
            'destination_city' => 'Semarang',
            'departure_date' => $date,
            'passengers' => 3,
            'bus_type' => 'executive',
            'departure_period' => 'morning',
            'max_price' => 100000,
        ]))
            ->assertOk()
            ->assertSee('Bus Sesuai')
            ->assertDontSee('Bus Nonaktif')
            ->assertSee('1 perjalanan ditemukan.');
    }

    public function test_trip_page_shows_only_available_routes_and_related_active_buses(): void
    {
        $date = now()->addDay()->toDateString();
        $availableBus = $this->createBus('BUS-LIST-1', 'Bus Jadwal Aktif', 'executive', 'active');
        $maintenanceBus = $this->createBus('BUS-LIST-2', 'Bus Perawatan', 'economy', 'maintenance');

        $this->createRoute($availableBus, $date, '08:00', 5, 80000);
        $this->createRoute($maintenanceBus, $date, '09:00', 5, 70000);

        $this->get(route('customer.trips.index'))
            ->assertOk()
            ->assertSee('Perjalanan yang dapat dipesan')
            ->assertSee('Bus Jadwal Aktif')
            ->assertSee('Bus yang memiliki perjalanan tersedia')
            ->assertDontSee('Bus Perawatan');
    }

    public function test_search_ignores_city_spacing_and_explains_when_optional_filters_exclude_a_schedule(): void
    {
        $date = now()->addDay()->toDateString();
        $bus = $this->createBus('BUS-NORMALIZE', 'Bus Normalisasi', 'economy', 'active');
        $this->createRoute($bus, $date, '10:00', 10, 75000);

        $this->get(route('customer.trips.index', [
            'origin_city' => '  JEPARA ',
            'destination_city' => ' semarang  ',
            'departure_date' => $date,
            'max_price' => 10000,
        ]))
            ->assertOk()
            ->assertSee('Perjalanan tidak ditemukan')
            ->assertSee('1 jadwal tersedia untuk rute ini');
    }

    private function createBus(string $code, string $name, string $type, string $status): Bus
    {
        return Bus::create([
            'bus_code' => $code,
            'bus_name' => $name,
            'bus_type' => $type,
            'plate_number' => 'K ' . substr($code, -1) . '000 SRH',
            'total_seats' => 30,
            'status' => $status,
        ]);
    }

    private function createRoute(Bus $bus, string $date, string $time, int $seats, int $price): void
    {
        TravelRoute::create([
            'bus_id' => $bus->id,
            'origin_city' => 'Jepara',
            'origin_terminal' => 'Terminal Jepara',
            'destination_city' => 'Semarang',
            'destination_terminal' => 'Terminal Terboyo',
            'departure_date' => $date,
            'departure_time' => $time,
            'estimated_arrival_time' => '11:00',
            'price' => $price,
            'available_seats' => $seats,
            'status' => 'available',
        ]);
    }
}
