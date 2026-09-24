<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
       User::create([
    'name' => 'PO CAN Travel Admin',
    'email' => 'admin@pocantravel.test',
    'password' => Hash::make('password'),
    'role' => 'admin',
]);

User::create([
    'name' => 'Customer PO CAN',
    'email' => 'customer@pocantravel.test',
    'password' => Hash::make('password'),
    'role' => 'customer',
]);
    }
}
