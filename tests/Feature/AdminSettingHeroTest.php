<?php

namespace Tests\Feature;

use App\Models\Bus;
use App\Models\TravelRoute;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdminSettingHeroTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_update_hero_settings_and_see_them_on_home_page(): void
    {
        Storage::fake('public');

        $admin = User::factory()->create(['role' => 'admin']);

        $heroImage = UploadedFile::fake()->image('hero-banner.jpg', 1920, 1080);

        $response = $this->actingAs($admin)
            ->post(route('admin.settings.update'), [
                'app_name' => 'PO CAN Travel Keren',
                'contact_email' => 'admin@pocan.com',
                'contact_phone' => '08123456789',
                'footer_address' => 'Jepara Kota Ukir',
                'payment_expiry_hours' => 24,
                'cancellation_policy' => 'Kebijakan pembatalan 2 jam',
                'hero_badge' => 'Armada Super Mewah',
                'hero_title' => 'Nikmati Liburan Nyaman Bersama PO CAN',
                'hero_subtitle' => 'Tiket bus online terpercaya dengan kursi ergonomis dan AC dingin.',
                'hero_image' => $heroImage,
            ]);

        $response->assertSessionHas('success');

        // Pastikan foto tersimpan di storage public
        $files = Storage::disk('public')->files('settings');
        $this->assertNotEmpty($files);

        // Buka halaman depan (Home)
        $homeResponse = $this->get(route('home'));
        $homeResponse->assertOk()
            ->assertSee('Armada Super Mewah')
            ->assertSee('Nikmati Liburan Nyaman Bersama PO CAN')
            ->assertSee('Tiket bus online terpercaya dengan kursi ergonomis dan AC dingin.')
            ->assertSee($files[0]);
    }

    public function test_bus_image_is_displayed_on_home_schedule_search_and_detail_pages(): void
    {
        Storage::fake('public');

        $customer = User::factory()->create(['role' => 'customer']);

        $busImage = UploadedFile::fake()->image('scania-bus.jpg', 800, 600);
        $storedImagePath = $busImage->store('buses', 'public');

        $bus = Bus::create([
            'bus_code' => 'BUS-SCANIA-01',
            'bus_name' => 'PO CAN Scania Double Glass',
            'bus_type' => 'executive',
            'plate_number' => 'K 9999 AA',
            'total_seats' => 30,
            'facilities' => ['AC', 'WiFi', 'Audio'],
            'description' => 'Bus mewah dengan suspensi udara Scania.',
            'image' => $storedImagePath,
            'status' => 'active',
        ]);

        $route = TravelRoute::create([
            'bus_id' => $bus->id,
            'origin_city' => 'Jepara',
            'origin_terminal' => 'Terminal Jepara',
            'destination_city' => 'Semarang',
            'destination_terminal' => 'Terminal Terboyo',
            'departure_date' => Carbon::tomorrow('Asia/Jakarta')->toDateString(),
            'departure_time' => '08:00',
            'estimated_arrival_time' => '11:00',
            'price' => 85000,
            'available_seats' => 30,
            'status' => 'available',
        ]);

        // 1. Home page menampilkan foto bus
        $homeResponse = $this->get(route('home'));
        $homeResponse->assertOk()
            ->assertSee('PO CAN Scania Double Glass')
            ->assertSee($storedImagePath);

        // 2. Search / Jadwal Bus page menampilkan foto bus
        $searchResponse = $this->get(route('customer.trips.index', [
            'origin_city' => 'Jepara',
            'destination_city' => 'Semarang',
            'departure_date' => Carbon::tomorrow('Asia/Jakarta')->toDateString(),
            'passengers' => 1,
        ]));
        $searchResponse->assertOk()
            ->assertSee('PO CAN Scania Double Glass')
            ->assertSee($storedImagePath);

        // 3. Detail Perjalanan menampilkan foto bus
        $detailResponse = $this->get(route('customer.trips.show', $route));
        $detailResponse->assertOk()
            ->assertSee('PO CAN Scania Double Glass')
            ->assertSee($storedImagePath);
    }
}
