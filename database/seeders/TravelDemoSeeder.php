<?php

namespace Database\Seeders;

use App\Models\Bus;
use App\Models\TravelRoute;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class TravelDemoSeeder extends Seeder
{
    public function run(): void
    {
        /*
        |--------------------------------------------------------------------------
        | Users
        |--------------------------------------------------------------------------
        */

        $customer = User::updateOrCreate(
            ['email' => 'customer@pocantravel.test'],
            [
                'name' => 'Customer Demo',
                'password' => Hash::make('password'),
                'phone' => '081234567890',
                'role' => 'customer',
                'address' => 'Jepara, Jawa Tengah',
                'birth_date' => '2005-05-10',
                'gender' => 'male',
            ]
        );

        $customer2 = User::updateOrCreate(
            ['email' => 'customer2@pocantravel.test'],
            [
                'name' => 'Customer Dua',
                'password' => Hash::make('password'),
                'phone' => '081234567892',
                'role' => 'customer',
                'address' => 'Semarang, Jawa Tengah',
                'birth_date' => '2004-08-15',
                'gender' => 'female',
            ]
        );

        User::updateOrCreate(
            ['email' => 'admin@pocantravel.test'],
            [
                'name' => 'Admin PO CAN Travel',
                'password' => Hash::make('password'),
                'phone' => '081234567891',
                'role' => 'admin',
                'address' => 'Jepara, Jawa Tengah',
            ]
        );

        /*
        |--------------------------------------------------------------------------
        | Bus 1 - Executive
        |--------------------------------------------------------------------------
        */

        $bus1 = Bus::updateOrCreate(
            ['bus_code' => 'BUS-001'],
            [
                'bus_name' => 'PO CAN Travel Executive 1',
                'bus_type' => 'executive',
                'plate_number' => 'K 1234 AB',
                'total_seats' => 30,
                'facilities' => [
                    'AC',
                    'WiFi',
                    'USB Charger',
                    'Reclining Seat',
                    'Entertainment',
                ],
                'description' => 'Bus executive dengan fasilitas lengkap dan nyaman.',
                'image' => null,
                'status' => 'active',
            ]
        );

        /*
        |--------------------------------------------------------------------------
        | Bus 2 - Economy
        |--------------------------------------------------------------------------
        */

        $bus2 = Bus::updateOrCreate(
            ['bus_code' => 'BUS-002'],
            [
                'bus_name' => 'PO CAN Travel Economy 1',
                'bus_type' => 'economy',
                'plate_number' => 'K 5678 CD',
                'total_seats' => 30,
                'facilities' => [
                    'AC',
                    'USB Charger',
                ],
                'description' => 'Bus economy dengan harga terjangkau.',
                'image' => null,
                'status' => 'active',
            ]
        );

        /*
        |--------------------------------------------------------------------------
        | Bus 3 - VIP
        |--------------------------------------------------------------------------
        */

        $bus3 = Bus::updateOrCreate(
            ['bus_code' => 'BUS-003'],
            [
                'bus_name' => 'PO CAN Travel VIP 1',
                'bus_type' => 'vip',
                'plate_number' => 'K 9012 EF',
                'total_seats' => 25,
                'facilities' => [
                    'AC',
                    'WiFi',
                    'USB Charger',
                    'Reclining Seat',
                    'Leg Rest',
                    'Entertainment',
                ],
                'description' => 'Bus VIP dengan kursi lebih luas dan fasilitas premium.',
                'image' => null,
                'status' => 'active',
            ]
        );

        /*
        |--------------------------------------------------------------------------
        | Bus 4 - Super VIP
        |--------------------------------------------------------------------------
        */

        $bus4 = Bus::updateOrCreate(
            ['bus_code' => 'BUS-004'],
            [
                'bus_name' => 'PO CAN Travel Super VIP',
                'bus_type' => 'super_vip',
                'plate_number' => 'K 3456 GH',
                'total_seats' => 20,
                'facilities' => [
                    'AC',
                    'WiFi',
                    'USB Charger',
                    'Reclining Seat',
                    'Leg Rest',
                    'Entertainment',
                    'Blanket',
                    'Pillow',
                ],
                'description' => 'Bus Super VIP dengan fasilitas perjalanan premium.',
                'image' => null,
                'status' => 'active',
            ]
        );

        /*
        |--------------------------------------------------------------------------
        | Bus 5 - Maintenance
        |--------------------------------------------------------------------------
        */

        $bus5 = Bus::updateOrCreate(
            ['bus_code' => 'BUS-005'],
            [
                'bus_name' => 'PO CAN Travel Maintenance',
                'bus_type' => 'economy',
                'plate_number' => 'K 7890 IJ',
                'total_seats' => 30,
                'facilities' => [
                    'AC',
                ],
                'description' => 'Bus sedang dalam proses perawatan.',
                'image' => null,
                'status' => 'maintenance',
            ]
        );

        /*
        |--------------------------------------------------------------------------
        | Route 1
        |--------------------------------------------------------------------------
        | Jepara -> Semarang
        | Executive
        */

        TravelRoute::updateOrCreate(
            [
                'bus_id' => $bus1->id,
                'origin_city' => 'Jepara',
                'destination_city' => 'Semarang',
                'departure_date' => now()->addDays(1)->toDateString(),
                'departure_time' => '08:00:00',
            ],
            [
                'origin_terminal' => 'Terminal Jepara',
                'destination_terminal' => 'Terminal Terboyo',
                'estimated_arrival_time' => '11:00:00',
                'price' => 75000,
                'available_seats' => 30,
                'status' => 'available',
            ]
        );

        /*
        |--------------------------------------------------------------------------
        | Route 2
        |--------------------------------------------------------------------------
        | Jepara -> Semarang
        | Economy
        */

        TravelRoute::updateOrCreate(
            [
                'bus_id' => $bus2->id,
                'origin_city' => 'Jepara',
                'destination_city' => 'Semarang',
                'departure_date' => now()->addDays(1)->toDateString(),
                'departure_time' => '10:00:00',
            ],
            [
                'origin_terminal' => 'Terminal Jepara',
                'destination_terminal' => 'Terminal Terboyo',
                'estimated_arrival_time' => '13:00:00',
                'price' => 60000,
                'available_seats' => 30,
                'status' => 'available',
            ]
        );

        /*
        |--------------------------------------------------------------------------
        | Route 3
        |--------------------------------------------------------------------------
        | Jepara -> Semarang
        | VIP
        */

        TravelRoute::updateOrCreate(
            [
                'bus_id' => $bus3->id,
                'origin_city' => 'Jepara',
                'destination_city' => 'Semarang',
                'departure_date' => now()->addDays(1)->toDateString(),
                'departure_time' => '13:00:00',
            ],
            [
                'origin_terminal' => 'Terminal Jepara',
                'destination_terminal' => 'Terminal Terboyo',
                'estimated_arrival_time' => '16:00:00',
                'price' => 100000,
                'available_seats' => 25,
                'status' => 'available',
            ]
        );

        /*
        |--------------------------------------------------------------------------
        | Route 4
        |--------------------------------------------------------------------------
        | Jepara -> Kudus
        */

        TravelRoute::updateOrCreate(
            [
                'bus_id' => $bus2->id,
                'origin_city' => 'Jepara',
                'destination_city' => 'Kudus',
                'departure_date' => now()->addDays(1)->toDateString(),
                'departure_time' => '07:30:00',
            ],
            [
                'origin_terminal' => 'Terminal Jepara',
                'destination_terminal' => 'Terminal Jati Kudus',
                'estimated_arrival_time' => '09:00:00',
                'price' => 40000,
                'available_seats' => 30,
                'status' => 'available',
            ]
        );

        /*
        |--------------------------------------------------------------------------
        | Route 5
        |--------------------------------------------------------------------------
        | Semarang -> Jepara
        */

        TravelRoute::updateOrCreate(
            [
                'bus_id' => $bus1->id,
                'origin_city' => 'Semarang',
                'destination_city' => 'Jepara',
                'departure_date' => now()->addDays(2)->toDateString(),
                'departure_time' => '09:00:00',
            ],
            [
                'origin_terminal' => 'Terminal Terboyo',
                'destination_terminal' => 'Terminal Jepara',
                'estimated_arrival_time' => '12:00:00',
                'price' => 75000,
                'available_seats' => 30,
                'status' => 'available',
            ]
        );

        /*
        |--------------------------------------------------------------------------
        | Route 6
        |--------------------------------------------------------------------------
        | Semarang -> Jepara
        | Super VIP
        */

        TravelRoute::updateOrCreate(
            [
                'bus_id' => $bus4->id,
                'origin_city' => 'Semarang',
                'destination_city' => 'Jepara',
                'departure_date' => now()->addDays(2)->toDateString(),
                'departure_time' => '14:00:00',
            ],
            [
                'origin_terminal' => 'Terminal Terboyo',
                'destination_terminal' => 'Terminal Jepara',
                'estimated_arrival_time' => '17:00:00',
                'price' => 125000,
                'available_seats' => 20,
                'status' => 'available',
            ]
        );

        /*
        |--------------------------------------------------------------------------
        | Route 7 - Hampir Penuh
        |--------------------------------------------------------------------------
        */

        TravelRoute::updateOrCreate(
            [
                'bus_id' => $bus3->id,
                'origin_city' => 'Jepara',
                'destination_city' => 'Demak',
                'departure_date' => now()->addDays(3)->toDateString(),
                'departure_time' => '08:30:00',
            ],
            [
                'origin_terminal' => 'Terminal Jepara',
                'destination_terminal' => 'Terminal Demak',
                'estimated_arrival_time' => '10:30:00',
                'price' => 65000,
                'available_seats' => 3,
                'status' => 'available',
            ]
        );

        /*
        |--------------------------------------------------------------------------
        | Route 8 - Full
        |--------------------------------------------------------------------------
        */

        TravelRoute::updateOrCreate(
            [
                'bus_id' => $bus2->id,
                'origin_city' => 'Jepara',
                'destination_city' => 'Pati',
                'departure_date' => now()->addDays(3)->toDateString(),
                'departure_time' => '09:00:00',
            ],
            [
                'origin_terminal' => 'Terminal Jepara',
                'destination_terminal' => 'Terminal Pati',
                'estimated_arrival_time' => '11:00:00',
                'price' => 55000,
                'available_seats' => 0,
                'status' => 'full',
            ]
        );

        /*
        |--------------------------------------------------------------------------
        | Route 9 - Cancelled
        |--------------------------------------------------------------------------
        */

        TravelRoute::updateOrCreate(
            [
                'bus_id' => $bus1->id,
                'origin_city' => 'Jepara',
                'destination_city' => 'Rembang',
                'departure_date' => now()->addDays(4)->toDateString(),
                'departure_time' => '10:00:00',
            ],
            [
                'origin_terminal' => 'Terminal Jepara',
                'destination_terminal' => 'Terminal Rembang',
                'estimated_arrival_time' => '13:00:00',
                'price' => 85000,
                'available_seats' => 30,
                'status' => 'cancelled',
            ]
        );

        /*
        |--------------------------------------------------------------------------
        | Route 10 - Maintenance Bus
        |--------------------------------------------------------------------------
        */

        TravelRoute::updateOrCreate(
            [
                'bus_id' => $bus5->id,
                'origin_city' => 'Jepara',
                'destination_city' => 'Semarang',
                'departure_date' => now()->addDays(5)->toDateString(),
                'departure_time' => '08:00:00',
            ],
            [
                'origin_terminal' => 'Terminal Jepara',
                'destination_terminal' => 'Terminal Terboyo',
                'estimated_arrival_time' => '11:00:00',
                'price' => 55000,
                'available_seats' => 30,
                'status' => 'cancelled',
            ]
        );

        /*
        |--------------------------------------------------------------------------
        | Informasi Seeder
        |--------------------------------------------------------------------------
        */

        $this->command->newLine();

        $this->command->info('==========================================');
        $this->command->info('  DATA DEMO PO CAN TRAVEL BERHASIL');
        $this->command->info('==========================================');

        $this->command->newLine();

        $this->command->info('CUSTOMER 1');
        $this->command->info('Email    : customer@pocantravel.test');
        $this->command->info('Password : password');

        $this->command->newLine();

        $this->command->info('CUSTOMER 2');
        $this->command->info('Email    : customer2@pocantravel.test');
        $this->command->info('Password : password');

        $this->command->newLine();

        $this->command->info('ADMIN');
        $this->command->info('Email    : admin@pocantravel.test');
        $this->command->info('Password : password');

        $this->command->newLine();

        $this->command->info('Bus aktif       : 4');
        $this->command->info('Bus maintenance : 1');
        $this->command->info('Total rute      : 10');

        $this->command->newLine();
    }
}