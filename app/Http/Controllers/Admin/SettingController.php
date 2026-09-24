<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        $settings = Setting::all()->pluck('value', 'key')->toArray();
        
        // Default values jika belum ada di database
        $data = [
            'app_name' => $settings['app_name'] ?? 'PO CAN Travel',
            'contact_email' => $settings['contact_email'] ?? 'support@pocantravel.com',
            'contact_phone' => $settings['contact_phone'] ?? '081234567890',
            'footer_address' => $settings['footer_address'] ?? 'Jl. Contoh No. 123, Jepara',
            'payment_expiry_hours' => $settings['payment_expiry_hours'] ?? '24',
            'cancellation_policy' => $settings['cancellation_policy'] ?? 'Pembatalan dapat dilakukan maksimal 3 jam sebelum keberangkatan.',
        ];

        return view('admin.settings.index', compact('data'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'app_name' => 'required|string|max:255',
            'contact_email' => 'required|email|max:255',
            'contact_phone' => 'required|string|max:20',
            'footer_address' => 'required|string',
            'payment_expiry_hours' => 'required|integer|min:1',
            'cancellation_policy' => 'required|string',
        ]);

        foreach ($validated as $key => $value) {
            Setting::set($key, $value);
        }

        return back()->with('success', 'Pengaturan sistem berhasil diperbarui.');
    }
}