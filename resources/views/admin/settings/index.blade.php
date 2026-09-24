<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="text-sm font-medium text-amber-600">KONFIGURASI</p>
                <h1 class="mt-1 text-2xl font-bold text-slate-950">Pengaturan Sistem</h1>
                <p class="mt-1 text-sm text-slate-500">Kelola informasi dasar aplikasi, kontak, dan aturan bisnis.</p>
            </div>
            <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center gap-2 text-sm font-medium text-slate-600 hover:text-slate-900 transition">
                ← Kembali ke Dashboard
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
            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <h3 class="text-lg font-bold text-slate-900 mb-4">Informasi Aplikasi</h3>
                <div class="grid gap-4 md:grid-cols-2">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Nama Aplikasi</label>
                        <input type="text" name="app_name" value="{{ old('app_name', $data['app_name']) }}" class="w-full rounded-lg border-slate-300 focus:border-amber-500 focus:ring-amber-500" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Batas Waktu Pembayaran (Jam)</label>
                        <input type="number" name="payment_expiry_hours" value="{{ old('payment_expiry_hours', $data['payment_expiry_hours']) }}" class="w-full rounded-lg border-slate-300 focus:border-amber-500 focus:ring-amber-500" required>
                        <p class="mt-1 text-xs text-slate-500">Order akan otomatis expired setelah batas waktu ini.</p>
                    </div>
                </div>
            </div>

            {{-- 2. Informasi Kontak & Footer --}}
            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <h3 class="text-lg font-bold text-slate-900 mb-4">Informasi Kontak & Footer</h3>
                <div class="grid gap-4 md:grid-cols-2">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Email Kontak</label>
                        <input type="email" name="contact_email" value="{{ old('contact_email', $data['contact_email']) }}" class="w-full rounded-lg border-slate-300 focus:border-amber-500 focus:ring-amber-500" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Nomor Telepon / WhatsApp</label>
                        <input type="text" name="contact_phone" value="{{ old('contact_phone', $data['contact_phone']) }}" class="w-full rounded-lg border-slate-300 focus:border-amber-500 focus:ring-amber-500" required>
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-slate-700 mb-1">Alamat (Untuk Footer)</label>
                        <textarea name="footer_address" rows="2" class="w-full rounded-lg border-slate-300 focus:border-amber-500 focus:ring-amber-500" required>{{ old('footer_address', $data['footer_address']) }}</textarea>
                    </div>
                </div>
            </div>

            {{-- 3. Kebijakan --}}
            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <h3 class="text-lg font-bold text-slate-900 mb-4">Kebijakan</h3>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Aturan Pembatalan</label>
                    <textarea name="cancellation_policy" rows="3" class="w-full rounded-lg border-slate-300 focus:border-amber-500 focus:ring-amber-500" required>{{ old('cancellation_policy', $data['cancellation_policy']) }}</textarea>
                    <p class="mt-1 text-xs text-slate-500">Teks ini akan ditampilkan kepada customer saat membatalkan pesanan.</p>
                </div>
            </div>

            {{-- Tombol Simpan --}}
            <div class="flex justify-end pt-4">
                <button type="submit" class="rounded-lg bg-slate-900 px-6 py-2.5 text-sm font-semibold text-white hover:bg-slate-700 transition shadow-sm">
                    Simpan Pengaturan
                </button>
            </div>
        </form>
    </div>
</x-app-layout>