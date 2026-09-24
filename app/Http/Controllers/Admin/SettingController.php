<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Storage;

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
            'hero_badge' => $settings['hero_badge'] ?? 'Tiket Resmi Bus Antarkota',
            'hero_title' => $settings['hero_title'] ?? 'Perjalanan Anda, dimulai dari jadwal yang tepat.',
            'hero_subtitle' => $settings['hero_subtitle'] ?? 'Pesan tiket bus antarkota resmi PO CAN Travel dengan jadwal terkonfirmasi, kepastian nomor kursi pilihan sendiri, dan kemudahan e-ticket instan.',
            'hero_image' => $settings['hero_image'] ?? null,
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
            'hero_badge' => 'nullable|string|max:100',
            'hero_title' => 'nullable|string|max:255',
            'hero_subtitle' => 'nullable|string|max:1000',
            'hero_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
        ]);

        // Handle upload foto hero
        if ($request->hasFile('hero_image')) {
            $oldHeroImage = Setting::get('hero_image');
            if ($oldHeroImage && Storage::disk('public')->exists($oldHeroImage)) {
                Storage::disk('public')->delete($oldHeroImage);
            }

            $heroPath = $request->file('hero_image')->store('settings', 'public');
            Setting::set('hero_image', $heroPath, 'Foto latar belakang hero banner');
        } elseif ($request->boolean('remove_hero_image')) {
            $oldHeroImage = Setting::get('hero_image');
            if ($oldHeroImage && Storage::disk('public')->exists($oldHeroImage)) {
                Storage::disk('public')->delete($oldHeroImage);
            }
            Setting::set('hero_image', '', 'Foto latar belakang hero banner');
        }

        // Simpan field teks lainnya
        $textFields = [
            'app_name',
            'contact_email',
            'contact_phone',
            'footer_address',
            'payment_expiry_hours',
            'cancellation_policy',
            'hero_badge',
            'hero_title',
            'hero_subtitle',
        ];

        foreach ($textFields as $field) {
            if (array_key_exists($field, $validated)) {
                Setting::set($field, (string) ($validated[$field] ?? ''));
            }
        }

        return back()->with('success', 'Pengaturan sistem dan banner beranda berhasil diperbarui.');
    }
}