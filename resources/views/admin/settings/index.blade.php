<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="eyebrow">Konfigurasi</p>
                <h1 class="mt-1 page-heading">Pengaturan Sistem</h1>
                <p class="mt-1 text-sm text-slate-500">Kelola informasi dasar aplikasi, kontak, dan aturan bisnis.</p>
            </div>
            <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-slate-600 hover:text-slate-900 transition">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M15 19l-7-7 7-7"/></svg>
                Kembali ke Dashboard
            </a>
        </div>
    </x-slot>

    <div class="mx-auto max-w-4xl px-4 py-7 sm:px-6 lg:px-8 space-y-6">

        {{-- Notifikasi Sukses --}}
        @if(session('success'))
            <div class="rounded-xl border border-emerald-200 bg-emerald-50 p-4 text-sm font-medium text-emerald-800">
                {{ session('success') }}
            </div>
        @endif

        <form method="POST" action="{{ route('admin.settings.update') }}" class="space-y-6">
            @csrf

            {{-- 1. Informasi Aplikasi --}}
            <div class="settings-card">
                <h3 class="section-title mb-4">Informasi Aplikasi</h3>
                <div class="grid gap-4 md:grid-cols-2">
                    <div>
                        <label for="app_name" class="settings-label">Nama Aplikasi</label>
                        <input id="app_name" type="text" name="app_name"
                               value="{{ old('app_name', $data['app_name']) }}"
                               class="settings-input" required>
                        @error('app_name')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="payment_expiry_hours" class="settings-label">Batas Waktu Pembayaran (Jam)</label>
                        <input id="payment_expiry_hours" type="number" name="payment_expiry_hours"
                               value="{{ old('payment_expiry_hours', $data['payment_expiry_hours']) }}"
                               class="settings-input" required>
                        <p class="mt-1 text-xs text-slate-500">Order akan otomatis expired setelah batas waktu ini.</p>
                        @error('payment_expiry_hours')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            {{-- 2. Informasi Kontak & Footer --}}
            <div class="settings-card">
                <h3 class="section-title mb-4">Informasi Kontak & Footer</h3>
                <div class="grid gap-4 md:grid-cols-2">
                    <div>
                        <label for="contact_email" class="settings-label">Email Kontak</label>
                        <input id="contact_email" type="email" name="contact_email"
                               value="{{ old('contact_email', $data['contact_email']) }}"
                               class="settings-input" required>
                        @error('contact_email')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="contact_phone" class="settings-label">Nomor Telepon / WhatsApp</label>
                        <input id="contact_phone" type="text" name="contact_phone"
                               value="{{ old('contact_phone', $data['contact_phone']) }}"
                               class="settings-input" required>
                        @error('contact_phone')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="md:col-span-2">
                        <label for="footer_address" class="settings-label">Alamat (Untuk Footer)</label>
                        <textarea id="footer_address" name="footer_address" rows="2"
                                  class="settings-textarea" required>{{ old('footer_address', $data['footer_address']) }}</textarea>
                        @error('footer_address')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            {{-- 3. Kebijakan --}}
            <div class="settings-card">
                <h3 class="section-title mb-4">Kebijakan</h3>
                <div>
                    <label for="cancellation_policy" class="settings-label">Aturan Pembatalan</label>
                    <textarea id="cancellation_policy" name="cancellation_policy" rows="3"
                              class="settings-textarea" required>{{ old('cancellation_policy', $data['cancellation_policy']) }}</textarea>
                    <p class="mt-1 text-xs text-slate-500">Teks ini akan ditampilkan kepada customer saat membatalkan pesanan.</p>
                    @error('cancellation_policy')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Tombol Simpan --}}
            <div class="flex justify-end pt-4">
                <button type="submit" class="settings-submit">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 13l4 4L19 7"/></svg>
                    Simpan Pengaturan
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
